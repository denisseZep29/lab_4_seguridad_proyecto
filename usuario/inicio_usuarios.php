<?php
session_start();
require '../configuracion/db_config.php';

// Verifica si el usuario tiene sesión activa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../publico/inicio.html");
    exit();
}

// Obtén el ID del usuario
$user_id = $_SESSION['user_id'];

// Obtiene estadísticas: Cursos inscritos y completados
try {
    // Cursos inscritos
    $stmt_inscritos = $pdo->prepare("SELECT COUNT(*) as total FROM user_courses WHERE user_id = ?");
    $stmt_inscritos->execute([$user_id]);
    $cursos_inscritos = $stmt_inscritos->fetchColumn();

    // Cursos completados
    $stmt_completados = $pdo->prepare("SELECT COUNT(*) as total FROM user_courses WHERE user_id = ? AND completed = 1");
    $stmt_completados->execute([$user_id]);
    $cursos_completados = $stmt_completados->fetchColumn();
} catch (PDOException $e) {
    die("Error al obtener estadísticas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Usuario</title>
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <link rel="stylesheet" href="../recursos/css/inicio_usuarios.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<!-- Barra lateral -->
<?php include '../componentes/barra_lateral.php'; ?>

<main class="user-dashboard">
    <!-- Encabezado -->
    <header class="dashboard-header">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p>Accede a tus cursos, revisa tus progresos y explora nuevas oportunidades de aprendizaje.</p>
    </header>

    <!-- Resumen -->
    <section class="dashboard-overview">
        <div class="card">
            <h2>Cursos Inscritos</h2>
            <p>Total: <strong><?php echo $cursos_inscritos; ?></strong></p>
        </div>
        <div class="card">
            <h2>Cursos Completados</h2>
            <p>Total: <strong><?php echo $cursos_completados; ?></strong></p>
        </div>
    </section>

    <!-- Gráficos -->
    <section class="dashboard-stats">
        <div class="chart-container">
            <h3>Progreso de Cursos</h3>
            <canvas id="progressChart"></canvas>
        </div>
    </section>
</main>

<script>
    feather.replace(); // Reemplaza los íconos con Feather Icons

    // Datos para el gráfico de progreso
    const data = {
        labels: ['Cursos Inscritos', 'Cursos Completados'],
        datasets: [{
            data: [<?php echo $cursos_inscritos; ?>, <?php echo $cursos_completados; ?>],
            backgroundColor: ['#406ff3', '#324fb0'],
            hoverBackgroundColor: ['#324fb0', '#263b7a'],
        }]
    };

    // Configuración del gráfico
    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
            },
        },
    };

    // Renderiza el gráfico
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, config);
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
