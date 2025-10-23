<?php
// login.php
require '../configuracion/db_config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : null; // Captura el course_id si está presente

    try {
        // Consulta para obtener el usuario con su rol
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica si se encontró el usuario
        if (!$user) {
            echo "No se encontró un usuario con ese correo electrónico.";
            exit();
        }

        // Verifica la contraseña
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Regenera el ID de sesión

            // Configura la sesión del usuario
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = strtolower(trim($user['role'])); // Normaliza el rol

            // Redirige según el rol o al curso si course_id está presente
            if ($course_id) {
                header("Location: http://localhost:80/Tutorias_Online/usuario/cursos.php?course_id=$course_id");
            } elseif ($_SESSION['role'] === 'admin') {
                header("Location: http://localhost:80/Tutorias_Online/administrador/inicio_admins.php");
            } else {
                header("Location: http://localhost:80/Tutorias_Online/usuario/inicio_usuarios.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
            exit();
        }
    } catch (PDOException $e) {
        // Manejo de errores de base de datos
        echo "Error de conexión a la base de datos: " . $e->getMessage();
        exit();
    }
}