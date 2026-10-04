<?php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../config.php';

$sql = "SELECT * FROM arboles";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Error a la base de dades: " . $conn->error]);
    exit;
}

$arboles = [];

while ($row = $result->fetch_assoc()) {
    $row['tiene_imagen'] = (!empty($row['imagen']) && file_exists('../' . $row['imagen']));
    $arboles[] = $row;
}

echo json_encode($arboles);

$conn->close();
?>
