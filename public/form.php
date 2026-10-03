<?php
declare(strict_types=1);

// Vereist hosting met PHP en een correct geconfigureerde mailserver.
// Publiceer deze handler in de webroot naast de statische Astro-build.
ini_set('display_errors', '0');
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');
session_start();

$domain = 'wijkraadkoningshaven.nl';
$forms = [
    'contact' => [
        'from' => "contact@$domain",
        'to' => "info@$domain",
        'required' => ['name', 'email', 'message'],
        'labels' => ['name' => 'Naam', 'phone' => 'Telefoon', 'email' => 'E-mail', 'message' => 'Bericht'],
        'subject' => 'Contactformulier wijkraad',
        'cooldown' => 120,
    ],
    'verrijkjewijk' => [
        'from' => "verrijkjewijk@$domain",
        'to' => "vjw@$domain",
        'required' => ['naam', 'telefoon', 'street', 'datum', 'lokatie', 'doelgroep', 'deelnemers', 'bedrag', 'bijdragen', 'terms', 'email'],
        'labels' => [
            'naam' => 'Naam', 'telefoon' => 'Telefoon', 'street' => 'Adres',
            'postcode' => 'Postcode', 'woonplaats' => 'Woonplaats', 'email' => 'E-mail',
            'aanvrager' => 'Aanvrager', 'event_description' => 'Omschrijving',
            'datum' => 'Datum', 'lokatie' => 'Locatie', 'doelgroep' => 'Doelgroep',
            'deelnemers' => 'Deelnemers', 'bedrag' => 'Bedrag',
            'bijdragen' => 'Bijdragen', 'bijdragen_toegezegd' => 'Toegezegde bijdragen',
            'berekening' => 'Begroting', 'terms' => 'Voorwaarden geaccepteerd',
        ],
        'subject' => 'Aanvraag Verrijk je wijk',
        'cooldown' => 300,
    ],
];

$isHtmx = ($_SERVER['HTTP_HX_REQUEST'] ?? '') === 'true';

function feedback(int $status, string $message, bool $htmx, string $redirect = '/'): never
{
    http_response_code($status);
    $kind = $status < 400 ? 'success' : 'error';
    $safe = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $html = '<div class="form-feedback__message form-feedback__message--' . $kind . '" role="alert">' . $safe . '</div>';

    if ($htmx) {
        // HTMX wisselt 4xx/5xx standaard niet om; laat formulierfouten
        // als succesvolle HTTP-respons zien, met een foutmelding in de UI.
        http_response_code(200);
        echo $html;
        exit;
    }

    // Zonder JS: toon altijd een bruikbare HTML-respons.
    echo '<!doctype html><html lang="nl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Formulierstatus</title></head><body><main><h1>Formulierstatus</h1>';
    echo $html;
    echo '<p><a href="' . htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') . '">Terug naar het formulier</a></p></main></body></html>';
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    feedback(405, 'Deze pagina accepteert alleen formulierinzendingen.', $isHtmx);
}

// Basiscontrole op de herkomst van de POST. Geen vervanging voor serverconfiguratie.
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
    $host = parse_url($origin, PHP_URL_HOST);
    if (!in_array($host, [$domain, "www.$domain", 'localhost'], true)) {
        feedback(403, 'Ongeldige formulierherkomst.', $isHtmx);
    }
}

$type = $_POST['_form'] ?? '';
if (!is_string($type) || !isset($forms[$type])) {
    feedback(400, 'Ongeldig formulier.', $isHtmx);
}

$config = $forms[$type];
$redirect = $type === 'contact' ? '/contact/' : '/verrijk-je-wijk/';

if (!empty($_POST['honeypot_field'])) {
    // Geef geen aanwijzingen over de spamcontrole.
    feedback(200, 'Bedankt. Je bericht is ontvangen.', $isHtmx, $redirect);
}

$lastSent = (int) ($_SESSION['last_sent_' . $type] ?? 0);
if (time() - $lastSent < $config['cooldown']) {
    feedback(429, 'Je hebt onlangs al een bericht verzonden. Probeer het later opnieuw.', $isHtmx, $redirect);
}

$fields = [];
foreach ($config['labels'] as $key => $label) {
    $value = $_POST[$key] ?? '';
    if (!is_string($value)) {
        feedback(400, 'Een formulierwaarde is ongeldig.', $isHtmx, $redirect);
    }
    $value = trim($value);
    if (strlen($value) > 20000) {
        feedback(400, 'Een ingevuld veld is te lang.', $isHtmx, $redirect);
    }
    $fields[$key] = $value;
}

foreach ($config['required'] as $key) {
    if (($fields[$key] ?? '') === '') {
        feedback(422, 'Vul alle verplichte velden in.', $isHtmx, $redirect);
    }
}

$email = $fields['email'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $email)) {
    feedback(422, 'Vul een geldig e-mailadres in.', $isHtmx, $redirect);
}

if ($type === 'verrijkjewijk') {
    if ($fields['terms'] !== 'on') {
        feedback(422, 'Accepteer eerst de voorwaarden.', $isHtmx, $redirect);
    }
    if (!is_numeric(str_replace(',', '.', $fields['bedrag'])) || (float) str_replace(',', '.', $fields['bedrag']) < 0) {
        feedback(422, 'Vul een geldig bedrag in.', $isHtmx, $redirect);
    }
    if (!ctype_digit($fields['deelnemers']) || (int) $fields['deelnemers'] < 1) {
        feedback(422, 'Vul een geldig aantal deelnemers in.', $isHtmx, $redirect);
    }
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $fields['datum']);
    if (!$date || $date->format('Y-m-d') !== $fields['datum']) {
        feedback(422, 'Vul een geldige datum in.', $isHtmx, $redirect);
    }
}

$lines = [];
foreach ($config['labels'] as $key => $label) {
    $lines[] = $label . ': ' . ($fields[$key] ?: '-');
}
$body = "Nieuw bericht via de website\n\n" . implode("\n", $lines) . "\n";

$headers = [
    'From' => $config['from'],
    'Reply-To' => $email,
    'MIME-Version' => '1.0',
];

$attachment = $_FILES['attachment'] ?? null;
$hasAttachment = $attachment && ($attachment['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

if ($hasAttachment) {
    if (!is_array($attachment) || ($attachment['error'] ?? null) !== UPLOAD_ERR_OK || !is_uploaded_file($attachment['tmp_name'])) {
        feedback(422, 'De bijlage kon niet worden verwerkt.', $isHtmx, $redirect);
    }
    if ($attachment['size'] > 5 * 1024 * 1024) {
        feedback(413, 'De bijlage mag maximaal 5 MB zijn.', $isHtmx, $redirect);
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($attachment['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    if (!isset($allowed[$mime]) || @getimagesize($attachment['tmp_name']) === false) {
        feedback(422, 'Alleen geldige JPG- en PNG-afbeeldingen zijn toegestaan.', $isHtmx, $redirect);
    }

    // Bijlage blijft uitsluitend in het tijdelijke PHP-uploadbestand.
    // Geen openbaar uploadpad en geen door de gebruiker bepaalde bestandsnaam.
    $boundary = '=_wijkraad_' . bin2hex(random_bytes(16));
    $headers['Content-Type'] = 'multipart/mixed; boundary="' . $boundary . '"';
    $mailBody = '--' . $boundary . "\r\n";
    $mailBody .= "Content-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n";
    $mailBody .= chunk_split(base64_encode($body)) . "\r\n";
    $mailBody .= '--' . $boundary . "\r\n";
    $mailBody .= 'Content-Type: ' . $mime . '; name="bijlage.' . $allowed[$mime] . '"' . "\r\n";
    $mailBody .= 'Content-Disposition: attachment; filename="bijlage.' . $allowed[$mime] . '"' . "\r\n";
    $mailBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $mailBody .= chunk_split(base64_encode((string) file_get_contents($attachment['tmp_name']))) . "\r\n";
    $mailBody .= '--' . $boundary . "--\r\n";
} else {
    $headers['Content-Type'] = 'text/plain; charset=UTF-8';
    $mailBody = $body;
}

if (!mail($config['to'], $config['subject'], $mailBody, $headers)) {
    feedback(500, 'Verzenden is momenteel niet mogelijk. Probeer het later opnieuw.', $isHtmx, $redirect);
}

$_SESSION['last_sent_' . $type] = time();
feedback(200, 'Bedankt! Je bericht is succesvol verzonden.', $isHtmx, $redirect);
