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
      <h1>Welcome <?= $user['name'] ?> !</h1>
      <br><a href="logout.php">Cerrar Sesión</a>
    <?php else: ?>
      <div class="contenedora">
        <img src="assets/css/images/logo_principal.png" alt="" width="250px"><br>
        <a class="login" href="login.php">Iniciar Sesión</a>
        <a class="register" href="signup.php">Registrarse</a>
      </div>
    <?php endif; ?>
  </body>
</html>
