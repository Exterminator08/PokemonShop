<?php
require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Helpers/Message.php';

session_start();

function redirectBack() {
    header("Location: ../../register.php");
    exit();
}

// Валидация
$required = ['firstname', 'lastname', 'email', 'password', 'password_confirm'];

foreach ($required as $field) {
    if (empty($_POST[$field])) {
        setError("{$field}-mandatory", ucfirst($field) . " is required.");
        redirectBack();
    }
}

if ($_POST['password'] !== $_POST['password_confirm']) {
    setError("password-confirm", "Passwords do not match.");
    redirectBack();
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    setError("email-mandatory", "Invalid email address.");
    redirectBack();
}

// Хеширование пароля
$data = [
    'firstname' => $_POST['firstname'],
    'lastname' => $_POST['lastname'],
    'email' => $_POST['email'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
];

$user = Database::insert('users', $data);

if ($user) {
    setMessage('success', 'Registration successful!');
    header("Location: ../../login.php");
    exit();
} else {
    setError('registration-error', 'Registration failed (user may already exist).');
    redirectBack();
}
