<?php
require '../configuracion/db_config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../publico/inicio.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener la información del usuario desde la base de datos
try {
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    echo "<p>Error al cargar la información del perfil.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../recursos/css/perfil.css">
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
<!-- Barra lateral -->
<?php include '../componentes/barra_lateral.php'; ?>

<main class="perfil-dashboard">
    <header class="perfil-header">
        <h1>Perfil</h1>
        <p>Gestiona tu información personal y cambia tu contraseña.</p>
    </header>

    <div class="form-container">
        <!-- Formulario de información personal -->
        <form id="perfilForm" class="perfil-form">
            <h2>Información Personal</h2>
            <input type="hidden" name="action" value="update_profile">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn-actualizar">Actualizar Perfil</button>
            <p id="perfilMessage" class="message"></p>
        </form>

        <!-- Formulario para cambiar contraseña -->
        <form id="passwordForm" class="password-form">
            <h2>Cambiar Contraseña</h2>
            <input type="hidden" name="action" value="change_password">
            <div class="form-group">
                <label for="current_password">Contraseña Actual</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Nueva Contraseña</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn-actualizar">Cambiar Contraseña</button>
            <p id="passwordMessage" class="message"></p>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    feather.replace(); // Reemplaza los íconos con Feather Icons

    // Actualizar perfil
    document.getElementById('perfilForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const messageElement = document.getElementById('perfilMessage');

        fetch('../acciones/actualizar_perfil.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageElement.textContent = data.message;
                    messageElement.style.color = 'green';
                } else {
                    messageElement.textContent = data.message;
                    messageElement.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageElement.textContent = 'Error al actualizar el perfil.';
                messageElement.style.color = 'red';
            });
    });

    // Cambiar contraseña
    document.getElementById('passwordForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const messageElement = document.getElementById('passwordMessage');

        fetch('../acciones/actualizar_perfil.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageElement.textContent = data.message;
                    messageElement.style.color = 'green';
                } else {
                    messageElement.textContent = data.message;
                    messageElement.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageElement.textContent = 'Error al cambiar la contraseña.';
                messageElement.style.color = 'red';
            });
    });

</script>
</body>
</html>
