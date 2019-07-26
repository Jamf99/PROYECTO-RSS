<?php

  session_start();
  require 'database.php';

  if(isset($_SESSION['user_id'])) {

    //Se encarga de extrer la información del usuario desde la base de datos
    $records = $conn->prepare('SELECT id, name, email, password, suscriptions, wallpapers, favoritos FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if ($results != null && count($results) > 0) {
      $user = $results;
    }

    $favs = $user['favoritos'];

  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Favoritos</title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <nav>
      <div class="logo">
        <a href="index.php"><img src="assets/images/logo_principal.png" alt="" width="70px"></a><br>
      </div>
      <ul class="nav-links">
        <li><a>¡Hola! <?=$user['name']?></a></li>
        <li><a href="favorites.php" >Mis favoritos</a></li>
        <li><a href="logout.php">Cerrar sesión</a></li>
      </ul>
    </nav>
    <div class="favoritos">
      <h1>Mis Favoritos</h1>
      <div class="favoritos-container">
        <?php
        if(!empty($favs)) {
          $arreglo_favoritos = explode(",", $favs);
          foreach($arreglo_favoritos as $favorito_a_mostrar) { ?>
            <form class="links-favoritos" action="index.html" method="post">
              <a href="<?php echo $favorito_a_mostrar; ?>"><?php echo $favorito_a_mostrar; ?></a>  <a style="color:red;"href="#">✖</a>
            </form>
          <?php
          }
        }
        ?>
      </div>
    </div>
  </body>
</html>
