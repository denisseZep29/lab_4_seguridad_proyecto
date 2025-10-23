<?php
header('Content-Type: application/json');
require '../configuracion/db_config.php';

// Verificar si se pasa el nombre de la tabla
if (!isset($_GET['table'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No se especificó la tabla',
    ]);
    exit();
}

$table = $_GET['table'];

// Validar que la tabla sea permitida
$allowedTables = ['users', 'courses', 'contact_messages', 'user_courses'];
if (!in_array($table, $allowedTables)) {
    echo json_encode([
        'success' => false,
        'message' => 'Tabla no permitida',
    ]);
    exit();
}

try {
    // Consultar la tabla
    $stmt = $pdo->query("SELECT * FROM $table");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode([
        'success' => true,
        'data' => $data,
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los datos: ' . $e->getMessage(),
    ]);
}
?>