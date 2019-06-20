<?php
  require 'database.php';
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

  </head>
  <body>
    <?php

    $enviado = false;
    $Vemail = NULL;
    $Vpass = NULL;
    $Vconfpass = NULL;

      if(isset($_POST['registrar'])){
        $enviado = true;
        $Vemail = $_POST['email'];
        $Vpass = $_POST['password'];
        $Vconfpass =$_POST['passwordConf'];
      }
     ?>
    <h1>Crear cuenta</h1>
    <div class="container">
    		<form action="registro.php" method = "POST">

    		<label for ="user">Email</label>
        <input  class="rg" type ="email" name = "email"  value = "<?php echo $Vemail;?>" placeholder="Escribe tu email" required >

      	<label for ="password"> Contraseña</label>
    		<input  class="rg" type="password"  name="password" value = "<?php echo $Vpass;?>" placeholder="Escribe tu contraseña" required>

        <label for ="password"> Confirmar Contraseña</label>
        <input  class="rg" type="password"  name="passwordConf" value = "<?php echo $Vconfpass;?>" placeholder="Repite tu contraseña" required>

      	<input  type="submit" name="registrar" value="Registrarse">
    		</form>
    	</div>
      <span>¿Ya tienes cuenta? </span><a href="index.php">Iniciar Sesión</a> <br><br>

      <?php

        if($enviado){
          $message = '';


              if($_POST['password'] == $_POST['passwordConf'] ){
                $mail = $_POST['email'];
                $pass = password_hash($_POST['password'],PASSWORD_BCRYPT);
                $sql = "INSERT INTO usersdb (email,password) VALUES ('$mail', '$pass')";

                  if (mysqli_query($conexion, $sql)) {
                      header ("location:cuentaCreada.php");
                  } else {
                        $message = 'El usuario ya se encuentra registrado.';
                  }
                  mysqli_close($conexion);
              }else{
                  $message = 'Las contraseñas no coinciden.';
              }

              echo "<h4><div style=\"color: #FF0000\">$message</div></h4>";

        }
       ?>
  </body>
</html>
