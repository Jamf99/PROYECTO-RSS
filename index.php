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
    <?php else: ?>
      <div class="contenedora">
        <img src="assets/images/logo_principal.png" alt="" width="250px"><br>
        <a class="login" href="login.php">Iniciar Sesión</a>
        <a class="register" href="signup.php">Registrarse</a>
      </div>
    <?php endif; ?>
  </body>
</html>
