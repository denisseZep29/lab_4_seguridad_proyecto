<?php
session_start();

// Conexión a la base de datos
require '../configuracion/db_config.php';

try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener usuarios: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <link rel="stylesheet" href="../recursos/css/gestionar_usuarios.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
<!-- Barra lateral -->
<?php include '../componentes/barra_lateral.php'; ?>

<main class="admin-dashboard">
    <header class="dashboard-header">
        <h1>Gestión de Usuarios</h1>
        <p>Administra los usuarios registrados en la plataforma.</p>
    </header>

    <section class="user-management">
        <!-- Tabla de usuarios -->
        <div class="user-table-container">
            <h2>Lista de Usuarios</h2>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Buscar por correo electrónico..." onkeyup="filterUsers()">
            </div>
            <div class="user-table-wrapper">
                <table id="userTable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr id="user-<?php echo htmlspecialchars($usuario['id']); ?>">
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['name']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['role']); ?></td>
                            <td>
                                <!-- Botón Editar -->
                                <button class="edit-btn"
                                        onclick="openEditModal(
                                        <?php echo htmlspecialchars($usuario['id']); ?>,
                                                '<?php echo htmlspecialchars($usuario['name']); ?>',
                                                '<?php echo htmlspecialchars($usuario['email']); ?>',
                                                '<?php echo htmlspecialchars($usuario['role']); ?>')">
                                    Editar
                                </button>
                                <!-- Botón Eliminar -->
                                <button class="delete-btn"
                                        onclick="openDeleteModal(
                                        <?php echo htmlspecialchars($usuario['id']); ?>)">
                                    Eliminar
                                </button>
                                <!-- Botón Ver Cursos -->
                                <button class="view-courses-btn"
                                        onclick="openCoursesModal(<?php echo htmlspecialchars($usuario['id']); ?>, '<?php echo htmlspecialchars($usuario['name']); ?>')">
                                    Ver Cursos
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Formulario de agregar usuario -->
        <div class="add-user-form-container">
            <h2>Agregar Usuario</h2>
            <form id="addUserForm">
                <input type="text" name="name" placeholder="Nombre" required>
                <input type="email" name="email" placeholder="Correo Electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <select name="role" required>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
                <button type="submit">AGREGAR USUARIO</button>
            </form>
        </div>
    </section>

    <!-- Modal para editar usuario -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
            <h3>Editar Usuario</h3>
            <form id="editUserForm" method="POST" action="../acciones/gestionar_usuarios.php">
                <input type="hidden" name="id" id="editUserId">
                <input type="text" name="name" id="editUserName" placeholder="Nombre" required>
                <input type="email" name="email" id="editUserEmail" placeholder="Correo Electrónico" required>
                <select name="role" id="editUserRole" required>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
                <div class="modal-buttons">
                    <button type="submit" name="action" value="edit" class="btn-confirm">Guardar Cambios</button>
                    <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal para confirmar eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal('deleteModal')">&times;</span>
            <h3>¿ESTÁS SEGURO DE ELIMINAR ESTE USUARIO?</h3>
            <form id="deleteUserForm" method="POST" action="../acciones/gestionar_usuarios.php">
                <input type="hidden" name="id" id="deleteUserId">
                <div class="modal-buttons">
                    <button type="submit" name="action" value="delete" class="btn-confirm">Eliminar</button>
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para ver cursos inscritos -->
    <div id="coursesModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal('coursesModal')">&times;</span>
            <h3>Cursos Inscritos</h3>
            <p id="userName"></p>
            <div id="coursesList" style="margin-top: 1rem;">
                <!-- Aquí se cargarán dinámicamente los cursos -->
            </div>
        </div>
    </div>

</main>



<script src="https://unpkg.com/feather-icons"></script>
<script>
    // Función para abrir el modal de cursos inscritos
    function openCoursesModal(userId, userName) {
        // Mostrar el nombre del usuario en el modal
        document.getElementById('userName').textContent = `Cursos inscritos de: ${userName}`;

        // Limpiar la lista de cursos previa
        const coursesList = document.getElementById('coursesList');
        coursesList.innerHTML = 'Cargando...';

        // Hacer una petición AJAX para obtener los cursos inscritos
        fetch(`../acciones/get_user_courses.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.courses.length > 0) {
                    // Crear la lista de cursos
                    // Crear la lista de cursos con el nuevo diseño
                    const ul = document.createElement('div');
                    ul.style.display = 'flex';
                    ul.style.flexDirection = 'column';
                    ul.style.gap = '15px';

                    data.courses.forEach(course => {
                        const courseItem = document.createElement('div');
                        courseItem.classList.add('course-item');
                        courseItem.innerHTML = `
        <h4>${course.title}</h4>
        <p>${course.description}</p>
    `;
                        ul.appendChild(courseItem);
                    });

                    coursesList.innerHTML = '';
                    coursesList.appendChild(ul);

                } else {
                    coursesList.innerHTML = '<p>Este usuario no está inscrito en ningún curso.</p>';
                }
            })
            .catch(error => {
                console.error('Error al cargar los cursos:', error);
                coursesList.innerHTML = '<p>Error al cargar los cursos.</p>';
            });

        // Mostrar el modal
        document.getElementById('coursesModal').style.display = 'flex';
    }

    // Función para cerrar cualquier modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }


    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
    });

    // Función para abrir el modal de editar
    function openEditModal(id, name, email, role) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUserName').value = name;
        document.getElementById('editUserEmail').value = email;
        document.getElementById('editUserRole').value = role;
        document.getElementById('editModal').style.display = 'flex';
    }


    // Función para abrir el modal de eliminar
    function openDeleteModal(id) {
        document.getElementById('deleteUserId').value = id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    // Función para cerrar cualquier modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function filterUsers() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#userTable tbody tr');

        rows.forEach(row => {
            const email = row.cells[2].textContent.toLowerCase(); // El índice 2 corresponde al correo en la tabla
            if (email.includes(filter)) {
                row.style.display = ''; // Muestra la fila si coincide
            } else {
                row.style.display = 'none'; // Oculta la fila si no coincide
            }
        });
    }
</script>

</body>
</html>
