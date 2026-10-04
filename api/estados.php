<?php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();

require_once '../config.php'; 

$estados_enum = [];
$result = $conn->query("SHOW COLUMNS FROM arboles LIKE 'estado'");

if ($result && $row = $result->fetch_assoc()) {
    $type = $row['Type'];
    if (preg_match("/^enum\((.*)\)$/", $type, $matches)) {
        $estados_enum = explode(",", str_replace("'", "", $matches[1]));
    }
}

ob_clean();

echo json_encode($estados_enum);
$conn->close();
?>
