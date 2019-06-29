<?php

  session_start();
  require 'database.php';

  if(isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT id, name, email, password FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
      $user = $results;
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
        <div class="suscripcion">
          <form class="" action="index.html" method="post">
            <label for="">Escribe el link del sitio web en dónde te quieras suscribir</label><br>
            <input type="text" name="" value=""><br>
            <input type="submit" name="" value="Suscribirse">
          </form>
        </div>

        <div class="noticias">
          <?php

            $xml ='http://www.eltiempo.com/contenido/opinion/rss.xml';
            $num_noticias = 5;
            $long_description = 100;
            $noticias = simplexml_load_file($xml);

            foreach($noticias as $noticia) {
              $n = 0;
              foreach($noticia as $reg){
                if($reg->title != NULL && $reg->title != '' && $reg->description != NULL && $reg->description != '' && $n < $num_noticias) {
                  ?> <div class="noticia"> <?php
                    echo '<h4><a href="'.$reg->link.'" target="_blank">'.$reg->title.'</a></h4>';
                    if(strlen($reg->description) > $long_description){
                      echo '<p>'.substr($reg->description, 0, $long_description).'....</a></p>';
                    }else if($reg->description == NULL || $reg->description == '') {

                    }else {

                    }
                    $n++;
                  ?></div> <?php
                }
              }
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
