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

    public function editCar($start, $place, $direction, $comment, $mailUser)
    {
        $return = false;
        $pkUser = $this->connection->selectSingleQuery('SELECT pk_user FROM t_user WHERE mail=?', [$mailUser]);
        $carUser = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser[0]]);
        //Verifie si l'user a une voiture
        if ($carUser) {
            $participation = $this->connection->selectSingleQuery('SELECT * FROM t_participation WHERE fk_user=? AND fk_car=?', [$pkUser[0], $carUser['pk_car']]);
            $updates = [];
            $params = [];
            if (!empty($start)) {
                $updates[] = 'start=?';
                $params[] = $start;
            }
            if (!empty($place)) {
                $updates[] = 'place=?';
                $params[] = $place;
            }
            if (!empty($direction)) {
                $updates[] = 'direction=?';
                $params[] = $direction;
            }
            if (!empty($comment)) {
                $updates[] = 'comment=?';
                $params[] = $comment;
            }
            if (!empty($updates)) {
                //Si il y a des update a faire préparé la requête
                $setQuery = implode(', ', $updates);
                $params[] = $carUser['pk_car'];
                $query = "UPDATE t_car SET $setQuery WHERE pk_car=?";
                //Verifie si cette voiture est déjà entrée dans une soirée
                if ($participation) {
                    $startDateTime = new DateTime($carUser['start']);
                    $now = new DateTime();
                    $nowPlus30Min = clone $now;
                    $nowPlus30Min->modify('+30 minutes');
                    //Verifie si le vehicule part dans au moins 30 minutes
                    if ($startDateTime >= $nowPlus30Min) {
                        //Verifie si il y a un nouveau start et si il est dans au moins 10 minutes
                        if ($this->isStartTimeValid($start)) {
                            //L'heure de départ est dans au moins 30 minutes et le nouveau start est dans au moins 10 minutes
                            if ($this->connection->executeQuery($query, $params)) {
                                $return = true;
                            } else {
                                //Il y a eu une erreur alors
                                $return = false;
                            }
                        } elseif('noStart') {
                            //L'heure de départ est dans au moins 30 minutes et aucun nouveau start
                            if ($this->connection->executeQuery($query, $params)) {
                                $return = true;
                            } else {
                                //Il y a eu une erreur alors
                                $return = false;
                            }
                        }
                        elseif('timeError'){
                            //Il y a un nouveau start nais il ne respect pas la condition de temps
                            $return = 'timeError';
                        }
                    } else {
                        //Le départ est dans moins de 30 minutes alors pas de changement possible
                        $return = 'timeError';
                    }
                } else {
                    //Si elle n'est pas entrée dans une soirée
                    //Ajoute et vérifie que l'ajout a été fait
                    if ($this->connection->executeQuery($query, $params)) {
                        //L'update a bien été fait
                        $return = true;
                    } else {
                        //si il y a eu une erreur alors
                        $return = false;
                    }
                }
            } else {
                //Il n y a pas de modification
                $return = 'noChanges';
            }
        } else {
            //Il n a pas de voiture
            $return = 'notHave';
        }
        return $return;
    }
    private function isStartTimeValid($start)
    {
        $return = false;
        $now = new DateTime();
        $nowPlus10Min = clone $now;
        $nowPlus10Min->modify('+10 minutes');
        $startDateTime = new DateTime($start);


        if (!empty($start)) {
            if($startDateTime >= $nowPlus10Min){
                $return = true;
            }
            else{
                $return = 'timeError';
            }
        }
        else{
            $return = 'noStart';
        }

        return $return;
    }


}




