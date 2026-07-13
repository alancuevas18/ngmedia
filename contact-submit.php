<?php
require __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Honeypot: si el campo oculto viene lleno, es un bot — fingimos éxito y salimos.
if (!empty($_POST['website'])) {
    header('Location: index.php?status=success#contacto');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

$isValid = $name !== ''
    && $message !== ''
    && filter_var($email, FILTER_VALIDATE_EMAIL)
    && mb_strlen($name) <= 150
    && mb_strlen($message) <= 2000;

if (!$isValid) {
    header('Location: index.php?status=error#contacto');
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO contact_messages (name, email, phone, message) VALUES (:name, :email, :phone, :message)'
);
$stmt->execute([
    'name' => $name,
    'email' => $email,
    'phone' => $phone !== '' ? $phone : null,
    'message' => $message,
]);

header('Location: index.php?status=success#contacto');
exit;
