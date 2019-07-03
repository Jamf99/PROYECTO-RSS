<?php

  session_start();
  require 'database.php';

  if(isset($_SESSION['user_id'])) {

    $records = $conn->prepare('SELECT id, name, email, password, suscriptions FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
      $user = $results;
    }

    $mensaje = '';

    $suscripciones = $user['suscriptions'];
    if(!empty($_POST['suscripcion'])){
      $suscripcion_a_agregar = $_POST['suscripcion'];
      $actualizado = '';
      if(!empty($suscripciones) && $suscripciones != '' && $suscripciones != NULL){
        $actualizado = $suscripciones.','.$suscripcion_a_agregar;
      } else{
        $actualizado = $suscripcion_a_agregar;
      }
      $stmt = $conn->prepare("UPDATE users SET suscriptions = '$actualizado' WHERE id = :id");
      $stmt->bindParam(':id', $_SESSION['user_id']);
      if($stmt->execute()) {
        $mensaje = 'Suscripción agregada';
      }else{
        $mensaje = 'No se pudo agregar la suscripción';
      }

    }

  }

?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>RSS Project</title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <?php if(!empty($user)): ?>
      <nav>
        <div class="logo">
          <a href="index.php"><img src="assets/images/logo_principal.png" alt="" width="70px"></a><br>
        </div>
        <ul class="nav-links">
          <li><a>¡Hola! <?=$user['name']?></a></li>
          <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
      </nav>

      <div class="principal">
        <div class="suscripcion" style="height:50%; width : 500px;">
          <form class="" action="index.php" method="post">
            <label for="">Escribe el link del sitio web en dónde te quieras suscribir</label>
            <input type="text" name="suscripcion" class="txtSuscripcion"><br>
            <input type="submit" name="" value="Suscribirse" class="suscribirse">
          </form>
          <?php if(!empty($mensaje)): ?>
            <p><b><?= $mensaje ?></b></p>
          <?php endif; ?>
        </div>

        <div class="noticias">
          <?php
            echo "<h2>Mis Noticias</h2>";
            if(!empty($suscripciones)) {
              $arreglo = explode(",", $suscripciones);
              foreach($arreglo as $pagina_suscripcion){
                $articulos = simplexml_load_string(file_get_contents($pagina_suscripcion));
                $num_noticia=1;
                $max_noticias=10;
                foreach($articulos->channel->item as $noticia){
                  $fecha = date("d/m/Y - ", strtotime($noticia->pubDate));?>
                  <article>
                      <h5><a href="<?php echo $noticia->link; ?>"><?php echo $noticia->title; ?></a></h5>
                      <?php echo $fecha; ?>
                      <?php echo $noticia->description; ?>
                  </article>
                  <?php $num_noticia++;
                  if($num_noticia > $max_noticias){
                      break;
                  }
                }
              }
            } else {
              echo "<br><br><br><br><br><br><br><p>No tienes noticias aún</p>";
            }
          ?>
        </div>
      </div>
    <?php else: ?>
      <div class="contenedora">
        <img src="assets/images/logo_principal.png" alt="" width="250px"><br>
        <a class="login" href="login.php">Iniciar Sesión</a>
        <a class="register" href="signup.php">Registrarse</a>
      </div>
    <?php endif; ?>
  </body>
</html>
