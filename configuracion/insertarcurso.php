<?php
require '../configuracion/db_config.php';

// Array de cursos proporcionados
$cursos = [
    [
        'playlist_id' => 'PLU8oAlHdN5BlvPxziopYZRd55pdqFwkeS',
        'title' => 'Curso Python desde 0',
        'description' => 'Aprende Python desde lo básico de forma rápida.'
    ],
    [
        'playlist_id' => 'PLyvsggKtwbLX9LrDnl1-K6QtYo7m0yXWB',
        'title' => 'Curso Java desde 0',
        'description' => 'Introducción a Java desde lo esencial.'
    ],
    [
        'playlist_id' => 'PLWtYZ2ejMVJmUTNE2QVaCd1y_6GslOeZ6',
        'title' => 'Curso C desde 0',
        'description' => 'Conoce la programación en C desde cero.'
    ],
    [
        'playlist_id' => 'PLWtYZ2ejMVJlUu1rEHLC0i_oibctkl0Vh',
        'title' => 'Curso C++ desde 0',
        'description' => 'Inicia con C++ con explicaciones claras.'
    ],
    [
        'playlist_id' => 'PLH_tVOsiVGzmnl7ImSmhIw5qb9Sy5KJRE',
        'title' => 'Curso PHP desde 0',
        'description' => 'Domina PHP de manera simple y eficaz.'
    ],
    [
        'playlist_id' => 'PLmIB7uA74Vvaub8PVR2Tj3B6_Wq1wr21Y',
        'title' => 'Curso PHP desde 0',
        'description' => 'Aprende a programar en PHP desde lo básico.'
    ],
    [
        'playlist_id' => 'PLLniqWgyb4HHfkyWNgMWSvTq6RlUGusgN',
        'title' => 'Curso Excel desde 0',
        'description' => 'Maneja Excel con herramientas prácticas.'
    ],
    [
        'playlist_id' => 'PLH_tVOsiVGznLd-dSQP9_ttuxLnSmSWwq',
        'title' => 'Curso CSS desde 0',
        'description' => 'Aprende a estilizar páginas web con CSS.'
    ],
    [
        'playlist_id' => 'PLjrXqm46I4pPgzUlnemhxWatJZzG9WFTB',
        'title' => 'Curso DISEÑO gráfico',
        'description' => 'Introducción al diseño gráfico profesional.'
    ],
    [
        'playlist_id' => 'PLFX8Q1hun4izyLOUuCOC8tAu03wgY4cLe',
        'title' => 'Curso Autocad',
        'description' => 'Diseña y modela con herramientas de Autocad.'
    ],
    [
        'playlist_id' => 'PL55BJ0x8OC8KtOe_NUhDv_uEbeItggh14',
        'title' => 'Curso Fusion 360',
        'description' => 'Modelado avanzado en Fusion 360.'
    ],
    [
        'playlist_id' => 'PLVzwufPir355nStjiLrg1WKBNyV-zdLzx',
        'title' => 'Curso Cobol desde 0',
        'description' => 'Aprende Cobol desde sus fundamentos.'
    ],
    [
        'playlist_id' => 'PLSvxAUzJ-XSfY0KpwV8SHBlyLVcrZkENc',
        'title' => 'Curso Redes desde 0',
        'description' => 'Conoce redes desde conceptos básicos.'
    ],
];

try {
    // Iniciar una transacción para asegurar la consistencia
    $pdo->beginTransaction();

    // Prepara la consulta para insertar los cursos
    $stmt = $pdo->prepare("INSERT INTO courses (youtube_playlist_id, title, description) VALUES (?, ?, ?)");

    // Itera sobre cada curso en el arreglo y realiza la inserción
    foreach ($cursos as $curso) {
        $stmt->execute([$curso['playlist_id'], $curso['title'], $curso['description']]);
    }

    // Confirma la transacción
    $pdo->commit();
    echo "Los cursos se han insertado correctamente en la base de datos.";
} catch (PDOException $e) {
    // Deshacer la transacción en caso de error
    $pdo->rollBack();
    echo "Error al insertar los cursos: " . $e->getMessage();
}
?>
