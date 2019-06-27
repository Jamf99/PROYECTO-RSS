<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />
    <link rel="stylesheet" href="assets/css/registerStyles.css" />
  </head>
  <body>
    <form method="post" class="form-register" style="margin-top:25px;">
        <h1>¡Únete a nosotros!</h1>
        <div class="input-contenedor">
            <i class="fas fa-user icon"></i>
            <input placeholder="Nombre Completo" type="text" required />
        </div>
        <div class="input-contenedor">
            <i class="fas fa-envelope icon"></i>
            <input placeholder="Correo Electrónico" type="email" required />
        </div>
        <div class="input-contenedor">
            <i class="fas fa-key icon"></i>
            <input placeholder="Contraseña" type="password" required />
        </div>
        <div class="input-contenedor">
            <i class="fas fa-key icon"></i>
            <input placeholder="Confirmar contraseña" type="password" required />
        </div>
        <input type="submit" value="Registrarse" class="button" />
        <br />
        <p>¿Ya tienes cuenta?<a class="link" href="login.php"> Inicia sesión aquí</a></p>
    </form>
</body>
</html>
