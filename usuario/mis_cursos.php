<?php
require '../configuracion/db_config.php';
require '../configuracion/youtube_api.php'; // API Key para acceder a YouTube
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../publico/inicio.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los cursos en los que el usuario está inscrito
try {
    $stmt = $pdo->prepare("
        SELECT courses.id, courses.title, courses.description, courses.youtube_playlist_id, user_courses.completed 
        FROM user_courses 
        INNER JOIN courses ON user_courses.course_id = courses.id 
        WHERE user_courses.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cursosInscritos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<p>Error al cargar los cursos inscritos.</p>";
    $cursosInscritos = [];
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
    <title>Mis Cursos</title>
    <link rel="stylesheet" href="../recursos/css/mis_cursos.css">
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <!-- Barra lateral -->
    <?php include '../componentes/barra_lateral.php'; ?>

    <main class="curso-dashboard">
        <div class="curso-scrollable">
            <header class="curso-header">
                <h1>Mis Cursos</h1>
                <p>Accede a tus cursos inscritos, revisa tu progreso y marca como completados.</p>
            </header>

            <section class="cursos-container">
                <?php if (count($cursosInscritos) > 0): ?>
                <?php foreach ($cursosInscritos as $curso): ?>
                <div class="curso-card">
                    <div class="curso-thumbnail">
                        <img src="<?php echo obtenerMiniaturaYoutube($curso['youtube_playlist_id'], $apiKey); ?>"
                            alt="Miniatura de <?php echo htmlspecialchars($curso['title']); ?>">
                    </div>
                    <div class="curso-content">
                        <h2><?php echo htmlspecialchars($curso['title']); ?></h2>
                        <p><?php echo htmlspecialchars($curso['description']); ?></p>
                        <p><strong>Estado:</strong> <?php echo $curso['completed'] ? 'Completado' : 'En progreso'; ?>
                        </p>
                        <div class="curso-buttons">
                            <a href="https://www.youtube.com/playlist?list=<?php echo htmlspecialchars($curso['youtube_playlist_id']); ?>"
                                target="_blank" class="btn-ver-curso">Ir al curso</a>
                            <button class="btn-completar <?php echo $curso['completed'] ? 'completado' : ''; ?>"
                                data-course-id="<?php echo htmlspecialchars($curso['id']); ?>"
                                <?php echo $curso['completed'] ? 'disabled' : ''; ?>>
                                <?php echo $curso['completed'] ? 'Completado' : 'Completar'; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p class="no-cursos">No estás inscrito en ningún curso.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>


    <script>
    feather.replace(); // Reemplaza los íconos con Feather Icons

    // Marcar curso como completado
    document.addEventListener('DOMContentLoaded', () => {
        const botonesCompletar = document.querySelectorAll('.btn-completar');

        botonesCompletar.forEach(boton => {
            boton.addEventListener('click', () => {
                const courseId = boton.getAttribute('data-course-id');

                // Enviar solicitud al servidor
                fetch('../acciones/completar_curso.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            course_id: courseId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            boton.textContent = 'Completado';
                            boton.disabled = true;
                            location
                                .reload(); // Recargar la página para actualizar el estado
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>