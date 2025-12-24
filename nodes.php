<?php
header('Content-Type: application/json');

$url = 'http://146.59.87.158:8091/status';

$ctx = stream_context_create(array(
    'http' => array(
        'timeout' => 2 
    )
));

$json = @file_get_contents($url, false, $ctx);

if ($json === FALSE) {
    echo json_encode([]);
} else {
    echo $json;
}
?>