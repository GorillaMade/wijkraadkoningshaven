<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// 🌍 Configuratie
$domain = "wijkraadkoningshaven.nl";
$allowed_file_types = ["image/jpeg", "image/png"];

// 📨 Formulieren configureren
$forms = [
    "contact" => [
        "from" => "contact@$domain",
        "to" => "info@$domain",
        "timeout" => 1800 // 30 minuten
    ],
    "verrijkjewijk" => [
        "from" => "verrijkjewijk@$domain",
        "to" => "vjw@$domain",
        "timeout" => 3600 // 1 uur
    ]
];

// 🛡️ Form type ophalen
$form_type = $_POST["_form"] ?? "contact";
if (!isset($forms[$form_type])) {
    http_response_code(400);
    echo "<p class='error'>❌ Ongeldig formulier.</p>";
    exit;
}

$form_config = $forms[$form_type];
$from_email = $form_config["from"];
$to = $form_config["to"];
$form_timeout = $form_config["timeout"];

// 🛡️ Rate limiting per formulier
$session_key = "last_email_time_" . $form_type;
if (isset($_SESSION[$session_key]) && time() - $_SESSION[$session_key] < $form_timeout) {
    http_response_code(429);
    echo "<p class='error'>Je kunt dit formulier slechts 1 keer per " . ($form_timeout/60) . " minuten verzenden.</p>";
    exit;
}

// 🐝 Honeypot check
if (!empty($_POST["honeypot_field"])) {
    http_response_code(400);
    echo "<p class='error'>Spam gedetecteerd.</p>";
    exit;
}

// 🔧 Verplichte velden (pas aan per formulier indien nodig)
if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["message"])) {
    http_response_code(400);
    echo "<p class='error'>Vul alle verplichte velden in.</p>";
    exit;
}

// 📜 E-mail samenstellen
$subject = $_POST["_subject"] ?? "Nieuw bericht via website";
$fields = $_POST;
unset($fields["_subject"], $fields["_next"], $fields["_form"], $fields["honeypot_field"]);

$message = "Nieuw bericht ontvangen:\n\n";
foreach ($fields as $key => $value) {
    $message .= ucfirst($key) . ": " . trim($value) . "\n";
}

// 📎 Bestand uploaden (optioneel)
$attachment_path = null;
if (!empty($_FILES["attachment"]["name"])) {
    $file_type = $_FILES["attachment"]["type"];
    if (!in_array($file_type, $allowed_file_types)) {
        http_response_code(400);
        echo "<p class='error'>Ongeldig bestandstype. Alleen JPG en PNG zijn toegestaan.</p>";
        exit;
    }

    $upload_dir = __DIR__ . "/uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $attachment_path = $upload_dir . basename($_FILES["attachment"]["name"]);
    move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment_path);
}

// 📩 E-mail headers
$reply_to = $fields["email"] ?? $to;

if ($attachment_path) {
    $boundary = md5(time());
    $headers = "From: $from_email\r\n";
    $headers .= "Reply-To: $reply_to\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $email_body = "--$boundary\r\n";
    $email_body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $email_body .= "$message\r\n\r\n";
    $email_body .= "--$boundary\r\n";
    $email_body .= "Content-Type: application/octet-stream; name=\"" . basename($attachment_path) . "\"\r\n";
    $email_body .= "Content-Disposition: attachment; filename=\"" . basename($attachment_path) . "\"\r\n";
    $email_body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $email_body .= chunk_split(base64_encode(file_get_contents($attachment_path))) . "\r\n";
    $email_body .= "--$boundary--";
} else {
    $headers = "From: $from_email\r\n";
    $headers .= "Reply-To: $reply_to\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8";
    $email_body = $message;
}

// 📨 Verstuur e-mail
if (mail($to, $subject, $email_body, $headers)) {
    $_SESSION[$session_key] = time();

    // Als je HTMX gebruikt, geef een HTML-feedback terug
    echo "<p class='success'>Uw bericht is succesvol verzonden. We nemen snel contact met u op!</p>";
} else {
    http_response_code(500);
    echo "<p class='error'>Er ging iets mis bij het verzenden van uw bericht.</p>";
}
?>
