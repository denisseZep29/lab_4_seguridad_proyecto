<?php
if (!isset($_SESSION)) {
    session_start();
}

$role = $_SESSION['role'] ?? null;

// Define los elementos del menú según el rol
$menu_items = $role === 'admin'
    ? [
        ['href' => '../administrador/inicio_admins.php', 'icon' => 'home', 'label' => 'Dashboard'],
        ['href' => '../administrador/gestionar_usuarios.php', 'icon' => 'users', 'label' => 'Usuarios'],
        ['href' => '../administrador/gestionar_cursos.php', 'icon' => 'folder', 'label' => 'Cursos'],
        ['href' => '../administrador/mensajes.php', 'icon' => 'message-square', 'label' => 'Mensajes'],
        ['href' => '../administrador/ayuda.php', 'icon' => 'help-circle', 'label' => 'Ayuda'],
        ['href' => '../acciones/logout.php', 'icon' => 'log-out', 'label' => 'Cerrar Sesión'],
    ]
    : [
        ['href' => '../usuario/inicio_usuarios.php', 'icon' => 'home', 'label' => 'Dashboard'],
        ['href' => '../usuario/ver_cursos.php', 'icon' => 'folder', 'label' => 'Ver Cursos'],
        ['href' => '../usuario/mis_cursos.php', 'icon' => 'book', 'label' => 'Mis Cursos'],
        ['href' => '../usuario/perfil.php', 'icon' => 'user', 'label' => 'Perfil'],
        ['href' => '../usuario/ayuda.php', 'icon' => 'help-circle', 'label' => 'Ayuda'],
        ['href' => '../acciones/logout.php', 'icon' => 'log-out', 'label' => 'Cerrar Sesión'],
    ];

?>

<nav class="navbar">
    <ul class="navbar__menu">
        <?php foreach ($menu_items as $item): ?>
            <li class="navbar__item">
                <a href="<?php echo $item['href']; ?>" class="navbar__link">
                    <i data-feather="<?php echo $item['icon']; ?>"></i>
                    <span><?php echo $item['label']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
