<?php
header('Content-Type: application/json');

$my_call = "MY-HOTSPOT";
$conf = @file_get_contents('/etc/svxlink/svxlink.conf');
if ($conf && preg_match('/CALLSIGN=(.*)/', $conf, $m)) {
    $my_call = trim($m[1]);
}

$nodes = [];
$nodes[$my_call] = ['active' => true, 'tg' => ''];

$logFile = '/var/www/html/svx_events.log';

if (file_exists($logFile)) {
    $lines = file($logFile);
    foreach ($lines as $line) {
        $line = trim($line);

        if (preg_match('/ReflectorLogic: Node ([A-Z0-9-\/]+) joined/', $line, $matches)) {
            $call = $matches[1];
            if ($call !== $my_call) {
                $nodes[$call] = ['active' => true, 'tg' => ''];
            }
        }

        if (preg_match('/ReflectorLogic: Node ([A-Z0-9-\/]+) left/', $line, $matches)) {
            $call = $matches[1];
            if (isset($nodes[$call])) {
                unset($nodes[$call]);
            }
        }

        if (strpos($line, 'Connected nodes:') !== false && preg_match('/Connected nodes: (.*)/', $line, $matches)) {
            $raw_list = explode(',', $matches[1]);
            foreach($raw_list as $n) {
                $n = trim($n);
                if (!empty($n) && $n !== $my_call) {
                    $nodes[$n] = ['active' => true, 'tg' => ''];
                }
            }
        }

        if (preg_match('/Talker start on TG #(\d+): ([A-Z0-9-\/]+)/', $line, $matches)) {
            $tg = $matches[1];
            $call = $matches[2];
            
            if (!isset($nodes[$call])) {
                $nodes[$call] = ['active' => true, 'tg' => $tg];
            } else {
                $nodes[$call]['tg'] = $tg;
            }
        }

        if (preg_match('/ReflectorLogic: Selecting TG #(\d+)/', $line, $matches)) {
            $nodes[$my_call]['tg'] = $matches[1];
        }
    }
}

echo json_encode($nodes);
?>