<?php
$errors = $errors ?? [];
$request = $request ?? [];

$emailError = $errors['email'] ?? '';
$passwordError = $errors['password'] ?? '';
$loginError = $errors['login'] ?? '';

$emailValue = htmlspecialchars($request['email'] ?? '');
$passwordValue = htmlspecialchars($request['password'] ?? '');

// Determine class, placeholder and value for form inputs based on errors
function getInputAttributes($error, $defaultPlaceholder, $value = '')
{
    $attributes = [];

    if ($error) {
        $attributes['placeholder'] = $error;
        $attributes['class'] = 'ph-error';
    } elseif ($value) {
        $attributes['value'] = $value;
    } else {
        $attributes['placeholder'] = $defaultPlaceholder;
    }
    return $attributes;
}

$emailAttributes = getInputAttributes($emailError, 'nombre@email.com', $emailValue);
$passwordAttributes = getInputAttributes($passwordError, 'Tu Contraseña', $passwordValue);

// If the error is general, display the generic error message in both fields
if ($loginError) {
    $emailAttributes = $passwordAttributes = [
        'placeholder' => $loginError,
        'class' => 'ph-error'
    ];
}
?>

<div class="auth-container login">
  <form class="auth" action="/auth" method="POST">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
    
    <label for="email">Correo Electrónico</label>
    <input type="text" id="email" name="email" 
           <?= implode(' ', array_map(fn ($k, $v) => "$k=\"$v\"", array_keys($emailAttributes), $emailAttributes)) ?>>

    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" 
           <?= implode(' ', array_map(fn ($k, $v) => "$k=\"$v\"", array_keys($passwordAttributes), $passwordAttributes)) ?>>

    <input class="btn" type="submit" value="Iniciar Sesion">
  </form>
</div>