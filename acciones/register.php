<?php
// register.php
require '../configuracion/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        echo "Registro exitoso. Puedes iniciar sesión ahora.";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "El correo electrónico ya está registrado.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>