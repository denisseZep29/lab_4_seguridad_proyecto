<?php
header('Content-Type: application/json');
require '../configuracion/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    if (!$name || !$email || !$message) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y el email debe ser válido."]);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);

        echo json_encode(["status" => "success", "message" => "Tu mensaje ha sido enviado con éxito. Nos pondremos en contacto contigo pronto."]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log para verificar errores
        echo json_encode(["status" => "error", "message" => "Error al guardar el mensaje en la base de datos."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método de solicitud no válido."]);
}
?>
