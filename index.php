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

    $mensaje = '';

    //Función que permite agregar una suscripción a la base de datos
    $suscripciones = $user['suscriptions'];
    if(!empty($_POST['suscripcion'])){
      $suscripcion_a_agregar = $_POST['suscripcion'];
      $actualizado = '';
      if(!empty($suscripciones) && $suscripciones != '' && $suscripciones != NULL){
        $actualizado = $suscripciones.','.$suscripcion_a_agregar;
      } else{
        $actualizado = $suscripcion_a_agregar;
      }
      $nuevo = str_replace(" ", "", $actualizado);
      $stmt = $conn->prepare("UPDATE users SET suscriptions = '$nuevo' WHERE id = :id");
      $stmt->bindParam(':id', $_SESSION['user_id']);
      if($stmt->execute()) {
        $mensaje = 'Suscripción agregada';
        header('Location: /Proyecto_RSS');
      }else{
        $mensaje = 'No se pudo agregar la suscripción';
      }

    }

    //Función que permite agregar una noticia a los favoritos
    $favoritos = $user['favoritos'];
    if(!empty($_GET['favorito'])) {
      $favorito_a_agregar = $_GET['favorito'];
      $array = explode(",", $favoritos);
      $repetido = false;
      foreach($array as $fav) {
        if($fav == $favorito_a_agregar) {
          $repetido = true;
        }
      }
      if(!$repetido){
        $fav_actualizado = '';
        if(!empty($favoritos) && $favoritos != '' && $favoritos != NULL){
          $fav_actualizado = $favoritos.','.$favorito_a_agregar;
        } else{
          $fav_actualizado = $favorito_a_agregar;
        }
        $stmt = $conn->prepare("UPDATE users SET favoritos = '$fav_actualizado' WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['user_id']);
        if($stmt->execute()) {
          header('Location: /Proyecto_RSS');
        }else{
          $mensaje = 'No se pudo agregar el favorito';
        }
      }else {
        header('Location: /Proyecto_RSS');
      }
    }

    //Función que permite eliminar una suscripción de la base de datos
    $flag = '';
    if(!empty($_GET['suscription'])) {
      $suscripcion_a_eliminar = $_GET['suscription'];
      $arreglo = explode(",", $suscripciones);
      if(count($arreglo) == 1){
        $nuevo = str_replace($suscripcion_a_eliminar,"", $suscripciones);
      }else if($arreglo[0] == $suscripcion_a_eliminar){
        $nuevo = str_replace($suscripcion_a_eliminar.',',"", $suscripciones);
      } else {
        $nuevo = str_replace(','.$suscripcion_a_eliminar,"", $suscripciones);
      }
      $consulta = $conn->prepare("UPDATE users SET suscriptions = '$nuevo' WHERE id = :id");
      $consulta->bindParam(':id', $_SESSION['user_id']);
      if($consulta->execute()) {
        header('Location: /Proyecto_RSS');
      }
    }

    //Función que permite cambiar el fondo de pantalla
    $mensajito = '';
    $destino = '';
    if(!empty($user['wallpapers']) || $user['wallpapers'] != NULL || $user['wallpapers'] != '') {
      $destino = $user['wallpapers'];
    }
    if(!empty($_FILES['imagen']['name'])) {
      $file = $_FILES['imagen']['name'];
      $ruta = $_FILES["imagen"]["tmp_name"];
      $destino = "assets/images/wallpaper/".$file;
      copy($ruta, $destino);
      $query = $conn->prepare("UPDATE users SET wallpapers = '$destino' WHERE id = :id");
      $query->bindParam(':id', $_SESSION['user_id']);
      if($query->execute()){
        $mensajito = '';
      } else {
        $mensajito = 'No se pudo agregar la imagen';
      }
    }

    //Función que permite filtrar el contenido de noticias teniendo en cuenta la suscripción
    $filtro = '';
    if(!empty($_POST['filtro'])) {
      $filtro = $_POST['filtro'];
    }

    //Indica la cantidad máxima de noticias que desea ver el usuario por suscripción
    $max_noticias = 10;
    if(!empty($_POST['total_noticias'])) {
      $max_noticias = $_POST['total_noticias'];
    }
  }

?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>RSS Project</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
      function eliminarSuscripcion() {
        if(confirm("¿Seguro que quieres eliminar esta suscripción?")) {
          return true;
        }else{
          return false;
        }
      }
      function validarExtension() {
        var archivoInput = document.getElementById('archivoInput');
        var archivoRuta = archivoInput.value;

        var extensionesPermitidas = /(.jpg|.png|.gif)$/i;

        if(!extensionesPermitidas.exec(archivoRuta)) {
          alert('Asegúrate de haber escogido una imagen');
          archivoInput.value = '';
          return false;
        } else {
          return true;
        }
      }
    </script>
    <style media="screen">
      body {
        <?php
          if($destino != '') : ?>
            background-image: url("<?= $destino ?>");
            margin: 0;
            padding: 0;
            font-family: 'Mali', cursive;
            background-size: cover;
            background-attachment: fixed;
         <?php endif; ?>
      }
    </style>
  </head>

  <body>
    <?php if(!empty($user)): ?>
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
      <?php if(false == false) : ?>
        <div class="principal">
          <div class="izquierda">
            <form class="contenedor" style="height:130px; width : 500px;" action="index.php" method="post">
              <input type="text" placeholder="Escribe el formato RSS del sitio web donde desees suscribirte" name="suscripcion" class="txtSuscripcion"><br>
              <input style="margin-top: 15px;"type="submit" name="" value="Suscribirse" class="button">
            </form>
            <div class="panel_auxiliar">
              <div class="suscripciones">
                <h3>Mis suscripciones</h3>
                <?php
                if(!empty($suscripciones)) {
                  $arreglo = explode(",", $suscripciones);
                  foreach($arreglo as $suscripcion) { ?>
                    <ul>
                      <form class="" action="index.html" method="post">
                        <li><?php echo $suscripcion.' ' ?> <a onclick="return eliminarSuscripcion();" href="index.php?suscription=<?php echo $suscripcion;?>">Eliminar</a></li>
                      </form>
                    </ul>
                    <p><?=$flag?></p>
                    <?php
                  }
                }
                ?>
              </div>
              <div class="opciones">
                <form class="" action="index.php" method="post">
                  <label for="">Número de noticias que desee ver por suscripción</label><br>
                  <input type="number" min="1" name="total_noticias" value="<?= $max_noticias?>">
                  <input type="submit" name="" value="Confirmar">
                </form>
                <div class="filtrar">
                  <h5>FILTRAR POR SUSCRIPCIÓN</h5>
                  <form class="" action="index.php" method="post">
                    <select style="margin-top:20px;" class="" name="filtro">
                      <option value="">Mostrar todo</option>
                      <?php
                        $arreglo = explode(",", $suscripciones);
                        foreach($arreglo as $filtrado) {
                          ?><option value="<?= $filtrado ?>"><?php echo $filtrado ?></option><?php
                        }?>
                      ?>
                    </select><br>
                    <input style="margin-top : 30px;" class="boton_filtrar" type="submit" value="Filtrar">
                  </form>
                </div>
                <div class="preferencias" style="padding-top : 0px;">
                  <h5>PERSONALIZAR </h5><h6>FONDO PANTALLA</h6>
                  <form class="" action="index.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="imagen" id="archivoInput" onchange="return validarExtension();">
                    <input type="submit" name="" class="boton_personalizar" value="Cambiar">
                    <?=$mensajito?>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="noticias" style="padding-top:0px;">
            <?php

              if(!empty($suscripciones) && $filtro == '') {
                $arreglo = explode(",", $suscripciones);
                $total = count($arreglo)*$max_noticias;
                echo "<h1>MIS NOTICIAS ($total)</h1>";
                foreach($arreglo as $pagina_suscripcion){
                  $articulos = simplexml_load_string(file_get_contents($pagina_suscripcion));
                  $num_noticia=1;
                  foreach($articulos->channel->item as $noticia){
                    $fecha = date("d/m/Y - ", strtotime($noticia->pubDate));?>
                    <article>
                      <form class="" action="index.html" method="post">
                        <h5><a href="<?php echo $noticia->link; ?>"><?php echo $noticia->title; ?></a>  <a style="color:red;"href="index.php?favorito=<?php echo $noticia->link; ?>">★</a></h5>
                      </form>
                        <?php echo $fecha; ?>
                        <?php echo $noticia->description; ?>
                    </article>
                    <?php $num_noticia++;
                    if($num_noticia > $max_noticias){
                        break;
                    }
                  }
                  echo $max_noticias;
                }
              } else if($filtro != '') {
                echo "<h1>MIS NOTICIAS de $filtro ($max_noticias)</h1>";
                $articulos = simplexml_load_string(file_get_contents($filtro));
                $num_noticia=1;
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
              } else {
                echo "<h1>MIS NOTICIAS</h1>";
                echo "<br><br><br><br><br><br><br><p>No tienes noticias aún</p>";
              }
            ?>
          </div>
        </div>
      <?php else : ?>
        <div class="favoritos">

        </div>
      <?php endif; ?>
    <?php else : ?>
      <div class="contenedora">
        <img src="assets/images/logo_principal.png" alt="" width="250px"><br>
        <a class="login" href="login.php">Iniciar Sesión</a>
        <a class="register" href="signup.php">Registrarse</a>
      </div>
    <?php endif; ?>
  </body>
</html>
