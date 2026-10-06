<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0);
error_reporting(E_ALL);
 
//require_once '../config.php';
require_once __DIR__ . '/../config.php';

$sql = "SELECT * FROM arboles";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Error a la base de dades: " . $conn->error]);
    exit;
}

$arboles = [];

/*while ($row = $result->fetch_assoc()) {
    $row['tiene_imagen'] = (!empty($row['imagen']) && file_exists('../' . $row['imagen']));
    $arboles[] = $row;
}*/

while ($row = $result->fetch_assoc()) {
    // Afegim '../' només perquè PHP trobi el fitxer des de la carpeta /api/
    $ruta_para_php = '../' . $row['imagen'];
    
    $row['tiene_imagen'] = (!empty($row['imagen']) && file_exists($ruta_para_php));
    $arboles[] = $row;
}

// Aqui se tiene que añadir el log en logs/actions.log
$usuarioActivo = $_SESSION['usuario_nombre'] ?? 'Invitado';
$cantidadArboles = count($arboles);
registerAction("Consultó el listado completo de árboles (Total devueltos: $cantidadArboles)", $usuarioActivo);

echo json_encode($arboles);

$conn->close();
?>
