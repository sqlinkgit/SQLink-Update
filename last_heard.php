<?php
$logFile = '/var/www/html/svx_events.log';
$limit_display = 20;

if (!file_exists($logFile)) {
    echo "<tr><td colspan='3' style='text-align:center; color:#777; padding:10px;'>Oczekiwanie na dane...</td></tr>";
    exit;
}

$lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lines = array_reverse($lines);

$count = 0;
$found_any = false;

foreach ($lines as $line) {
    if ($count >= $limit_display) break;

    $line = trim($line);

    if (strpos($line, 'Talker start on TG') !== false) {
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

if (!$found_any) {
    echo "<tr><td colspan='3' style='text-align:center; color:#777; padding:10px;'>Brak ostatnich rozmów w historii.</td></tr>";
}
?>