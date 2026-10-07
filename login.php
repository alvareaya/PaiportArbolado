<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Vinculación del archivo CSS externo -->
    <link rel="stylesheet" href="css/login_style.css">
</head>
<body>

    <div class="login-container">
        <h2>Incio de sesion</h2>
        
        <form id="form-login">
            <div class="form-group">
                <label for="input-usuario">Usuario</label>
                <input type="text" id="input-usuario" placeholder="Introduce tu usuario" autocomplete="username" required>
            </div>
            
            <div class="form-group">
                <label for="input-contrasena">Contraseña</label>
                <input type="password" id="input-contrasena" placeholder="••••••••" autocomplete="current-password" required>
            </div>
            
            <button type="submit" class="btn-submit">Acceder</button>
        </form>
    </div>

    <script src="js/usuarios_endpoints.js"></script>
</body>
</html>
