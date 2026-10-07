<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado : Editar Árbol</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
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
    <form id="form-editar" enctype="multipart/form-data">
        <label>Especie:</label>
        <input type="text" name="especie" required><br>

        <label>Ubicación:</label>
        <input type="text" name="ubicacion" required><br>

        <label>Fecha de Plantación:</label>
        <input type="date" name="fecha_plantacion" required><br>

        <label>Estado:</label>

        <select name="estado" id="select-estado" required>
            <option value="">-- Selecciona un estado --</option>
        </select><br>

        <!--<label>Usuario:</label>
        <input type="text" name="usuario" required><br>-->

        <label>Imagen del Árbol:</label><br>
        <img id="vista-previa" src="" alt="Imagen del árbol" class="img-mediana" style="display: none;">
        <p id="sin-imagen-texto" style="color: gray; font-style: italic; display: none;">No hay imagen disponible para este árbol.</p>

        <label>Cambiar Imagen:</label>
        <input type="file" name="imagen"><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
    
    <a href="index.php" class="btn-back">← Volver a la lista</a>

    <script src="js/arboles_endpoints.js"></script>
</body>
</html>
