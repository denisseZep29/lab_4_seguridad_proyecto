<?php
require '../configuracion/db_config.php';
require '../configuracion/youtube_api.php';
session_start();

// Verificar si el usuario está intentando inscribirse a un curso específico
$course_id_to_inscribe = isset($_GET['course_id']) ? intval($_GET['course_id']) : null;

// Obtener cursos desde la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
    $cursos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<p>Error al cargar los cursos.</p>";
    $cursos = [];
}

// Función para obtener miniaturas de YouTube
function obtenerMiniaturaYoutube($playlistId, $apiKey) {
    $apiUrl = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=1&playlistId=$playlistId&key=$apiKey";
    try {
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);
        if (!empty($data['items'][0]['snippet']['thumbnails']['high']['url'])) {
            return $data['items'][0]['snippet']['thumbnails']['high']['url'];
        }
    } catch (Exception $e) {
        return "../recursos/img/default-thumbnail.png"; // Imagen por defecto si falla
    }
    return "../recursos/img/default-thumbnail.png";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explora Cursos</title>
    <link rel="stylesheet" href="../recursos/css/estilos.css">
    <link rel="stylesheet" href="../recursos/css/headerinicio.css">
    <link rel="stylesheet" href="../recursos/css/cursos.css">
</head>

<body>
    <!-- Encabezado -->
    <header class="header">
        <nav>
            <ul class="nav-links">
                <li><a class="nav-link" href="../publico/inicio.html">Inicio</a></li>
                <li><a class="nav-link" href="cursos.php">Cursos</a></li>
                <li><a class="nav-link" href="../publico/contacto.html">Contacto</a></li>
                <li><a class="nav-link" href="../publico/nosotros.html">Nosotros</a></li>
            </ul>
        </nav>
        <div class="logo">
            <img src="../recursos/img/Logo.png" alt="Tutorías Online Logo">
        </div>
        <nav>
            <ul class="np-links">
                <?php if (!isset($_SESSION['user_id'])): ?>
                <li><a class="nav-link" href="../publico/LogInSignUp.html?action=signup" id="SignUp">Sign Up</a></li>
                <li><a class="nav-link" href="../publico/LogInSignUp.html?action=login" id="LogIn">Log In</a></li>
                <?php else: ?>
                <nav>
                    <ul class="user-links">
                        <li><a id="MiPerfil" href="../usuario/perfil.php">Mi Perfil</a></li>
                        <li><a id="CerrarSesion" href="../acciones/logout.php">Salir</a></li>
                    </ul>
                </nav>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Sección de Cursos -->
    <section class="courses-dashboard">
        <header class="dashboard-header">
            <h1>Explora Cursos</h1>
            <p>Elige un curso y empieza a aprender hoy mismo.</p>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Buscar curso por título...">
            </div>
        </header>

        <section class="courses-container">
            <?php foreach ($cursos as $curso): ?>
            <div class="course-card">
                <div class="course-thumbnail">
                    <img src="<?php echo obtenerMiniaturaYoutube($curso['youtube_playlist_id'], $apiKey); ?>"
                        alt="Miniatura de <?php echo htmlspecialchars($curso['title']); ?>">
                </div>
                <div class="course-info">
                    <h2><?php echo htmlspecialchars($curso['title']); ?></h2>
                    <p><?php echo htmlspecialchars($curso['description']); ?></p>
                </div>
                <div class="course-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="../acciones/inscribirse.php" method="POST">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($curso['id']); ?>">
                        <button type="submit" class="btn-inscribirse">Inscribirse</button>
                    </form>
                    <?php else: ?>
                    <a href="../publico/LogInSignUp.html?course_id=<?php echo htmlspecialchars($curso['id']); ?>"
                        class="btn-inscribirse">Inicia sesión para inscribirte</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </section>

    <!-- Pie de página -->
    <footer class="footer">
        <div class="company-info">
            <div class="company-logo">
                <img src="../recursos/img/Logo.png" alt="Company Logo" width="44" height="44">
            </div>
            <p>&copy; 2024 Tutorías Online, Inc.</p>
        </div>
        <div class="social-icons">
            <a href="#" aria-label="Twitter">
                <img src="../recursos/img/twitter-alt.png" alt="Twitter Icon" width="18" height="18">
            </a>
            <a href="#" aria-label="Instagram">
                <img src="../recursos/img/instagram.png" alt="Instagram Icon" width="18" height="18">
            </a>
            <a href="#" aria-label="Facebook">
                <img src="../recursos/img/facebook.png" alt="Facebook Icon" width="18" height="18">
            </a>
        </div>
    </footer>

    <script>
    // Filtrar cursos por título
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        const courses = document.querySelectorAll('.course-card');

        courses.forEach(course => {
            const title = course.querySelector('h2').textContent.toLowerCase();
            if (title.includes(searchText)) {
                course.style.display = 'block';
            } else {
                course.style.display = 'none';
            }
        });
    });
    </script>
</body>

</html>