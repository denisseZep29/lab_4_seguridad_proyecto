<?php
// reset_password.php
require '../configuracion/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    // Verificar si las contraseñas coinciden
    if ($newPassword !== $confirmPassword) {
        echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden."]);
        exit();
    }

    // Encriptar la nueva contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    try {
        // Verificar si el correo existe en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(["status" => "error", "message" => "No se encontró un usuario con ese correo electrónico."]);
        } else {
            // Actualizar la contraseña
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            echo json_encode(["status" => "success", "message" => "Contraseña cambiada con éxito."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al cambiar la contraseña."]);
    }
}
?>