<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />
    <link rel="stylesheet" href="assets/css/loginStyles.css">
  </head>
  <body>
    <form method="post" class="formulario" style="margin-top:15px;">
        <a href="index.php"><img src="assets/css/images/logo_principal.png" alt="" width="250px"></a><br>
        <h1>Iniciar sesión</h1>
        <div class="contenedor">
            <div class="input-contenedor">
                <i class="fas fa-envelope icon"></i>
                <input placeholder="Correo Electrónico" type="email" required />
            </div>
            <div class="input-contenedor">
                <i class="fas fa-key icon"></i>
                <input placeholder="Contraseña" type="password" required />
            </div>
            <input type="submit" value="Iniciar sesión" class="button" />
            <p>¿No tienes cuenta?<a class="link" href="signup.php"> Registrate aquí</a></p>
        </div>
    </form>
  </body>
</html>
