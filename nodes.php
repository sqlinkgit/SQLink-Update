<?php
header('Content-Type: application/json');

// 1. Pobieramy Twój Znak z pliku konfiguracyjnego
$my_call = "MY-HOTSPOT";
$conf = file_get_contents('/etc/svxlink/svxlink.conf');
if (preg_match('/CALLSIGN=(.*)/', $conf, $m)) {
    $my_call = trim($m[1]);
}

// 2. Czytamy logi
$cmd = 'sudo /usr/bin/tail -n 200 /var/log/svxlink';
$output = shell_exec($cmd);
$lines = explode("\n", $output);

$current_tg = 0;
$connected_list = [$my_call];

foreach ($lines as $line) {
    // A. Szukamy TG
    if (preg_match('/Selecting TG #(\d+)/', $line, $match)) {
        $current_tg = (int)$match[1];
    }

    // B. Szukamy listy podłączonych stacji
    if (strpos($line, 'ReflectorLogic: Connected nodes:') !== false) {
        if (preg_match('/Connected nodes: (.*)/', $line, $m)) {
            $raw_list = explode(',', $m[1]);
            $connected_list = array_map('trim', $raw_list);
        }
    }
}

// 3. Budujemy finalną tablicę węzłów
$nodes = [];

if (empty($connected_list)) {
    $connected_list = [$my_call];
}

foreach ($connected_list as $callsign) {
    $nodes[$callsign] = [
        'active' => true,
        'tg' => ''
    ];

    // Jeśli to TY, dodajemy informację o Twoim TG
    if ($callsign === $my_call) {
        if ($current_tg > 0) {
            $nodes[$callsign]['tg'] = $current_tg;
        }
    }
}

// 4. Zwracamy JSON
echo json_encode($nodes);
?>
