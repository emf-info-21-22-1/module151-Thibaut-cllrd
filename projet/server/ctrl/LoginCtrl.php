<?php
require_once('./services/LoginService.php');
require_once('models/User.php');

class LoginCtrl
{
    private $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();
    }

    //check si le login est ok echo 200 si oui et 401 si non
    public function checkLogin($mail, $password)
    {
        if (!empty($mail) && !empty($password)) {
            $user = new User($mail, $password);

            if ($this->loginService->checkLogin($user)) {
                echo 200;
            } else {
                echo 401;
            }


        } else {
            echo 401;
        }

    }
    //Créer un nouveau profile
    public function createProfile($mail, $name, $firstname, $password, $picture){
        if (!empty($mail) && !empty($name) && !empty($firstname) && !empty($password)) {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $user = new User($mail, $hashPassword);
            $user->setName($name);
            $user->setFirstname($firstname);
            if(!empty($picture)) {
                $user->setPicture($picture);
            }

            if($loginService->createProfile($user)){
                echo 200;
            }
            else{
                //Une erreur est survenue et le profil n'a pas été créé
                echo 500;
            }
            

        }
        else{
            //La requete est incomplète ou mal formulée
            echo 400;
        }

}




?>