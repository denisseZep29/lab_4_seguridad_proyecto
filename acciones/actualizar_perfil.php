<?php
require '../configuracion/db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Revisar si la solicitud es para actualizar el perfil o cambiar la contraseña
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        // Actualizar nombre y correo electrónico
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($name) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit();
        }

        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user_id]);

            echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil.']);
        }
    } elseif ($action === 'change_password') {
        // Cambiar contraseña
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit();
        }

        if ($new_password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            exit();
        }

        try {
            // Verificar contraseña actual
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($current_password, $user['password'])) {
                echo json_encode(['success' => false, 'message' => 'La contraseña actual no es correcta.']);
                exit();
            }

            // Actualizar nueva contraseña
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);

            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción inválida.']);
    }
}
?>
