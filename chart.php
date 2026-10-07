<?php
session_start();
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
    
    <script src="js/chart.umd.js"></script>
    <script src="js/arboles_endpoints.js" defer></script>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            text-align: center;
        }
        .chart-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }
        .chart-container {
            position: relative;
            height: 350px; 
            width: 100%;
        }
        .btn-volver {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Estadísticas del Arbolado Urbano</h2>
        
        <div class="chart-card">
            <h3 style="margin-top:0; color:#4a5568; font-weight: 600;">Distribución de Árboles por Especie</h3>
            <div class="chart-container">
                <canvas id="graficaPHP"></canvas>
            </div>
        </div>

        <a href="index.php" class="btn-volver">Volver al inicio</a>
    </div>

</body>
</html>
