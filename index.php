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

    //Para el fondo de pantalla
    $mensajito = '';
    if(!empty($_FILES['imagen']['name'])) {
      $imagen = $_FILES['imagen']['name'];
      $ruta = $_FILES['imagen']['tmp_name'];
      $mensajito = $imagen;
    }

    //Para filtrar
    $filtro = '';
    if(!empty($_POST['filtro'])) {
      $filtro = $_POST['filtro'];
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

        var extensionesPermitidas = /(.jpg|.png)$/i;

        if(!extensionesPermitidas.exec(archivoRuta)) {
          alert('Asegúrate de haber escogido una imagen');
          archivoInput.value = '';
          return false;
        } else {
          return true;
        }
      }

    </script>
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
              echo "<h1>MIS NOTICIAS</h1>";
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
              echo $filtro;
            } else if($filtro != '') {
              echo "<h1>MIS NOTICIAS de $filtro</h1>";
              $articulos = simplexml_load_string(file_get_contents($filtro));
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
            } else {
              echo "<h1>MIS NOTICIAS</h1>";
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
