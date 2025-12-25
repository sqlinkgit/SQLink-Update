<?php
    // --- 1. TELEMETRIA ---
    if (isset($_GET['ajax_stats'])) {
        header('Content-Type: application/json');
        $stats = [];
        $model = @file_get_contents('/sys/firmware/devicetree/base/model');
        $stats['hw'] = $model ? str_replace("\0", "", trim($model)) : "Generic Linux";
        $temp_raw = @file_get_contents('/sys/class/thermal/thermal_zone0/temp');
        $stats['temp'] = $temp_raw ? round($temp_raw / 1000, 1) : 0;
        $free = shell_exec('free -m');
        $free_arr = explode("\n", (string)trim($free));
        $mem = preg_split("/\s+/", $free_arr[1]);
        $stats['ram_percent'] = round(($mem[2] / $mem[1]) * 100, 1);
        $dt = disk_total_space('/'); $df = disk_free_space('/');
        $stats['disk_percent'] = round((($dt - $df) / $dt) * 100, 1);
        $ip = trim(shell_exec("hostname -I | awk '{print $1}'"));
        $stats['ip'] = empty($ip) ? "Brak IP" : $ip;
        $ssid = trim(shell_exec("iwgetid -r"));
        if (!empty($ssid)) { $stats['net_type'] = "WiFi"; $stats['ssid'] = $ssid; }
        elseif (!empty($ip) && $ip != "127.0.0.1") { $stats['net_type'] = "LAN"; $stats['ssid'] = ""; }
        else { $stats['net_type'] = "Offline"; $stats['ssid'] = ""; }
        echo json_encode($stats); exit;
    }

    // --- 2. DTMF ---
    if (isset($_POST['ajax_dtmf'])) {
        $code = $_POST['ajax_dtmf'];
        if (preg_match('/^[0-9A-D*#]+$/', $code)) {
            shell_exec("sudo /usr/local/bin/send_dtmf.sh " . escapeshellarg($code));
            echo "OK: $code";
        } else { echo "ERROR"; } exit;
    }

    // --- 3. CONFIG ---
    function parse_svx_conf($file) {
        $ini = []; $curr = "GLOBAL"; if (!file_exists($file)) return [];
        foreach (file($file) as $line) {
            $line = trim($line); if (empty($line) || $line[0] == '#' || $line[0] == ';') continue;
            if ($line[0] == '[' && substr($line, -1) == ']') { $curr = substr($line, 1, -1); $ini[$curr] = []; }
            else { $parts = explode('=', $line, 2); if (count($parts) == 2) $ini[$curr][trim($parts[0])] = trim(trim($parts[1]), '"\''); }
        } return $ini;
    }
    $ini = parse_svx_conf('/etc/svxlink/svxlink.conf');
    $ref = $ini['ReflectorLogic'] ?? []; $simp = $ini['SimplexLogic'] ?? []; $glob = $ini['GLOBAL'] ?? []; $el = $ini['ModuleEchoLink'] ?? [];

    $vals = [
        'Callsign' => $ref['CALLSIGN'] ?? 'N0CALL', 'DefaultTG' => $ref['DEFAULT_TG'] ?? '0',
        'Modules' => $simp['MODULES'] ?? 'Help,Parrot,EchoLink'
    ];
    $vals_el = ['Callsign' => $el['CALLSIGN'] ?? $vals['Callsign'], 'Password' => $el['PASSWORD'] ?? ''];

    // --- 4. WIFI SCAN LOGIC (NAPRAWIONA) ---
    $wifi_scan_results = [];
    $raw_wifi_debug = ""; 

    if (isset($_POST['wifi_scan'])) {
        // Dodajemy opóźnienie 2 sekundy przed odczytem, aby karta zdążyła
        shell_exec("sudo nmcli dev wifi rescan"); // Trigger scan
        sleep(3); // Czekaj na radio
        $cmd = "sudo nmcli --get-values SSID,SIGNAL,SECURITY device wifi list 2>&1";
        $raw_wifi_debug = shell_exec($cmd);

        if (!empty($raw_wifi_debug)) {
            $lines = explode("\n", $raw_wifi_debug);
            $unique_ssids = [];
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $parts = explode(':', $line);
                if (count($parts) >= 3) {
                    $sec = array_pop($parts);   
                    $sig = array_pop($parts);   
                    $ssid = implode(':', $parts); 
                    
                    if (empty($ssid) || $ssid == "--") continue;
                    
                    // TERAZ POKAZUJEMY WSZYSTKO (nawet własny AP), żebyś widział że działa
                    // if ($ssid == "SQLink_WiFi_AP") continue; 

                    if (!isset($unique_ssids[$ssid]) || $unique_ssids[$ssid]['signal'] < $sig) {
                        $unique_ssids[$ssid] = ['ssid' => $ssid, 'signal' => $sig, 'sec' => $sec];
                    }
                }
            }
            $wifi_scan_results = array_values($unique_ssids);
            usort($wifi_scan_results, function($a, $b) { return $b['signal'] - $a['signal']; });
        }
    }

    // --- AKCJE SYSTEMOWE ---
    if (isset($_POST['save_svx_full'])) { 
        $newData = $_POST; unset($newData['save_svx_full'], $newData['active_tab']); 
        file_put_contents('/tmp/svx_new_settings.json', json_encode($newData));
        shell_exec('sudo /usr/bin/python3 /usr/local/bin/update_svx_full.py 2>&1'); 
        shell_exec('sudo /usr/bin/systemctl restart svxlink > /dev/null 2>&1 &');
    }
    
    if (isset($_POST['git_update'])) {
        $out = shell_exec("sudo /usr/local/bin/update_dashboard.sh 2>&1");
        if (strpos($out, 'STATUS: SUCCESS') !== false) {
             shell_exec('sudo /usr/sbin/reboot > /dev/null 2>&1 &');
             echo "<div class='alert alert-success'>Aktualizacja OK. Restart...</div>";
        } else {
             echo "<div class='alert alert-error'>Błąd aktualizacji: $out</div>";
        }
    }

    if (isset($_POST['wifi_connect'])) {
        $ssid = $_POST['ssid']; $pass = $_POST['pass'];
        shell_exec("sudo nmcli dev wifi connect " . escapeshellarg($ssid) . " password " . escapeshellarg($pass) . " > /dev/null 2>&1 &");
    }
    if (isset($_POST['wifi_delete'])) {
        $ssid = $_POST['ssid'];
        shell_exec("sudo nmcli connection delete " . escapeshellarg($ssid) . " > /dev/null 2>&1 &");
    }

    $saved_wifi_list = [];
    $nm_saved = shell_exec("sudo nmcli -t -f NAME connection show 2>/dev/null");
    if ($nm_saved) {
        $lines = explode("\n", trim($nm_saved));
        foreach($lines as $l) { 
            $l = trim($l); 
            if(!empty($l) && !in_array($l, ["Wired connection 1", "lo", "Rescue_AP", "SQLink_WiFi_AP", "preconfigured"])) 
                $saved_wifi_list[] = $l; 
        }
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotspot <?php echo $vals['Callsign']; ?></title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <header>
        <div style="position: relative; display: flex; justify-content: center; align-items: center; min-height: 100px;">
            <img src="sqlink4.png" alt="SQLink" style="position: absolute; left: 15%; top: 50%; transform: translateY(-50%); height: 90px; width: auto;">
            <h1 style="margin: 0; z-index: 2;">Hotspot <?php echo $vals['Callsign']; ?></h1>
            <img src="ant3.PNG" alt="Radio" style="position: absolute; right: 15%; top: 50%; transform: translateY(-50%); height: 90px; width: auto;">
        </div>
        <div class="status-bar"><span id="main-status-dot" class="status-dot red"></span><span id="main-status-text" class="status-text inactive">SYSTEM START...</span></div>
    </header>

    <div class="tabs">
        <button id="btn-Dashboard" class="tab-btn active" onclick="openTab(event, 'Dashboard')">Dashboard</button>
        <button id="btn-Nodes" class="tab-btn" onclick="openTab(event, 'Nodes')">Nodes</button>
        <button id="btn-DTMF" class="tab-btn" onclick="openTab(event, 'DTMF')">DTMF</button>
        <button id="btn-Radio" class="tab-btn" onclick="openTab(event, 'Radio')">Radio</button>
        <button id="btn-Audio" class="tab-btn" onclick="openTab(event, 'Audio')">Audio</button>
        <button id="btn-SvxConfig" class="tab-btn" onclick="openTab(event, 'SvxConfig')">Config</button>
        <button id="btn-WiFi" class="tab-btn" onclick="openTab(event, 'WiFi')">WiFi</button>
        <button id="btn-Power" class="tab-btn" onclick="openTab(event, 'Power')">Zasilanie</button>
        <button id="btn-Logs" class="tab-btn" onclick="openTab(event, 'Logs')">Logi</button>
        <button id="btn-Help" class="tab-btn" onclick="openTab(event, 'Help')">Pomoc</button>
    </div>

    <div id="Dashboard" class="tab-content active"><?php include 'tab_dashboard.php'; ?></div>
    <div id="WiFi" class="tab-content"><?php include 'tab_wifi.php'; ?></div>
    <div id="DTMF" class="tab-content"><?php include 'tab_dtmf.php'; ?></div>
    <div id="Audio" class="tab-content"><?php include 'tab_audio.php'; ?></div>
    <div id="Radio" class="tab-content"><?php include 'tab_radio.php'; ?></div>
    <div id="SvxConfig" class="tab-content"><?php include 'tab_config.php'; ?></div>
    <div id="Nodes" class="tab-content"><?php include 'tab_nodes.php'; ?></div>
    <div id="Power" class="tab-content"><?php include 'tab_power.php'; ?></div>
    <div id="Help" class="tab-content"><?php include 'help.php'; ?></div>
    <div id="Logs" class="tab-content"><div id="log-content" class="log-box">...</div></div>
</div>
<script>const GLOBAL_CALLSIGN = "<?php echo $vals['Callsign']; ?>";</script>
<script src="script.js?v=<?php echo time(); ?>"></script>
</body>
</html>