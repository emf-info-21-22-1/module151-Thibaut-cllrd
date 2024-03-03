<?php
require_once('ConnectionService.php');

class LoginService
{
    private $connection;

    public function __construct()
    {
        $this->connection = ConnectionService::getInstance();
    }


    //Check le login et retourne vrai si c'est ok
    public function checkLogin($user)
    {
        $return = false;
        $userData = $this->connection->selectSingleQuery('SELECT * FROM t_user WHERE mail= ?', [$user->getMail()]);
        if ($userData) {
        $password = $userData['password'];
            if (password_verify($user->getPassword(), $password)) {
                $return = true;
            } else {
                //Password incorrecte
                $return = false;
            }
        }
        else{
            $return = false;
        }
        return $return;
    }

    public function createProfile($user)
    {

        $return = false;
        $alreadyExist = $this->connection->selectSingleQuery('SELECT * FROM t_user WHERE username=?', [$user->getUsername()]);
        if ($alreadyExist == false) {
            //Ce compte n'existe pas encore donc on peut le créer
            $query = 'INSERT INTO t_user (username, mail,name,firstname,password,picture) VALUES (?,?,?,?,?,?)';
            $params = [$user->getUsername(),$user->getMail(), $user->getName(), $user->getFirstname(), $user->getPassword(), $user->getPicture()];

            if ($this->connection->executeQuery($query, $params)) {
                $return = true;
            } else {
                $return = false;
            }

        } else {
            $return = 'alreadyExist';
        }

        return $return;
    }

    public function disconnect()
    {
    }

}


