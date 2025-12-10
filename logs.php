<?php
$output = shell_exec('sudo /usr/bin/tail -n 300 /var/log/svxlink');

echo nl2br(htmlspecialchars($output));
?>
