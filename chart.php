<?php
session_start();
// Si quieres proteger la página también desde PHP antes de cargar el HTML:
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Estadísticas - Paiporta Arbolado</title>
    <link rel="stylesheet" href="css/style.css">
    
    <!-- 1. Cargar Chart.js desde la carpeta local js/ -->
    <script src="js/chart.umd.js"></script>
    
    <!-- 2. Se carga la logica de los endpoitns -->
    <script src="js/arboles_endpoints.js" defer></script>
</head>
<body>

    <div style="width: 700px; margin: 50px auto; text-align: center;">
        <h2>Estadísticas del Arbolado Urbano</h2>
        <a href="index.php">Volver</a>
        
        <!-- El canvas donde tu JS pintará los datos -->
        <canvas id="graficaPHP"></canvas>
    </div>

</body>
</html>
