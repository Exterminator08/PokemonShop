<?php
require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Helpers/Message.php';
require_once __DIR__ . '/../Helpers/Auth.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function redirectBack()
{
    header("Location: ../../login.php");
    exit();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email)) {
    setError('email-mandatory', 'E-mailadres is verplicht.');
    redirectBack();
}
if (empty($password)) {
    setError('password-mandatory', 'Wachtwoord is verplicht.');
    redirectBack();
}

if (!Database::query("SELECT * FROM users WHERE email = :email", [':email' => $email])) {
    setError('credentials-error', 'Ongeldige gebruikersgegevens.');
    redirectBack();
}

$user = Database::get();

if (!$user || !password_verify($password, $user->password)) {
    setError('credentials-error', 'Ongeldige gebruikersgegevens.');
    redirectBack();
}

login($user);
setMessage('login-messages', 'Welkom terug, ' . htmlspecialchars($user->firstname) . '!');
header("Location: ../../index.php");
exit();
