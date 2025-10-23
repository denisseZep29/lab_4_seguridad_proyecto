
    <?php
    require '../vendor/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['response_message'], $_POST['message_id'])) {
        $email = $_POST['email'];
        $response_message = $_POST['response_message'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kkekekelekeke@gmail.com'; // Cambia esto a tu correo
            $mail->Password = 'kwpc klux nnwf enxb'; // Cambia esto a tu contraseña de aplicación
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('kkekekelekeke@gmail.com', 'Administrador');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Respuesta a tu mensaje';
            $mail->Body = nl2br($response_message);

            $mail->send();
            http_response_code(200); // Respuesta exitosa
            echo "Respuesta enviada correctamente.";
        } catch (Exception $e) {
            echo "Error al enviar el mensaje: " . $mail->ErrorInfo;
        }
    } else {
        http_response_code(400); // Solicitud incorrecta
        echo "Faltan parámetros.";
    }
