<?php
    header('Content-Type: application/json; charset=utf-8');
    ini_set('display_errors', 0);
    error_reporting(E_ALL);

    session_start();
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php');
        exit;
    }

    require_once '../config.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["success" => false, "error" => "Método no permitido."]);
        exit;
    }

    $especie = $conn->real_escape_string($_POST['especie'] ?? '');
    $ubicacion = $conn->real_escape_string($_POST['ubicacion'] ?? '');
    $fecha = $conn->real_escape_string($_POST['fecha_plantacion'] ?? '');
    $estado = $conn->real_escape_string($_POST['estado'] ?? '');
    $usuario = $_SESSION['usuario_nombre'];

    $ruta_db = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $dir_destino = "../arboles_paiporta/uploads/";
        
        if (!is_dir($dir_destino)) {
            mkdir($dir_destino, 0755, true);
        }

        $nombre_archivo = time() . "_" . basename($_FILES['imagen']['name']);
        $ruta_destino = $dir_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $ruta_db = $conn->real_escape_string("arboles_paiporta/uploads/" . $nombre_archivo);
        } else {
            echo json_encode(["success" => false, "error" => "Error al mover el archivo a la carpeta de destino."]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "error" => "Error en la subida del archivo o imagen no seleccionada."]);
        exit;
    }

    $sql = "INSERT INTO arboles (especie, ubicacion, fecha_plantacion, estado, imagen, usuario_registro)
            VALUES ('$especie', '$ubicacion', '$fecha', '$estado', '$ruta_db', '$usuario')";

    if ($conn->query($sql)) {
        if (function_exists('registerAction')) {
            registerAction("Tree added $especie in $ubicacion", $usuario);
        }
        echo json_encode(["success" => true, "mensaje" => "¡Árbol añadido con éxito!"]);
    } else {
        echo json_encode(["success" => false, "error" => "Error en la base de datos: " . $conn->error]);
    }

    $conn->close();
?>
