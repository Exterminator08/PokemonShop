<?php
@include_once(__DIR__ . '/src/Helpers/Auth.php');
@include_once(__DIR__ . '/src/Helpers/Message.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   if (isset($_POST['user_id']) && $_POST['user_id'] == user_id()) {
      logout();
      setMessage('login-messages', 'U bent succesvol uitgelogd.');
   }
}

header('Location: index.php');
exit();
