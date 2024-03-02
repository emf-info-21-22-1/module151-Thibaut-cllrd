<?php
require_once('ConnectionService.php');
require_once('models/Participation.php');
require_once('models/Car.php');
class PartyService
{

    private $connection;
    public function __construct()
    {
        $this->connection = ConnectionService::getInstance();
    }



    public function getParticipationsOf($party)
    {
        $return = false;
        $getPartyPk = $this->connection->selectSingleQuery('SELECT pk_party FROM t_party WHERE name=?', [$party]);
        if ($getPartyPk != false) {
            $query = 'SELECT u.pk_user, u.mail, u.picture, u.name, u.firstname, c.start, c.place, c.direction, c.comment, c.fk_user FROM t_participation AS p LEFT JOIN t_user AS u ON p.fk_user = u.pk_user LEFT JOIN t_car AS c ON p.fk_car = c.pk_car WHERE p.fk_party = ?';
            $theParam[] = $getPartyPk['pk_party'];
            //Retourne pour la party toutes les infos necessaire au retour
            $allParticipation = $this->connection->selectQuery($query, $theParam);
            //L'array qui va contenir tous les objets
            $final = [];
            foreach ($allParticipation as $row) {
                $pk_user = $row['pk_user'];
                $mail = $row['mail'];
                $picture = $row['picture'];
                $name = $row['name'];
                $firstname = $row['firstname'];
                $start = $row['start'];
                $place = $row['place'];
                $direction = $row['direction'];
                $comment = $row['comment'];
                $fk_user = $row['fk_user'];
                $driver = null;

                if ($pk_user == $fk_user) {
                    $driver = $name . " " . $firstname;
                }

                $user = new User($mail);
                $user->setPk($pk_user);
                $user->setName($name);
                $user->setFirstname($firstname);
                $user->setPicture($picture);


                $car = new Car($fk_user);
                $car->setStart($start);
                $car->setPlace($place);
                $car->setDirection($direction);
                $car->setComment($comment);

                $final[] = [
                    'user' => $user->jsonSerialize(),
                    'car' => $car->jsonSerialize(),
                    'driver' => $driver
                ];

            }
            $jsonFinal = json_encode($final);
            $return = $jsonFinal;




        } else {
            $return = 'unfound';
        }

        return $return;
    }


    public function joinCar($driver, $mailUser){
        $return = false;
        $query = 'SELECT ';
    }

}





?>