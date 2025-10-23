<?php
require '../configuracion/db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['course_id']) || !isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
        exit();
    }

    $course_id = $input['course_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("UPDATE user_courses SET completed = 1 WHERE course_id = ? AND user_id = ?");
        $stmt->execute([$course_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Curso marcado como completado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el curso.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el curso.']);
    }
}
?>
