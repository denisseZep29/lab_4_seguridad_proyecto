<?php
require '../configuracion/db_config.php';

// Cantidad de usuarios a crear
$usuariosAInsertar = 50; // Cambia según tus necesidades

// Cursos disponibles
$cursosQuery = $pdo->query("SELECT id FROM courses");
$cursos = $cursosQuery->fetchAll(PDO::FETCH_COLUMN);

if (empty($cursos)) {
    die("No hay cursos disponibles en la base de datos. Por favor, agrega cursos primero.");
}

try {
    for ($i = 1; $i <= $usuariosAInsertar; $i++) {
        // Generar un nombre y correo únicos
        $nombre = "Usuario" . $i;
        $email = "usuario$i@example.com";
        $password = password_hash("password$i", PASSWORD_DEFAULT); // Hashear la contraseña

        // Fecha aleatoria entre enero y el mes actual
        $mes = rand(1, date('m'));
        $dia = rand(1, 28); // Limitar a 28 días para evitar problemas en febrero
        $anio = date('Y');
        $fechaCreacion = "$anio-$mes-$dia";

        // Insertar usuario
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'user', ?)");
        $stmt->execute([$nombre, $email, $password, $fechaCreacion]);

        // Obtener el ID del usuario recién creado
        $usuarioId = $pdo->lastInsertId();

        // Asignar cursos aleatorios al usuario
        $cursosAsignados = array_rand(array_flip($cursos), rand(1, 5)); // Entre 1 y 5 cursos
        if (!is_array($cursosAsignados)) {
            $cursosAsignados = [$cursosAsignados]; // Asegurarse de que sea un array
        }

        foreach ($cursosAsignados as $cursoId) {
            // Definir si el curso está completado
            $completado = rand(0, 1); // 0 = En progreso, 1 = Completado

            // Insertar en la tabla de inscripciones
            $stmt = $pdo->prepare("INSERT INTO user_courses (user_id, course_id, completed, enrollment_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$usuarioId, $cursoId, $completado, $fechaCreacion]);
        }
    }

    echo "Usuarios y cursos asignados creados exitosamente.";
} catch (PDOException $e) {
    echo "Error al insertar usuarios y cursos: " . $e->getMessage();
}
?>
