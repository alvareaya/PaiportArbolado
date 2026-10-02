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

    $sql = "INSERT INTO arboles (especie, ubicacion, fecha_plantacion, estado, usuario_registro)
    VALUES ('$especie', '$ubicacion', '$fecha', '$estado','$usuario')";

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
	<form method="POST">
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

		<label>Usuario:</label>
		<input type="text" name="usuario" required><br>

		<button type="submit">Guardar</button>
	</form>
	<a href="index.php">Volver a la lista</a>
</body>
</html>
