<?php
@include_once(__DIR__ . '/src/Helpers/Auth.php');
@include_once(__DIR__ . '/src/Helpers/Message.php');
@include_once(__DIR__ . '/template/head.inc.php');
?>

<form method="POST" action="src/Formhandlers/register.php" class="form">
   <h2>Register</h2>

   <?php if (hasError('registration-error')): ?>
      <p class="error"><?= getError('registration-error') ?></p>
   <?php endif; ?>

   <label>First Name</label>
   <input type="text" name="firstname" value="<?= old('firstname') ?>" />
   <?= errorText('firstname-mandatory') ?>

   <label>Last Name</label>
   <input type="text" name="lastname" value="<?= old('lastname') ?>" />
   <?= errorText('lastname-mandatory') ?>

   <label>Email</label>
   <input type="email" name="email" value="<?= old('email') ?>" />
   <?= errorText('email-mandatory') ?>

   <label>Password</label>
   <input type="password" name="password" />
   <?= errorText('password-mandatory') ?>

   <label>Confirm Password</label>
   <input type="password" name="password_confirm" />
   <?= errorText('password-confirm') ?>

   <button type="submit">Register</button>
</form>

<?php @include_once(__DIR__ . '/template/foot.inc.php'); ?>

<?php
function errorText($key) {
   if (hasError($key)) {
      return '<p class="error">' . getError($key) . '</p>';
   }
   return '';
}
?>
