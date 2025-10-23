<?php
session_start();
require '../configuracion/db_config.php';

// Obtener los cursos desde la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
    $cursos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener los cursos: " . $e->getMessage();
    $cursos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos</title>
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <link rel="stylesheet" href="../recursos/css/gestionar_cursos.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
<?php include '../componentes/barra_lateral.php'; ?>

<main class="admin-dashboard">
    <header class="dashboard-header">
        <h1>Gestión de Cursos</h1>
        <p>Administra los cursos disponibles en la plataforma.</p>
    </header>

    <section class="course-management">

        <!-- Lista de Cursos -->
        <div class="course-list-container">
            <h2>Lista de Cursos</h2>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Buscar curso por título..." onkeyup="filterCourses()">
            </div>

            <table class="course-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($cursos)): ?>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($curso['id']); ?></td>
                            <td><?php echo htmlspecialchars($curso['title']); ?></td>
                            <td><?php echo htmlspecialchars($curso['description']); ?></td>
                            <td>
                                <!-- Botón Editar -->
                                <button class="edit-btn"
                                        onclick="openEditModal(
                                                '<?php echo $curso['id']; ?>',
                                                '<?php echo htmlspecialchars($curso['title']); ?>',
                                                '<?php echo htmlspecialchars($curso['description']); ?>')">
                                    Editar
                                </button>
                                <!-- Botón Eliminar -->
                                <button class="delete-btn"
                                        onclick="openDeleteModal('<?php echo $curso['id']; ?>')">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay cursos disponibles.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Formulario para agregar curso -->
        <div class="add-course-form-container">
            <h2>Agregar Curso</h2>
            <form id="addCourseForm" method="POST" action="../acciones/gestionar_cursos.php">
                <input type="text" name="playlist_id" placeholder="ID de Playlist de YouTube" required>
                <input type="text" name="title" placeholder="Título del Curso" required>
                <textarea name="description" placeholder="Descripción del Curso"></textarea>
                <input type="hidden" name="action" value="add">
                <button type="submit">Agregar Curso</button>
            </form>
        </div>
    </section>
</main>

<!-- Modal para editar curso -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
        <h3>Editar Curso</h3>
        <form id="editCourseForm" method="POST" action="../acciones/gestionar_cursos.php">
            <input type="hidden" name="id" id="editCourseId">
            <input type="text" name="title" id="editCourseTitle" placeholder="Título del Curso" required>
            <textarea name="description" id="editCourseDescription" placeholder="Descripción del Curso"></textarea>
            <input type="hidden" name="action" value="edit">
            <div class="modal-buttons">
                <button type="submit" class="btn-confirm">Guardar Cambios</button>
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para confirmar eliminación -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('deleteModal')">&times;</span>
        <h3>¿Estás seguro de eliminar este curso?</h3>
        <form id="deleteCourseForm" method="POST" action="../acciones/gestionar_cursos.php">
            <input type="hidden" name="id" id="deleteCourseId">
            <input type="hidden" name="action" value="delete">
            <div class="modal-buttons">
                <button type="submit" class="btn-confirm">Eliminar</button>
                <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
    });
    function filterCourses() {
        // Obtener el valor ingresado en la barra de búsqueda
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();

        // Obtener todas las filas de la tabla, excepto el encabezado
        const rows = document.querySelectorAll('.course-table tbody tr');

        // Iterar sobre cada fila
        rows.forEach(row => {
            const titleCell = row.cells[1]; // La columna del título es la segunda (índice 1)
            const titleText = titleCell.textContent || titleCell.innerText;

            // Mostrar/ocultar fila según si coincide con el filtro
            if (titleText.toLowerCase().includes(filter)) {
                row.style.display = ''; // Mostrar fila si coincide
            } else {
                row.style.display = 'none'; // Ocultar fila si no coincide
            }
        });
    }

    // Abrir modal de editar curso
    function openEditModal(id, title, description) {
        document.getElementById('editCourseId').value = id;
        document.getElementById('editCourseTitle').value = title;
        document.getElementById('editCourseDescription').value = description;
        document.getElementById('editModal').style.display = 'flex';
    }

    // Abrir modal de eliminar curso
    function openDeleteModal(id) {
        document.getElementById('deleteCourseId').value = id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    // Cerrar modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>

</body>
</html>
