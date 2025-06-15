<!-- Página Iniciar sesión -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión · Forma-T</title>
  <?php require_once '../includes/styles.php'; ?>
  <link rel="stylesheet" href="../resources/css/account.css">
</head>
<body>
  <!-- Incluye la cabecera -->
  <?php require_once '../includes/header.php'; ?>

  <div class="account-page">
    <div class="account-container">
      <h1>Iniciar sesión</h1>

      <!-- Se muestra el mensaje de error si es necesario -->
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
          <?php 
            if ($_GET['error'] == 'incorrect-credentials') {
                echo 'El correo electrónico o la contraseña son incorrectos. Inténtalo de nuevo.';
            } elseif ($_GET['error'] == 'invalid-email-format') {
                echo 'El formato del correo electrónico no es válido. Introduce un correo electrónico correcto';
            }
          ?>
        </div>
      <?php endif; ?>

      <!-- Formulario de Inicio de sesión -->
      <form action="../../controllers/login_process.php" method="POST">
        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input type="text" id="email" name="email" placeholder="Introduce el correo electrónico" required>
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="Introduce la contraseña" required>
        </div>
        <button type="submit" class="account-button">Iniciar sesión</button>
      </form>
      <a href="register.php" class="account-link">¿No tienes una cuenta? Regístrate</a>
    </div>
  </div>

  <!-- Scripts -->
  <?php require_once '../includes/scripts.php'; ?>
</body>
</html>









