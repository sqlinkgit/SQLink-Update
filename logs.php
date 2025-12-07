<?php
// Czytamy ostatnie 300 linii logu SvxLink
// Używamy sudo, bo logi należą do roota
$output = shell_exec('sudo /usr/bin/tail -n 300 /var/log/svxlink');

// Zamieniamy nowe linie na <br> żeby ładnie wyglądało w HTML
echo nl2br(htmlspecialchars($output));
?>
