<?php
session_start(); // Inicia la sesión

// Destruye todas las variables de sesión
$_SESSION = [];

// Si se desea destruir la sesión completamente, también eliminar la cookie de sesión.
if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al usuario a la página de login o a la página principal
//header("Location: login.php"); //si lo ejecuto con directo del action (buttom)
echo '<script>
        alert("¡Vuelve Pronto!");
        window.location.href = "../../login.php";
      </script>';
//exit();


?>