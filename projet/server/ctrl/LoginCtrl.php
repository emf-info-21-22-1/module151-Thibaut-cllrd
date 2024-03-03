<?php
require_once('./services/LoginService.php');
require_once('models/User.php');
require_once('SessionCtrl.php');

class LoginCtrl
{
    private $loginService;
    private $session;

    public function __construct($session)
    {
        $this->loginService = new LoginService();
        $this->session = $session;
    }

    //check si le login est ok, retourne 200 si oui et 401 si non
    public function checkLogin($mail, $password)
    {
        if (!empty($mail) && !empty($password)) {

            $user = new User(null);
            $user->setMail($mail);
            $user->setPassword($password);
            if ($this->loginService->checkLogin($user)) {
                http_response_code(200);
                //Ajoute le mail dans la session
                $this->session->set('mail', $user->getMail());
            } else {
                http_response_code(401);
            }
        } else {
            http_response_code(400);
        }

    }
    //Créer un nouveau profile
    public function createProfile($username, $mail, $name, $firstname, $password, $picture)
    {
        if (!empty($username) && !empty($mail) && !empty($name) && !empty($firstname) && !empty($password)) {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $user = new User($username);
            $user->setMail($mail);
            $user->setPassword($hashPassword);
            $user->setName($name);
            $user->setFirstname($firstname);
            if (!empty($picture)) {
                $user->setPicture($picture);
            }
            $result = $this->loginService->createProfile($user);
            if ($result) {
                http_response_code(200);
            } elseif(!$result) {
                //Une erreur est survenue et le profil n'a pas été créé
                http_response_code(500);
            }
            elseif($result == 'alreadyExist') {
                http_response_code(409);
            }


        } else {
            //La requete est incomplète ou mal formulée
            http_response_code(400);
        }

    }
}



