<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'config.php';

$sql = "SELECT * FROM arboles";
$result = $conn->query($sql);

if (!$result) {
    die("Error a la base de dades: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> PaiportArbolado : Árboles de Paiporta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Gestión de Árboles de Paiporta</h1>
    <a href="crear.php"> Añadir nuevo árbol</a>
    <input type="text" id="buscar" placeholder="Buscar por especie o ubicación..." onkeyup="buscarArboles()">

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Especie</th>
            <th>Ubicación</th>
            <th>Fecha Plantación</th>
            <th>Estado</th>
            <th>Imagen</th>
            <th>Registro Usuario</th>
            <th>Acciones</th>
        </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['especie']) ?></td>
            <td><?= htmlspecialchars($row['ubicacion']) ?></td>
            <td><?= $row['fecha_plantacion'] ?></td>
            <td><?= $row['estado'] ?></td>
            <td><?= !empty($row['imagen']) ? $row['imagen'] : 'Sin imagen' ?></td>
            <td><?= $row['usuario_registro'] ?></td>
            <td>
            <a href="editar.php?id=<?= $row['id'] ?>">Editar</a>
            <a href="eliminar.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este árbol?')">Eliminar</a>
            </td>
            </tr>
            <?php endwhile; ?>
    </table>

    <script src="js/script.js"></script>
    </body>
</html>
<?php $conn->close(); ?>
