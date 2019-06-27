<?php

  $server = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'proyecto_rss2';

  try {
    $conn = new  PDO("mysql:host=$server;dbname=$database;", $username, $password);
  } catch (PDOException $e) {
    die('Connection failed: '.$e->getMessage());
  }

?>
