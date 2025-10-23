<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda</title>
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<style>
    body {
        background: #1E2749;
        font-family: 'Open Sans', sans-serif;
        color: #FAFAFF;
        margin: 0;
    }

    .admin-dashboard {
        margin-left: 80px; /* Espacio para la barra lateral */
        padding: 1.5rem;
    }

    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        text-align: center;
        font-size: 2rem;
        color: #E4D9FF;
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        text-align: center;
        color: #D1D1E9;
    }

    .help-section {
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 320px); /* Altura restante después del header */
    }

    .maintenance-message {
        text-align: center;
        background: #273469;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .maintenance-message h2 {
        color: #E4D9FF;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }

    .maintenance-message p {
        color: #D1D1E9;
        font-size: 1.2rem;
    }

</style>
<body>
<!-- Barra lateral -->
<?php include '../componentes/barra_lateral.php'; ?>

<main class="admin-dashboard">
    <header class="dashboard-header">
        <h1>Ayuda</h1>
        <p>Obtén información y soporte sobre el sistema.</p>
    </header>

    <section class="help-section">
        <div class="maintenance-message">
            <h2>Estamos trabajando en ello 🚧</h2>
            <p>Esta sección está actualmente en mantenimiento. Por favor, vuelve más tarde para acceder al contenido de ayuda.</p>
        </div>
    </section>
</main>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
    });
</script>
</body>
</html>
