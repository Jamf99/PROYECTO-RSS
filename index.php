<?php

  $message = '';
  if(!empty($_POST)){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $captcha =  $_POST['g-recaptcha-response'];

    $secret = '6LeZF6gUAAAAAFrsMIaYyhGgo2N5LL408EwNuFee';

      if(!$captcha){
        $message= 'Por favor verifique el captcha';
      }else{

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");

        $arr = json_decode($response, TRUE);

        if($arr['success']){
          $message= 'Captcha exitoso';
        }else{
        $message= 'Error al comprobar el captcha';
        }
      }
    }else{
      $message=  'Por favor complete todos los datos';

    }

    echo "<BR><div style=\"color: #FF0000\">$message</div>";

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Bienvenido al proyectoRSS</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
      <h1>Ingrese sus datos</h1>
      <?php

      $enviado = false;
      $Vemail = NULL;
      $Vpass = NULL;

        if(isset($_POST['iniciar'])){
          $enviado = true;
          $Vemail = $_POST['email'];
          $Vpass = $_POST['password'];
        }
       ?>
      <form id ="form" action="<?php  $_SERVER['PHP_SELF']; ?>" method="post">
        <table>
          <tr>
            <td class = "izq"> Email:</td>
            <td class = "der"> <input type ="email" name = "email"  value = "<?php echo $Vemail;?>" placeholder="Escribe tu email" required></td>
          </tr>
          <tr>
            <td class = "izq"> Contraseña:</td>
            <td class = "der"> <input type="password"  name="password" value = "<?php echo $Vpass;?>" placeholder="Escribe tu contraseña" required>
          </tr>
          <tr>
            <td colspan="2"><div class="g-recaptcha" data-sitekey="6LeZF6gUAAAAAIuV66nStblY31lTFKWAVBSW-bYi"></div></td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit"  name ="iniciar" value="Iniciar sesión"> </td>
          </tr>
        </table>
      </form>
    <span> ¿No estás registrado? </span> <a href="registro.php"> Crear cuenta</a>
  </body>
</html>
