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

    //check si le login est ok, retourne 200 si oui, 401 si non et 400 si les champs ne sont pas remplis
    public function checkLogin($mail, $password)
    {
        if (!empty($mail) && !empty($password)) {

            $user = new User(null);
            $user->setMail($mail);
            $user->setPassword($password);
            $result = $this->loginService->checkLogin($user);
            if ($result) {
                http_response_code(200);
                //Ajoute le mail dans la session
                $this->session->set('mail', $user->getMail());
                $this->session->set('pkUser', $result);
            } else {
                http_response_code(401);
            }
        } else {
            http_response_code(400);
        }

    }
    /**
     * Créé un compte utilisateur et retourne les codes http.
     */
    public function createProfile($username, $mail, $name, $firstname, $password, $picture){
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
            if ($result == 'ok') {
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



