<?php
    // 1. ODCZYT LOGÓW
    $cmd = 'sudo /usr/bin/tail -n 100 /var/log/svxlink 2>&1';
    $output = shell_exec($cmd);

    // 2. DIAGNOSTYKA BŁĘDÓW ODCZYTU
    if (!$output) {
        echo "<tr><td colspan='3' style='color:red; text-align:center;'>Błąd: Brak wyniku komendy tail.</td></tr>";
        exit;
    }
    
    if (strpos($output, 'Permission denied') !== false || strpos($output, 'password') !== false) {
        echo "<tr><td colspan='3' style='background:#300; color:#F44336; text-align:center; padding:10px;'>
              <b>BŁĄD UPRAWNIEŃ:</b><br>
              PHP nie może odczytać logów.<br>
              <small>Upewnij się, że w <i>sudo visudo</i> jest linia:<br>
              <code>www-data ALL=(root) NOPASSWD: /usr/bin/tail</code></small>
              </td></tr>";
        exit;
    }

    $lines = explode("\n", $output);
    $lines = array_reverse($lines);

    $count = 0;
    $limit = 20; 
    $found_any = false;

    foreach ($lines as $line) {
        if ($count >= $limit) break;
        $line = trim($line);
        if (empty($line)) continue;

        // 3. SZUKANIE ROZMÓW (Metoda "Luźna")
        if (strpos($line, 'ReflectorLogic: Talker start on TG') !== false) {
            

            if (preg_match('/TG #(\d+):\s*([A-Z0-9-\/]+)/', $line, $m_call)) {
                $tg = $m_call[1];
                $callsign = trim($m_call[2]);
                $time_display = "---";
                if (preg_match('/(\d{2}:\d{2}:\d{2})/', $line, $m_time)) {
                    $time_display = $m_time[1];
                }

                echo "<tr>";
                echo "<td><b style='color:#ccc;'>$time_display</b></td>";
                echo "<td><span class='badge-tg' style='background:#333; padding:2px 6px; border-radius:4px; font-size:12px; color:#FF9800;'>TG $tg</span></td>";
                echo "<td><b style='color:#4CAF50;'>$callsign</b></td>";
                echo "</tr>";

                $count++;
                $found_any = true;
            }
        }
    }

    // 4. DIAGNOSTYKA BRAKU WYNIKÓW
    if (!$found_any) {
        echo "<tr><td colspan='3' style='text-align:center; color:#777; padding:10px;'>
              Brak aktywności w ostatnich 100 liniach logu.<br>
              <br>
              <small style='color:#555;'>Ostatnia linia logu (Debug):<br>".htmlspecialchars($lines[0] ?? 'Brak')."</small>
              </td></tr>";
    }
?>
