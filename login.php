<?php
  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: /Proyecto_RSS');
  }

  require 'database.php';

  if(!empty($_POST['email']) && !empty($_POST['password'])) {
    $message = 'No ha ocurrido nada 1';
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email=:email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if(count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
      $_SESSION['user_id'] = $results['id'];
      header('Location: /Proyecto_RSS');
    }else {
      $message = "Lo siento, sus credenciales no coinciden";
    }
  }

?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />
    <link rel="stylesheet" href="assets/css/loginStyles.css">
  </head>
  <body>
    <form action="login.php" method="post" class="formulario" style="margin-top:15px;">
        <a href="index.php"><img src="assets/css/images/logo_principal.png" alt="" width="250px"></a><br>
        <h1>Iniciar sesión</h1>
        <?php if(!empty($message)): ?>
          <p><b><?= $message ?></b></p>
        <?php endif; ?>
        <div class="contenedor">
            <div class="input-contenedor">
                <i class="fas fa-envelope icon"></i>
                <input placeholder="Correo Electrónico" name="email" type="email" required />
            </div>
            <div class="input-contenedor">
                <i class="fas fa-key icon"></i>
                <input placeholder="Contraseña" type="password" name="password" required />
            </div>
            <input type="submit" value="Iniciar sesión" class="button" />
            <p>¿No tienes cuenta?<a class="link" href="signup.php"> Registrate aquí</a></p>
        </div>
    </form>
  </body>
</html>
