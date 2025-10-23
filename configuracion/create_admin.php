<?php
require '../configuracion/db_config.php';

$name = 'Keneth';
$email = 'Keneth@gmail.com';
$password = 'admin123';
$role = 'admin';

// Hash de la contraseña usando password_hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $hashed_password, $role]);

echo "Administrador creado correctamente";
?>
