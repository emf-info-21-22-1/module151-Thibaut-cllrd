<?php
require_once('ConnectionService.php');
require_once('models/Car.php');
class ProfileService
{

    private $connection;
    public function __construct()
    {
        $this->connection = ConnectionService::getInstance();
    }


    function createCar($start, $place, $direction, $comment, $mailUser)
    {
        $return = false;
        $pkUser = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE mail=?', [$mailUser]);

        if (!$this->connection->selectQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser[0]])) {
            //Si l'utilisateur n'a pas déjà une voiture

            if ($this->connection->executeQuery('INSERT INTO t_car (start,place,direction,comment,fk_user) VALUES(?,?,?,?,?)', [$start, $place, $direction, $comment, $pkUser[0]])) {
                //Si la voiture a bien été ajouté
                $return = true;
            } else {
                //Si elle n a pas bien été ajouté
                $return = false;
            }
        } else {
            //L'utilisateur a déjà une voiture
            $return = 'alreadyHave';
        }
        return $return;
    }

    public function getCarInfo($mailUser)
    {
        $return = false;
        $pkUser = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE mail=?', [$mailUser]);
        $carUser = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser[0]]);
        //Verifie que l'utilisateur a une voiture
        if ($carUser) {
            $start = $carUser['start'];
            $place = $carUser['place'];
            $direction = $carUser['direction'];
            $comment = $carUser['comment'];

            $car = new Car(null);
            $car->setStart($start);
            $car->setPlace($place);
            $car->setDirection($direction);
            $car->setComment($comment);

            $return = json_encode($car->jsonSerialize(), JSON_PRETTY_PRINT);
        } else {
            //Il n a pas de voiture
            return false;
        }

        return $return;
    }

    public function editCar($start, $place, $direction, $comment, $mailUser){
        $return = false;
        $pkUser = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE mail=?',[$mailUser]);
        $carUser = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?',[$pkUser[0]]);

        //Verifie si l'user a une voiture
        if ($carUser) {
            
        }
        else{
            //Il n a pas de voiture
        }



        return $return;
    }


}




?>