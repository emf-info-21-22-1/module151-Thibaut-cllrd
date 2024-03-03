<?php
require_once('ConnectionService.php');
class ProfileService
{

    private $connection;
    public function __construct()
    {
        $this->connection = ConnectionService::getInstance();
    }

    function createCar($start, $place, $direction, $comment, $mailUser){
        $return = false;
        $pkUser = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE mail=?', [$mailUser]);   
          
        if(!$this->connection->selectQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser[0]])){
            //Si l'utilisateur n'a pas déjà une voiture
            
            if($this->connection->executeQuery('INSERT INTO t_car (start,place,direction,comment,fk_user) VALUES(?,?,?,?,?)', [$start, $place, $direction, $comment, $pkUser[0]])){
                //Si la voiture a bien été ajouté
                $return = true;
            }
            else{
                //Si elle n a pas bien été ajouté
                $return = false;
            }
        }
        else{
            //L'utilisateur a déjà une voiture
            $return = 'alreadyHave';
        }
        return $return;
    }





}




?>