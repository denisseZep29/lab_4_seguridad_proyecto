<?php
require '../configuracion/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Agregar usuario
    if ($action === 'add' && isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $role]);
            header("Location: ../administrador/gestionar_usuarios.php?success=Usuario agregado correctamente");
        } catch (PDOException $e) {
            header("Location: ../administrador/gestionar_usuarios.php?error=Error al agregar usuario");
        }
        exit();
    }

    // Editar usuario
    if ($action === 'edit' && isset($_POST['id'], $_POST['name'], $_POST['email'], $_POST['role'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$name, $email, $role, $id]);
            header("Location: ../administrador/gestionar_usuarios.php?success=Usuario editado correctamente");
        } catch (PDOException $e) {
            header("Location: ../administrador/gestionar_usuarios.php?error=Error al editar usuario");
        }
        exit();
    }

    // Eliminar usuario
    if ($action === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: ../administrador/gestionar_usuarios.php?success=Usuario eliminado correctamente");
        } catch (PDOException $e) {
            header("Location: ../administrador/gestionar_usuarios.php?error=Error al eliminar usuario");
        }
        exit();
    }
}
?>
