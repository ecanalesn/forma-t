<!-- Página Crear nueva cuenta -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Regístrate · Forma-T</title>
  <?php require_once '../includes/styles.php'; ?>
  <link rel="stylesheet" href="../resources/css/account.css">
  <link rel="stylesheet" href="../resources/css/register.css"> 
</head>
<body>
  <!-- Incluye la cabecera -->
  <?php require_once '../includes/header.php'; ?>

  <div class="account-page"> 
    <div class="account-container">
      <h1>Crear una cuenta</h1>

      <!-- Se muestra el mensaje de éxito si la cuenta se creó correctamente -->
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
          Cuenta creada correctamente. Puedes iniciar sesión ahora.
        </div>
        <script>
        // Se redirige al login después de 3 segundos
        setTimeout(function() {
            window.location.href = "login.php"; 
        }, 3000);  
    </script>
      <?php endif; ?>

      <!-- Se muestra el mensaje de error si es necesario -->
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
          <?php 
          if ($_GET['error'] == 'password-too-short') {
                echo 'La contraseña debe tener al menos 6 caracteres.';
            } elseif ($_GET['error'] == 'password-contains-spaces') {
                echo 'La contraseña no puede contener espacios.';
            } elseif ($_GET['error'] == 'invalid-email-format') {
              echo 'El formato del correo electrónico no es válido. Introduce un correo electrónico correcto';
            }elseif ($_GET['error'] == 'email-exists') {
                  echo 'El correo electrónico que has introducido ya está registrado.';
            } elseif ($_GET['error'] == 'registration-failed') {
                echo 'Error al registrar la cuenta. Inténtalo de nuevo más tarde.';
            } elseif ($_GET['error'] == 'db-error') {
                echo 'Hubo un error en la base de datos. Inténtalo de nuevo más tarde.';
            }
          ?>
        </div>
      <?php endif; ?>

      <!-- Formulario de registro -->
      <form action="../../controllers/register_process.php" method="POST">
        <div class="form-group">
          <label for="first_name">Nombre</label>
          <input type="text" id="first_name" name="first_name" required placeholder="Introduce tu nombre">
        </div>
        <div class="form-group">
          <label for="last_name">Apellidos</label>
          <input type="text" id="last_name" name="last_name" required placeholder="Introduce tus apellidos">
        </div>  
        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" required placeholder="Introduce tu correo electrónico">
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" required placeholder="Crea una contraseña">
        </div>
        <button type="submit" class="account-button">Crear cuenta</button>
      </form>

      <!-- Enlace de vuelta al login -->
      <a href="login.php" class="account-link">¿Ya tienes una cuenta? Inicia sesión aquí</a>
    </div>
  </div>
    <!-- Scripts -->
    <?php require_once '../includes/scripts.php'; ?>
</body>
</html>



