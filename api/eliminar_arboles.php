<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../config.php';

session_start();
    
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["success" => false, "error" => "Método no permitido. Se requiere DELETE."]);
    exit;
}

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode(["success" => false, "error" => "ID de árbol no proporcionado o inválido."]);
    exit;
}


$usuario = "admin";

$sql = "DELETE FROM arboles WHERE id = $id";

if ($conn->query($sql)) {
    if (function_exists('registerAction')) {
        registerAction("Tree Deleted: ID $id", $usuario);
    }
    echo json_encode(["success" => true, "mensaje" => "¡Árbol eliminado correctamente!"]);
} else {
    echo json_encode(["success" => false, "error" => "Error al eliminar en la base de datos: " . $conn->error]);
}

$usuarioActivo = $_SESSION['usuario_nombre'] ?? 'Invitado';
registerAction("Arbol eliminado con exito)", $usuarioActivo);


$conn->close();
exit;
?>
