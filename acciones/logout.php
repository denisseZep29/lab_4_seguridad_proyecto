<?php
session_start();

// Elimina todas las variables de sesión
$_SESSION = [];

// Destruye la sesión
session_destroy();

// Asegúrate de que las cookies de sesión sean eliminadas
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirige al usuario a la página de inicio
header("Location: http://localhost:80/Tutorias_Online/publico/inicio.html");
exit();
?>