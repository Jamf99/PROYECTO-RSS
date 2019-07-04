<?php

  if (isset($_SESSION['user_id'])) {
    header('Location: /Proyecto_RSS');
  }

  require 'database.php';

  $message = '';

  if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['name'])) {

    $consulta = $conn->prepare("SELECT email FROM users WHERE email = :email");
    $consulta->bindParam(':email', $_POST['email']);
    $consulta->execute();
    $results = $consulta->fetch(PDO::FETCH_ASSOC);


    if($results == '') {
      if($_POST['password'] == $_POST['confirm_password']){
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':email', $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);

        if($stmt->execute()) {
          $message = 'Usuario creado correctamente';
        }else {
          $message = 'Ha ocurrido un error al crear su cuenta';
        }
      }else {
        $message = 'Las contraseñas no coinciden';
      }
    } else {
      $message = 'Ya existe un usuario con este correo electrónico '.$results;
    }

  }

?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />
    <link rel="stylesheet" href="assets/css/registerStyles.css" />
  </head>
  <body>

    <form action="signup.php" method="post" class="form-register" style="margin-top:20px;">
        <h1>¡Únete a nosotros!</h1>
        <?php if(!empty($message)): ?>
          <p><b><?= $message ?></b></p>
        <?php endif; ?>
        <div class="input-contenedor">
            <i class="fas fa-user icon"></i>
            <input placeholder="Nombre Completo" name="name" type="text" required />
        </div>
        <div class="input-contenedor">
            <i class="fas fa-envelope icon"></i>
            <input placeholder="Correo Electrónico" name="email" type="email" required />
        </div>
        <div class="input-contenedor">
            <i class="fas fa-key icon"></i>
            <input placeholder="Contraseña" name="password" type="password" required />
        </div>
        <div class="input-contenedor">
            <i class="fas fa-key icon"></i>
            <input placeholder="Confirmar contraseña" name="confirm_password" type="password" required />
        </div>
        <input type="submit" value="Registrarse" class="button" />
        <br />
        <p>¿Ya tienes cuenta?<a class="link" href="login.php"> Inicia sesión aquí</a></p>
    </form>
</body>
</html>
