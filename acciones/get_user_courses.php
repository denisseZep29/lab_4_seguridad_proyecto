<?php
require '../configuracion/db_config.php';

header('Content-Type: application/json');

// Verificar si hay un ID de usuario en la solicitud
if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no especificado.']);
    exit();
}

$userId = $_GET['user_id'];

try {
    // Consultar los cursos inscritos del usuario
    $stmt = $pdo->prepare("
        SELECT courses.title, courses.description 
        FROM user_courses
        INNER JOIN courses ON user_courses.course_id = courses.id
        WHERE user_courses.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'courses' => $cursos]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al cargar los cursos: ' . $e->getMessage()]);
}
