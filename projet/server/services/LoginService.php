<?php
require_once('ConnectionService.php');

class LoginService{
private $connexion;    

public function __construct(){
    $this->connexion = ConnectionService::getInstance();
}


//Check le login et retourne vrai si c'est ok
public function checkLogin($user){
    $return = false;
    $user1 = $this ->connexion->selectSingleQuery('SELECT * FROM t_user WHERE mail= ?' , [$user->getMail()]);
    
        $mail = $user1['mail'];
        $password = $user1['password'];
        if($user->getMail() == $mail){
            if(password_verify($user->getPassword(), $password)){  
                $return = true;
            }
            else{
                //Password incorrecte
                $return = false;
            }
        }
        else{
            $return = false;
        }
    
    return $return;
}

public function createProfile(){}

public function disconnect(){}

}


