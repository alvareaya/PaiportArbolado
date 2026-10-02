<?php
require_once 'config.php';

$estados_enum = [];
$result = $conn->query("SHOW COLUMNS FROM arboles LIKE 'estado'");

if ($result && $row = $result->fetch_assoc()) {
    $type = $row['Type'];
    
    if (preg_match("/^enum\((.*)\)$/", $type, $matches)) {
        $estados_enum = explode(",", str_replace("'", "", $matches[1]));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $especie = $conn->real_escape_string($_POST['especie']);
    $ubicacion = $conn->real_escape_string($_POST['ubicacion']);
    $fecha = $_POST['fecha_plantacion'];
	$estado = $conn->real_escape_string($_POST['estado']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
	
	$ruta_db = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $dir_destino = "./arboles_paiporta/uploads/";
        
        if (!is_dir($dir_destino)) {
            mkdir($dir_destino, 0755, true);
        }

        $nombre_archivo = time() . "_" . basename($_FILES['imagen']['name']);
        $ruta_destino = $dir_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {

            $ruta_db = $conn->real_escape_string($ruta_destino);
        } else {
            echo "Error al mover el archivo a la carpeta de destino.";
            exit();
        }
    } else {
        echo "Error en la subida del archivo.";
        exit();
    }

    $sql = "INSERT INTO arboles (especie, ubicacion, fecha_plantacion, estado, imagen, usuario_registro)
    VALUES ('$especie', '$ubicacion', '$fecha', '$estado', '$ruta_db','$usuario')";

    if ($conn->query($sql)) {
        registerAction("Tree added $especie in $ubicacion", $usuario);
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
		<title>PaiportArbolado: Añadir Árbol</title>
		<link rel="stylesheet" href="css/style.css">
	</head>
<body>
	<h1>Añadir Nuevo Árbol</h1>
	<form method="POST" enctype="multipart/form-data">
		<label>Especie:</label>
		<input type="text" name="especie" required><br>

		<label>Ubicación:</label>
		<input type="text" name="ubicacion" required><br>

		<label>Fecha de Plantación:</label>
		<input type="date" name="fecha_plantacion" required><br>

		<label>Estado:</label>
		<select name="estado" required>
			<option value="">-- Selecciona un estado --</option>
			<?php
			if (!empty($estados_enum)) {
				foreach ($estados_enum as $opcion) {
					echo "<option value='" . $opcion . "'>" . ucfirst($opcion) . "</option>";
				}
			} else {
				echo "<option value=''>No se pudieron cargar los estados</option>";
			}
			?>
		</select><br>

		<label>Añadir imagen:</label>
		<input type="file" name="imagen" required><br>

		<label>Usuario:</label>
		<input type="text" name="usuario" required><br>

		<button type="submit">Guardar</button>
	</form>
	<a href="index.php">Volver a la lista</a>
</body>
</html>
