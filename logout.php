<?php
// 1. Iniciar la sesión para poder acceder a ella
session_start();

// 2. Desvincular todas las variables de sesión
$_SESSION = array();

// 3. Borrar las cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir la sesión en el servidor
session_destroy();

// 5. Redirigir al usuario a la página de login
header("Location: login.php");
exit;
?>
