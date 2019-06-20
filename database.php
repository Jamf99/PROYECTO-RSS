<?php
$servername = "localhost";
$database = "proyectorss";
$username = "root";
$password = "";
//crea Conexion - valida conexion
  $conexion=mysqli_connect($servername,$username,$password,$database) or die("--Falló la conexión".mysqli_error($conexion));
?>
