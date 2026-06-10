<?php
require_once 'dbconnect.php';

$portName = 'COM4';
$baudRate = 9600;

$serialPort = dio_open("\\\\.\\{$portName}", O_RDWR);
if (!$serialPort) {
    die(json_encode(['error' => "Impossible d'ouvrir $portName"]));
}
$output = [];
exec("mode {$portName} baud={$baudRate} data=8 stop=1 parity=n xon=on", $output);

$ligne = '';
$debut = time();
while (time() - $debut < 5) {
    $c = dio_read($serialPort, 1);
    if ($c === "\n") break;
    if ($c) $ligne .= $c;
}
dio_close($serialPort);

$ligne = trim($ligne);

if (empty($ligne) || strpos($ligne, 'STATUS:') !== false) {
    echo json_encode(['status' => 'ignored', 'raw' => $ligne]);
    exit;
}

$data = [];
foreach (explode(';', $ligne) as $part) {
    $kv = explode(':', $part);
    if (count($kv) === 2) $data[trim($kv[0])] = trim($kv[1]);
}

if (!isset($data['GAZ'], $data['ALERT'])) {
    echo json_encode(['error' => 'Format invalide', 'raw' => $ligne]);
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO gas_measures_g7a (sensor_name, gas_type, gas_value, danger_level)
     VALUES ('MQ135', 'CO2/gaz', :gaz, :alert)"
);
$stmt->execute([
    ':gaz'   => (int)$data['GAZ'],
    ':alert' => (int)$data['ALERT'],
]);

echo json_encode([
    'status'  => 'ok',
    'gaz'     => $data['GAZ'],
    'alerte'  => $data['ALERT'],
    'timestamp' => date('Y-m-d H:i:s'),
]);
?>