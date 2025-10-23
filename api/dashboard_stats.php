<?php
require '../configuracion/db_config.php';

$response = [];

try {
    // Total de usuarios registrados
    $stmt = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
    $response['total_users'] = $stmt->fetch()['total_users'];

    // Total de cursos
    $stmt = $pdo->query("SELECT COUNT(*) AS total_courses FROM courses");
    $response['total_courses'] = $stmt->fetch()['total_courses'];

    // Estadísticas mensuales de usuarios
    $stmt = $pdo->query("
        SELECT MONTH(created_at) AS month, COUNT(*) AS count 
        FROM users 
        WHERE YEAR(created_at) = YEAR(CURDATE()) 
        GROUP BY MONTH(created_at)
    ");
    $userStats = array_fill(1, 12, 0); // Inicializar con 0 para los 12 meses
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userStats[(int)$row['month']] = (int)$row['count'];
    }
    $response['user_monthly_stats'] = $userStats;

    // Estadísticas mensuales de cursos
    $stmt = $pdo->query("
        SELECT MONTH(created_at) AS month, COUNT(*) AS count 
        FROM courses 
        WHERE YEAR(created_at) = YEAR(CURDATE()) 
        GROUP BY MONTH(created_at)
    ");
    $courseStats = array_fill(1, 12, 0); // Inicializar con 0 para los 12 meses
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $courseStats[(int)$row['month']] = (int)$row['count'];
    }
    $response['course_monthly_stats'] = $courseStats;

    $response['success'] = true;
} catch (PDOException $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
