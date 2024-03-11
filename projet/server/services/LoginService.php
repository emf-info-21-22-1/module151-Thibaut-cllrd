<?php
require_once('ConnectionService.php');

class LoginService
{
    private $connection;

    public function __construct()
    {
        $this->connection = ConnectionService::getInstance();
    }


    /**
     * Retourne la PK de l'utilisateur si il a pu se connecter, sinon false.
     */
    public function checkLogin($user){
        $return = false;
        $userData = $this->connection->selectSingleQuery('SELECT * FROM t_user WHERE mail= ?', [$user->getMail()]);
        if ($userData) {
        $password = $userData['password'];
            if (password_verify($user->getPassword(), $password)) {
                $return = $userData['pk_user'];
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

    /**
     * Créé un compte utilisateur.
     * Retourne ok si il a été créé, alreadyExist si le compte existe déjà et false si il y a eu un autre problème.
     */
    public function createProfile($user)
    {

        $return = false;
        $alreadyExist = $this->connection->selectSingleQuery('SELECT username,mail FROM t_user WHERE username=? OR mail=?', [$user->getUsername(), $user->getMail()]);
        if($alreadyExist == false){
            //Ce compte n'existe pas encore donc on peut le créer
            $query = 'INSERT INTO t_user (username, mail,name,firstname,password,picture) VALUES (?,?,?,?,?,?)';
            $params = [$user->getUsername(),$user->getMail(), $user->getName(), $user->getFirstname(), $user->getPassword(), $user->getPicture()];

                $this->connection->executeQuery($query, $params);
                $pkUser = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE mail=?',[$user->getMail()]);
                $this->connection->executeQuery('INSERT INTO t_participation (fk_car, fk_user, fk_party) VALUES (?,?,?)',[null,$pkUser[0], 1]);

                if($this->connection->selectSingleQuery('SELECT * FROM t_user WHERE mail=?', [$user->getMail()])){
                $return = 'ok';
                }
             else {
                $return = false;
            }

        } else {
            $return = 'alreadyExist';
        }

        return $return;
    }


}


