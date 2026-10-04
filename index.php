<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado : Árboles de Paiporta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Gestión de Árboles de Paiporta</h1>
    <a href="crear.php">Añadir nuevo árbol</a>
    <input type="text" id="buscar" placeholder="Buscar por especie o ubicación..." onkeyup="buscarArboles()">

    <table border="1">
        <thead>
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
        </thead>
        <tbody id="tabla-arboles">
        </tbody>
    </table>

    <script src="js/arboles_endpoints.js"></script>
</body>
</html>

<?php $conn->close(); ?>
