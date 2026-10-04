<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado: Añadir Árbol</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Añadir Nuevo Árbol</h1>
    
    <form id="form-crear" enctype="multipart/form-data">
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

        <label>Añadir imagen:</label>
        <input type="file" name="imagen" required><br>

        <label>Usuario:</label>
        <input type="text" name="usuario" required><br>

        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver a la lista</a>

    <script src="js/arboles_endpoints.js"></script>
</body>
</html>
