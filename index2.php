<?php

  include_once 'user.php';
  include_once 'userSession.php';

  $userSession = new UserSession();
  $user = new User();

  if(isset($_SESSION['user'])) {
    echo "Hay sesión";
  }else if(isset($_POST['email']) && isset($_POST['password'])) {
    echo "Validación de login";
  }else {
    echo "Login";
    include_once 'index.php';
  }

?>
