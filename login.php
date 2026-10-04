<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>

    <h2>Login de Usuarios</h2>
    
    <form id="form-login">
        <label for="input-usuario">Usuario:</label><br>
        <input type="text" id="input-usuario" required><br><br>
        
        <label for="input-contrasena">Contraseña:</label><br>
        <input type="password" id="input-contrasena" required><br><br>
        
        <button type="submit">Acceder</button>
    </form>

    <script src="js/usuarios_endpoints.js"></script>
</body>
</html>