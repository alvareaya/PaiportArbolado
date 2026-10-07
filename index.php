<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado : Árboles de Paiporta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <!-- Eliminado el style en línea, ahora se controla 100% desde el CSS -->
    <header>
        <h1>Gestión de Árboles de Paiporta</h1>
        <div>
            <span>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong></span>
            <a href="chart.php">Ver gráfica</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <!-- Agrupamos el botón y el buscador en una barra de acciones -->
    <div class="actions-bar">
        <a href="crear.php" class="btn-add">Añadir nuevo árbol</a>
        <input type="text" id="buscar" placeholder="Buscar por especie, ubicación o estado..." onkeyup="buscarArboles()">
    </div>

    <!-- Eliminado el border="1" para que el CSS maneje los bordes de manera limpia -->
    <table>
        <thead>
            <tr>
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
            <!-- Recuerda que cuando inyectes dinámicamente los botones de acción desde tu JS, 
                 puedes usar las clases 'btn-action btn-edit' y 'btn-action btn-delete' 
                 para que adopten los estilos correctos -->
        </tbody>
    </table>
    
    <script src="js/arboles_endpoints.js"></script>
</body>
</html>
