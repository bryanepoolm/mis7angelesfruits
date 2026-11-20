<?php
declare(strict_types=1);

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

$headers = [
    'From' => 'Mis 7 Angeles <no-reply@mis7angelesfruits.mx>',
    'Reply-To' => $email,
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8',
];

$subject = '=?UTF-8?B?' . base64_encode('Nueva consulta desde Mis 7 Ángeles') . '?=';
$sent = mail('bryanedilberto@hotmail.com', $subject, $body, $headers, '-fno-reply@mis7angelesfruits.mx');

if (!$sent) {
    error_log('No se pudo enviar una consulta desde mis7angelesfruits.mx');
    respond(502, false, 'No se pudo enviar el mensaje. Escríbenos por correo e inténtalo de nuevo más tarde.');
}

respond(200, true, 'Mensaje enviado. Te responderemos pronto.');
