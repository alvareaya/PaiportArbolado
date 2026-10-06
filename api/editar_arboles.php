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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = intval($_GET['id'] ?? 0);
    
    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID de árbol no proporcionado o inválido."]);
        exit;
    }

    $sql = "SELECT * FROM arboles WHERE id = $id";
    $result = $conn->query($sql);
    $arbol = $result->fetch_assoc();

    if (!$arbol) {
        echo json_encode(["success" => false, "error" => "Árbol no encontrado."]);
        exit;
    }

    echo json_encode($arbol);
    $conn->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);

    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID de árbol no proporcionado."]);
        exit;
    }

    $res_actual = $conn->query("SELECT imagen FROM arboles WHERE id = $id");
    $arbol_actual = $res_actual->fetch_assoc();
    
    if (!$arbol_actual) {
        echo json_encode(["success" => false, "error" => "El árbol que intentas editar no existe."]);
        exit;
    }

    $especie = $conn->real_escape_string($_POST['especie'] ?? '');
    $ubicacion = $conn->real_escape_string($_POST['ubicacion'] ?? '');
    $fecha = $conn->real_escape_string($_POST['fecha_plantacion'] ?? '');
    $estado = $conn->real_escape_string($_POST['estado'] ?? '');
    //$usuario = $conn->real_escape_string($_POST['usuario'] ?? '');
    $usuario = $_SESSION['usuario_nombre'];
    
    $ruta_imagen_bd = $arbol_actual['imagen']; 

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        $carpeta_destino = '../arboles_paiporta/uploads/';
        
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        $nombre_original = basename($_FILES['imagen']['name']);
        $nombre_final = time() . "_" . str_replace(" ", "", $nombre_original);
        $fichero_subido = $carpeta_destino . $nombre_final;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {

            $ruta_imagen_bd = "../arboles_paiporta/uploads/" . $nombre_final;
        } else {
            echo json_encode(["success" => false, "error" => "Error al guardar la imagen en el servidor."]);
            exit;
        }
    }

    $sql = "UPDATE arboles SET
            especie = '$especie',
            ubicacion = '$ubicacion',
            fecha_plantacion = '$fecha',
            estado = '$estado',
            imagen = '$ruta_imagen_bd'
            WHERE id = $id";

    if ($conn->query($sql)) {
        if (function_exists('registerAction')) {
            registerAction("Tree Updated: ID $id", $usuario);
        }
        echo json_encode(["success" => true, "mensaje" => "¡Árbol actualizado con éxito!"]);
    } else {
        echo json_encode(["success" => false, "error" => "Error en la base de datos: " . $conn->error]);
    }

    $usuarioActivo = $_SESSION['usuario_nombre'] ?? 'Invitado';
    registerAction("Arbol $especie editado con exito)", $usuarioActivo);

    $conn->close();
    exit;
}
