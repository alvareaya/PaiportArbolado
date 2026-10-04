<?php

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0); // Cambia a 1 si necesitas depurar y ver el error real
error_reporting(E_ALL);


require_once '../config.php';
session_start();


$input = json_decode(file_get_contents('php://input'), true);
$nombre_usuario = $input['nombre'] ?? '';
$contrasena_ingresada = $input['contrasena'] ?? '';

if (empty($nombre_usuario) || empty($contrasena_ingresada)) {
    http_response_code(400);
    echo json_encode(['error' => 'El usuario y la contraseña son obligatorios.']);
    exit;
}


$sql = "SELECT id, nombre, contrasena FROM usuarios WHERE nombre = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param('s', $nombre_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();


if ($usuario && password_verify($contrasena_ingresada, $usuario['contrasena'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    
    echo json_encode([
        'success' => true,
        'mensaje' => 'Sesión iniciada correctamente.',
        'usuario' => [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre']
        ]
    ]);
    exit; // <--- IMPRESCINDIBLE: Corta el script aquí
} else {
    http_response_code(401);
    echo json_encode(['error' => 'El usuario o la contraseña son incorrectos.']);
    exit; // <--- Buenas prácticas para cerrar el flujo limpiamente
}


$stmt->close();
$conn->close();
?>
