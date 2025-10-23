<?php
session_start();

require '../configuracion/db_config.php';

// Cargar mensajes desde la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $mensajes = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener los mensajes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <link rel="stylesheet" href="../recursos/css/barra_lateral.css">
    <link rel="stylesheet" href="../recursos/css/dashboard_admin.css">
    <style>
        .message-card {
            background-color: #273469;
            color: #FAFAFF;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .message-card h3 {
            margin: 0 0 0.5rem;
        }

        .message-card p {
            margin: 0.5rem 0 1rem;
        }

        .message-card small {
            display: block;
            margin-bottom: 1rem;
            color: #D1D1E9;
        }

        .reply-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .reply-form textarea {
            width: 100%;
            height: 3rem;
            padding: 0.5rem;
            border-radius: 5px;
            border: none;
            font-size: 1rem;
            resize: none;
        }

        .reply-form button {
            align-self: flex-start;
            padding: 0.5rem 2rem;
            background-color: #406ff3;
            color: #FAFAFF;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reply-form button:hover {
            background-color: #324fb0;
        }

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #b02a37;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
<!-- Barra lateral -->
<?php include '../componentes/barra_lateral.php'; ?>

<main class="admin-dashboard">
    <header class="dashboard-header">
        <h1>Mensajes</h1>
        <p>Revisa los mensajes enviados desde la página de contacto y responde directamente a los usuarios.</p>
    </header>

    <!-- Lista de mensajes -->
    <section class="message-list">
        <?php if (!empty($mensajes)): ?>
            <?php foreach ($mensajes as $mensaje): ?>
                <div class="message-card" id="message-<?php echo $mensaje['id']; ?>">
                    <button class="delete-button" data-id="<?php echo $mensaje['id']; ?>">Eliminar</button>
                    <h3><?php echo htmlspecialchars($mensaje['name']); ?> (<?php echo htmlspecialchars($mensaje['email']); ?>)</h3>
                    <p><?php echo htmlspecialchars($mensaje['message']); ?></p>
                    <small>Enviado el: <?php echo htmlspecialchars($mensaje['created_at']); ?></small>

                    <!-- Formulario para responder -->
                    <form class="reply-form" data-id="<?php echo $mensaje['id']; ?>" data-email="<?php echo htmlspecialchars($mensaje['email']); ?>">
                        <textarea name="response_message" placeholder="Escribe tu respuesta aquí..." required></textarea>
                        <button type="submit">Enviar Respuesta</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-messages">
                <h2>No hay mensajes disponibles</h2>
                <p>Actualmente no hay mensajes para mostrar. Vuelve más tarde.</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<!-- Feather Icons -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
    });

    // Enviar respuesta con AJAX
    $(document).on('submit', '.reply-form', function (e) {
        e.preventDefault();
        const form = $(this);
        const messageId = form.data('id');
        const email = form.data('email');
        const responseMessage = form.find('textarea').val();

        $.ajax({
            url: '../acciones/responder_mensaje.php',
            type: 'POST',
            data: { email: email, response_message: responseMessage, message_id: messageId },
            success: function (response) {
                alert('Respuesta enviada correctamente');
                $('#message-' + messageId).remove();
            },
            error: function () {
                alert('Error al enviar la respuesta');
            }
        });
    });

    // Eliminar mensaje con AJAX
    $(document).on('click', '.delete-button', function () {
        const messageId = $(this).data('id');

        $.ajax({
            url: '../acciones/eliminar_mensaje.php',
            type: 'POST',
            data: { message_id: messageId },
            success: function () {
                alert('Mensaje eliminado correctamente');
                $('#message-' + messageId).remove();
            },
            error: function () {
                alert('Error al eliminar el mensaje');
            }
        });
    });
</script>
</body>
</html>
