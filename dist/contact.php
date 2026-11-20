<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

function respond(int $status, bool $ok, string $message): never
{
    http_response_code($status);
    header('Cache-Control: no-store');

    if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    } else {
        header('Content-Type: text/html; charset=UTF-8');
        $title = $ok ? 'Mensaje enviado' : 'No se pudo enviar el mensaje';
        echo '<!doctype html><html lang="es"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
        echo '<title>' . $title . ' | Mis 7 Ángeles</title><body style="font:16px/1.6 Arial,sans-serif;max-width:38rem;margin:12vh auto;padding:0 1.5rem;color:#173c31">';
        echo '<h1>' . $title . '</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<a href="/#contacto">Volver al sitio</a></body></html>';
    }

    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    respond(405, false, 'Método no permitido.');
}

if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 12000) {
    respond(413, false, 'El mensaje es demasiado largo.');
}

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && !in_array(parse_url($origin, PHP_URL_HOST), ['mis7angelesfruits.mx', 'www.mis7angelesfruits.mx'], true)) {
    respond(403, false, 'No se pudo verificar el origen del formulario.');
}

if (trim((string) ($_POST['_honey'] ?? '')) !== '') {
    respond(200, true, 'Mensaje enviado. Te responderemos pronto.');
}

$name = trim((string) ($_POST['Nombre'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$company = trim((string) ($_POST['Empresa'] ?? ''));
$interest = trim((string) ($_POST['Interés'] ?? 'Consulta general'));
$message = trim((string) ($_POST['Mensaje'] ?? ''));

if ($name === '' || strlen($name) > 240 || preg_match('/[\r\n]/', $name)
    || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 160
    || strlen($company) > 240 || preg_match('/[\r\n]/', $company)
    || strlen($interest) > 120 || preg_match('/[\r\n]/', $interest)
    || $message === '' || strlen($message) > 12000) {
    respond(422, false, 'Revisa los datos y vuelve a intentarlo.');
}

$body = "Nueva consulta desde mis7angelesfruits.mx\n\n"
    . "Nombre: {$name}\n"
    . "Correo: {$email}\n"
    . "Empresa: " . ($company !== '' ? $company : 'No indicada') . "\n"
    . "Interés: {$interest}\n\n"
    . "Mensaje:\n{$message}\n";

$configFile = dirname(__DIR__) . '/contact.smtp.txt';
$autoloadFile = dirname(__DIR__) . '/vendor/autoload.php';
if (!is_readable($configFile) || !is_file($autoloadFile)) {
    error_log('El formulario de mis7angelesfruits.mx no tiene SMTP configurado.');
    respond(503, false, 'El formulario no está disponible por el momento. Inténtalo de nuevo más tarde.');
}

$passwordFile = file_get_contents($configFile);
$appPassword = is_string($passwordFile) ? preg_replace('/\s+/', '', $passwordFile) : null;
if (!is_string($appPassword) || !preg_match('/^[a-z0-9]{16}$/i', $appPassword)) {
    error_log('La configuración SMTP de mis7angelesfruits.mx está incompleta.');
    respond(503, false, 'El formulario no está disponible por el momento. Inténtalo de nuevo más tarde.');
}

require $autoloadFile;

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    $mail->Username = 'bryanp.financialgroup@gmail.com';
    $mail->Password = $appPassword;
    $mail->Timeout = 15;
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    $mail->setFrom('bryanp.financialgroup@gmail.com', 'Mis 7 Ángeles');
    $mail->addAddress('bryanedilberto@hotmail.com');
    $mail->addReplyTo($email, $name);
    $mail->Subject = 'Nueva consulta desde Mis 7 Ángeles';
    $mail->Body = $body;
    $mail->send();
} catch (MailException $error) {
    error_log('Falló el envío SMTP de mis7angelesfruits.mx: ' . $error->getMessage());
    respond(502, false, 'No se pudo enviar el mensaje. Inténtalo de nuevo más tarde.');
}

respond(200, true, 'Mensaje enviado. Te responderemos pronto.');
