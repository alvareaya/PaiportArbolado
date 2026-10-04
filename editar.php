<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

// Obtener datos del árbol
$sql = "SELECT * FROM arboles WHERE id = $id";
$result = $conn->query($sql);
$arbol = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $especie = $conn->real_escape_string($_POST['especie']);
    $ubicacion = $conn->real_escape_string($_POST['ubicacion']);
    $fecha = $_POST['fecha_plantacion'];
    $estado = $conn->real_escape_string($_POST['estado']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
    
    $ruta_imagen_bd = $arbol['imagen']; 

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpeta_destino = './arboles_paiporta/uploads/';
        $nombre_original = basename($_FILES['imagen']['name']);
        
        $nombre_final = time() . "_" . str_replace(" ", "", $nombre_original);
        $fichero_subido = $carpeta_destino . $nombre_final;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
            $ruta_imagen_bd = $fichero_subido;
        } else {
            echo "Error al guardar la imagen en el servidor.";
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
        registerAction("Tree Updated: ID $id", $usuario);
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado : Editar Árbol</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilo para que la imagen se vea a tamaño mediano */
        .img-mediana {
            max-width: 300px;
            height: auto;
            display: block;
            margin: 10px 0 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <h1>Editar Árbol</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $arbol['id'] ?>">

        <label>Especie:</label>
        <input type="text" name="especie" value="<?= htmlspecialchars($arbol['especie']) ?>" required><br>

        <label>Ubicación:</label>
        <input type="text" name="ubicacion" value="<?= htmlspecialchars($arbol['ubicacion']) ?>" required><br>

        <label>Fecha de Plantación:</label>
        <input type="date" name="fecha_plantacion" value="<?= $arbol['fecha_plantacion'] ?>" required><br>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="sano" <?= $arbol['estado'] === 'sano' ? 'selected' : '' ?>>Sano</option>
            <option value="enfermo" <?= $arbol['estado'] === 'enfermo' ? 'selected' : '' ?>>Enfermo</option>
            <option value="talado" <?= $arbol['estado'] === 'talado' ? 'selected' : '' ?>>Talado</option>
        </select><br>

        <label>Usuario:</label>
        <input type="text" name="usuario" value="<?= htmlspecialchars($arbol['usuario_registro']) ?>" required><br>

        <label>Imagen del Árbol:</label><br>
        
        <?php if (!empty($arbol['imagen'])): ?>
            <img id="vista-previa" src="<?= htmlspecialchars($arbol['imagen']) ?>" alt="Imagen del árbol" class="img-mediana">
        <?php else: ?>
            <img id="vista-previa" src="" alt="Vista previa" class="img-mediana" style="display: none;">
            <p id="sin-imagen-texto" style="color: gray; font-style: italic;">No hay imagen disponible para este árbol.</p>
        <?php endif; ?>

        <label>Cambiar Imagen:</label>
        <input type="file" id="input-imagen" name="imagen" accept="image/*"><br><br>

        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver a la lista</a>

    <script>
        document.getElementById('input-imagen').addEventListener('change', function(event) {
            const archivo = event.target.files[0];
            
            if (archivo) {
                const lector = new FileReader();
                
                lector.onload = function(e) {
                    const imgPreview = document.getElementById('vista-previa');
                    const txtNoImage = document.getElementById('sin-imagen-texto');
                    
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                    
                    if (txtNoImage) {
                        txtNoImage.style.display = 'none';
                    }
                }
                
                lector.readAsDataURL(archivo);
            }
        });
    </script>
</body>
</html>
