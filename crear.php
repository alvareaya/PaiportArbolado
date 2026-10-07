<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado: Añadir Árbol</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Añadir Nuevo Árbol</h1>
    
    <!-- Eliminamos los <br> ya que el CSS maneja los espaciados de forma limpia -->
    <form id="form-crear" enctype="multipart/form-data">
        <label>Especie:</label>
        <input type="text" name="especie" placeholder="Ej. Olivo, Pino..." required>

        <label>Ubicación:</label>
        <input type="text" name="ubicacion" placeholder="Ej. Plaza Mayor, Calle Colón..." required>

        <label>Fecha de Plantación:</label>
        <input type="date" name="fecha_plantacion" required>

        <label>Estado:</label>
        <select name="estado" id="select-estado" required>
            <option value="">-- Selecciona un estado --</option>
        </select>

        <label>Añadir imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit">Guardar Árbol</button>
    </form>
    
    <a href="index.php" class="btn-back">← Volver a la lista</a>

    <script src="js/arboles_endpoints.js"></script>
</body>
</html>
