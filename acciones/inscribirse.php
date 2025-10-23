<?php
require '../configuracion/db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['course_id']) || !isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para inscribirte en este curso.']);
        exit();
    }

    $course_id = $input['course_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Verificar si ya está inscrito
        $stmt = $pdo->prepare("SELECT * FROM user_courses WHERE course_id = ? AND user_id = ?");
        $stmt->execute([$course_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya estás inscrito en este curso.']);
            exit();
        }

        // Insertar inscripción
        $stmt = $pdo->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);

        echo json_encode(['success' => true, 'message' => 'Te has inscrito en el curso exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al inscribirse en el curso.']);
    }
}
?>
