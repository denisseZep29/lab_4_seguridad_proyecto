<?php
require '../configuracion/db_config.php';
require '../configuracion/youtube_api.php'; // API Key para acceder a YouTube

// Función para obtener miniaturas de YouTube
function obtenerMiniaturaYoutube($playlistId, $apiKey)
{
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

// Obtener cursos desde la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
    $cursos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<p>Error al cargar los cursos.</p>";
    $cursos = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explora Cursos</title>
    <link rel="stylesheet" href="../recursos/css/ver_cursos.css">
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <!-- Barra lateral -->
    <?php include '../componentes/barra_lateral.php'; ?>

    <main class="curso-dashboard">
        <div class="curso-scrollable">
            <header class="curso-header">
                <h1>Explora Cursos</h1>
                <p>Elige un curso y empieza a aprender hoy mismo.</p>
                <input type="text" id="searchInput" placeholder="Buscar curso por título...">
            </header>

            <section class="cursos-container">
                <?php foreach ($cursos as $curso): ?>
                <div class="curso-card">
                    <div class="curso-thumbnail">
                        <img src="<?php echo obtenerMiniaturaYoutube($curso['youtube_playlist_id'], $apiKey); ?>"
                            alt="Miniatura de <?php echo htmlspecialchars($curso['title']); ?>">
                    </div>
                    <div class="curso-content">
                        <h2><?php echo htmlspecialchars($curso['title']); ?></h2>
                        <p><?php echo htmlspecialchars($curso['description']); ?></p>
                        <button class="btn-inscribirse" data-course-id="<?php echo htmlspecialchars($curso['id']); ?>">
                            Inscribirse
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>


    <script>
    // Filtrar cursos por título
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        const cursos = document.querySelectorAll('.curso-card');

        cursos.forEach(curso => {
            const title = curso.querySelector('h2').textContent.toLowerCase();
            if (title.includes(searchText)) {
                curso.style.display = 'block';
            } else {
                curso.style.display = 'none';
            }
        });
    });

    // Inscripción en curso
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.btn-inscribirse');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const courseId = button.getAttribute('data-course-id');

                // Enviar solicitud al servidor
                fetch('../acciones/inscribirse.php', {
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
                            button.textContent = 'Inscrito';
                            button.disabled = true;
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

    feather.replace(); // Reemplaza los íconos con Feather Icons
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>