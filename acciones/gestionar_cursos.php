<?php
require '../configuracion/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Agregar curso
    if ($action === 'add' && isset($_POST['playlist_id'], $_POST['title'], $_POST['description'])) {
        $playlistId = $_POST['playlist_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];

        try {
            $stmt = $pdo->prepare("INSERT INTO courses (youtube_playlist_id, title, description) VALUES (?, ?, ?)");
            $stmt->execute([$playlistId, $title, $description]);
            header("Location: ../administrador/gestionar_cursos.php?success=Curso agregado correctamente");
        } catch (PDOException $e) {
            header("Location: ../administrador/gestionar_cursos.php?error=Error al agregar curso");
        }
        exit();
    }

    // Editar curso
    if ($action === 'edit' && isset($_POST['id'], $_POST['title'], $_POST['description'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];

        try {
            $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $id]);
            header("Location: ../administrador/gestionar_cursos.php?success=Curso editado correctamente");
        } catch (PDOException $e) {
            header("Location: ../administrador/gestionar_cursos.php?error=Error al editar curso");
        }
        exit();
    }

    // Eliminar curso
    if ($action === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: ../administrador/gestionar_cursos.php?success=Curso eliminado correctamente");
        } catch (PDOException $e) {
            header("Location: ../administrador/gestionar_cursos.php?error=Error al eliminar curso");
        }
        exit();
    }
}
?>
