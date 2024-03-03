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
            $query = 'SELECT u.username, u.mail, u.picture, c.start, c.place, c.direction, c.comment FROM t_participation p JOIN t_car c ON p.fk_car = c.pk_car JOIN t_user u ON c.fk_user = u.pk_user WHERE p.fk_party = ? GROUP BY c.pk_car';
            $theParam[] = $getPartyPk['pk_party'];
            //Retourne pour la party toutes les infos necessaire au retour
            $allParticipation = $this->connection->selectQuery($query, $theParam);

            $allAvailableSeats = $this->connection->selectQuery('SELECT c.pk_car, u.username, c.place AS total_places, c.place - COALESCE(p.participant_count, 0) AS available_seats FROM t_car AS c JOIN t_user AS u ON c.fk_user = u.pk_user LEFT JOIN (SELECT fk_car, COUNT(*) - 1 AS participant_count FROM t_participation WHERE fk_party = ? GROUP BY fk_car) AS p ON c.pk_car = p.fk_car', $theParam);
            //L'array qui va contenir tous les objets json a retourner
            $final = [];
            foreach ($allParticipation as $row) {
                //Recupération des données de la requete
                $picture = $row['picture'];
                $username = $row['username'];
                $start = $row['start'];
                $place = $row['place'];
                $direction = $row['direction'];
                $comment = $row['comment'];
                
                //Creation user et voiture pour utiliser le json_encode
                $user = new User($username);
                $user->setPicture($picture);
                $car = new Car(null);
                $car->setStart($start);
                $car->setPlace($place);
                $car->setDirection($direction);
                $car->setComment($comment);

                $availableSeats = -1;
                foreach ($allAvailableSeats as $carRow) {
                    if($carRow['username'] == $username){
                        $availableSeats = $carRow['available_seats'];
                        break;
                    }
                }
                $final[] = [
                    'user' => $user->jsonSerialize(),
                    'car' => $car->jsonSerialize(),
                    'availableSeats'=>$availableSeats
                ];
            }
            $jsonFinal = json_encode(['participations' => $final], JSON_PRETTY_PRINT);
            $return = $jsonFinal;
        } else {
            $return = 'unfound';
        }
        return $return;
    }


    public function joinCar($usernameToJoin, $mailJoiner){
        $return = false;
        
        $pkUserToJoin = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE username=?', [$usernameToJoin]);
        $pkJoiner = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE mail=?', [$mailJoiner]);

        $participationExist = $this->connection->selectSingleQuery('SELECT p.* FROM t_participation AS p JOIN t_car AS c ON p.fk_car = c.pk_car WHERE c.fk_user = ? AND p.fk_user = c.fk_user', [$pkUserToJoin]);
        if($participationExist){
            //Si la participation a rejoindre existe
            $fkCarToJoin = $participationExist['fk_car'];
            $nbUsersInCar = $this->connection->selectSingleQuery('SELECT COUNT(*) - 1 AS nbUsersInCar FROM t_participation WHERE fk_car = 1 AND fk_party=?',[$fkCarToJoin]);
            $nbPlacesInCar = $this->connection->selectSingleQuery('SELECT place FROM t_car WHERE pk_car=?',[$fkCarToJoin]);
            $availableSeats = $nbPlacesInCar - $nbUsersInCar;
            if($availableSeats > 0){
                //Si il reste de la place alors créer une entrée dans t_participation
                
            }
        }
        else{
            //La voiture qu'il veut rejoindre n'existe pas
            $return = false;
        }

    }


}





