<?php
session_start();

// Asegurar que la página no se cachee
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Si el usuario no tiene sesión activa, redirigir al inicio de sesión
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../publico/inicio.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <link rel="stylesheet" href="../recursos/css/dashboard_admin.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
<!-- Barra lateral -->
<?php include '../componentes/barra_lateral.php'; ?>

<main class="admin-dashboard">
    <!-- Encabezado -->
    <header class="dashboard-header">
        <h1>Panel de Administración</h1>
        <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>. Aquí puedes gestionar toda la plataforma.</p>
    </header>

    <!-- Resumen -->
    <section class="dashboard-overview">
        <div class="card">
            <h2>Usuarios</h2>
            <p>Total registrados: <strong>120</strong></p>
            <a href="../administrador/gestionar_usuarios.php" class="btn-link">Gestionar Usuarios</a>
        </div>
        <div class="card">
            <h2>Cursos</h2>
            <p>Total cursos: <strong>45</strong></p>
            <a href="../administrador/gestionar_cursos.php" class="btn-link">Gestionar Cursos</a>
        </div>
    </section>

    <!-- Gráficos -->
    <section class="dashboard-stats">
        <div class="chart-container">
            <h3>Estadísticas de Usuarios</h3>
            <canvas id="userChart"></canvas>
        </div>
        <div class="chart-container">
            <h3>Estadísticas de Cursos</h3>
            <canvas id="courseChart"></canvas>
        </div>
    </section>

</main>

<!-- Scripts -->
<script>
    feather.replace(); // Reemplaza los íconos con Feather Icons
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../recursos/js/dashboard_admin.js"></script>
</body>
</html>