<?php
require '../configuracion/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$message_id]);
        http_response_code(200);
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error al eliminar el mensaje: " . $e->getMessage();
    }
}
