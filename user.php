<?php

  class User extends DB{

    private $UserEmail;

    public function userExists($email, $pass){
      $md5pass = md5($pass);

      $query = $this->connect()->prepare('SELECT * FROM usersdb WHERE email = :email AND password = :pass');
      $query -> execute(['email' => $email, 'pass' => $md5pass]);

      if($query -> rowCount()){
        return true;
      }else{
        return false;
      }
    }

    public function setUser($email){
      $query = $this->connect()->prepare('SELECT * FROM usersdb WHERE email = :email');
      $query->execute(['email' => $email]);

      foreach($query as $currentUser){
        $this->userEmail = $currentUser['email'];
      }
    }

    public getEmail(){
      
    }

  }

?>
