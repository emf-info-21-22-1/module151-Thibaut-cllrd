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


    /**
     * Retourne ok si la voiture a été créé, sinon un message d'erreur ou false si problème serveur.
     */
    function createCar($start, $place, $direction, $comment, $pkUser)
    {
        $return = false;

        if (!$this->connection->selectQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser])) {
            //Si l'utilisateur n'a pas déjà une voiture

            if ($this->connection->executeQuery('INSERT INTO t_car (start,place,direction,comment,fk_user) VALUES(?,?,?,?,?)', [$start, $place, $direction, $comment, $pkUser])) {
                //Si la voiture a bien été ajouté
                $return = 'ok';
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

    /**
     * Retourne un JSON d'infos de la voiture ou false.
     */
    public function getCarInfo($pkUser)
    {
        $return = false;
        $carUser = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser]);
        $pk_party = $this->connection->selectSingleQuery('SELECT fk_party FROM t_participation WHERE fk_car=?', [$carUser['pk_car']]);
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
            $car->setInParty(null);
            if ($pk_party) {
                $car->setInParty(true);
            }

            $return = json_encode($car->jsonSerialize(), JSON_PRETTY_PRINT);
        } else {
            //Il n a pas de voiture
            return false;
        }

        return $return;
    }

    /**
     * Retourne un JSON de username qui sont dans la voiture de l'utilisateur, sinon un message d'erreur.
     */
    public function getUserInCar($pkUser)
    {
        $return = false;
        $carUser = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser]);
        if ($carUser) {
            $pkUsersInCar = $this->connection->selectQuery('SELECT fk_user FROM t_participation WHERE fk_car=? ', [$carUser['pk_car']]);
            $usersInCar = [];
            foreach ($pkUsersInCar as $thisUser) {

                if ($thisUser[0] != $pkUser) {
                    $username = $this->connection->selectSingleQuery('SELECT username FROM t_user WHERE pk_user=?', [$thisUser[0]]);
                    $usersInCar[] = $username[0];
                }
            }
            $return = json_encode($usersInCar, JSON_PRETTY_PRINT);
        } else {
            $return = 'noCar';
        }
        return $return;
    }

    /**
     * Retourne ok si la voiture a été modifié, sinon un message d'erreur ou false si problème serveur.
     */
    public function editCar($start, $place, $direction, $comment, $pkUser)
    {
        $return = false;
        $carUser = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser]);
        //Verifie si l'user a une voiture
        if ($carUser) {
            $participation = $this->connection->selectSingleQuery('SELECT * FROM t_participation WHERE fk_user=?', [$pkUser]);
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
                                $return = 'ok';
                            } else {
                                //Il y a eu une erreur alors
                                $return = false;
                            }
                        } elseif ('noStart') {
                            //L'heure de départ est dans au moins 30 minutes et aucun nouveau start
                            if ($this->connection->executeQuery($query, $params)) {
                                $return = true;
                            } else {
                                //Il y a eu une erreur alors
                                $return = false;
                            }
                        } elseif ('timeError') {
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
    //Cette fonction test si start de la voiture est valide et si il est dans au moins 10 minutes
    //Retourn true si oui, 'timeError' si c'est moins de 10 minutes et 'noStart' si start est vide
    private function isStartTimeValid($start)
    {
        $return = false;
        $now = new DateTime();
        $nowPlus10Min = clone $now;
        $nowPlus10Min->modify('+10 minutes');
        $startDateTime = new DateTime($start);


        if (!empty($start)) {
            if ($startDateTime >= $nowPlus10Min) {
                $return = true;
            } else {
                $return = 'timeError';
            }
        } else {
            $return = 'noStart';
        }

        return $return;
    }

    /**
     * Retourne ok si la voiture a été supprimé, sinon un message d'erreur ou false si problème serveur.
     */
    public function deleteCar($pkUser)
    {
        $return = false;
        $carOfUser = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser]);
        if ($carOfUser) {
            //L'utilisateur a une voiture
            $participationsOfCar = $this->connection->selectQuery('SELECT * FROM t_participation WHERE fk_car=?', [$carOfUser['pk_car']]);
            if ($participationsOfCar) {
                //la voiture est dans disponible dans la fête
                $start = new DateTime($carOfUser['start']);
                $now = new DateTime();
                $nowPlus30Min = clone $now;
                $nowPlus30Min->modify('+30 minutes');
                if ($start >= $nowPlus30Min) {
                    //La voiture part dans au moins 30 minutes donc ok
                    if ($this->connection->executeQuery('DELETE FROM t_participation WHERE fk_car=?', [$carOfUser['pk_car']])) {
                        if ($this->connection->executeQuery('DELETE FROM t_car WHERE fk_user=?', [$pkUser])) {
                            //La voiture a été supprimée
                            $return = 'ok';
                        } else {
                            //erreur lors de la suppresion de la voiture
                            $return = false;
                        }
                    } else {
                        //erreur de suppression de ou des participation(s) de cette voiture
                        $return = false;
                    }

                } else {
                    //La voiture part dans moins de 30 minutes donc pas possible
                    $return = 'errorTime';
                }
            } else {
                //La voiture n'est pas dans une fête
                if ($this->connection->executeQuery('DELETE FROM t_car WHERE fk_user=?', [$pkUser])) {
                    //La voiture a été supprimée
                    $return = true;
                } else {
                    //erreur lors de la suppresion
                    $return = false;
                }
            }
        } else {
            $return = 'notHave';
        }
        return $return;
    }

    /**
     * Retourne un JSON avec les infos de l'utilisateur, sinon un message d'erreur ou false si problème serveur.
     */
    public function getProfile($pkUser)
    {
        $return = false;
        $profile = $this->connection->selectSingleQuery('SELECT * FROM t_user WHERE pk_user=?', [$pkUser]);
        if ($profile) {
            $user = new User($profile['username']);
            $user->setMail($profile['mail']);
            $user->setPicture(base64_encode($profile['picture']));
            $user->setName($profile['name']);
            $user->setFirstname($profile['firstname']);
            $user->setPk_car(null);
            $pkCar = $this->connection->selectSingleQuery('SELECT pk_car FROM t_car WHERE fk_user=?', [$pkUser]);
            if ($pkCar) {
                $user->setPk_car($pkCar[0]);
            }
            $return = json_encode($user->jsonSerialize());
        } else {
            $return = false;
        }
        return $return;
    }

    /**
     * Retourne ok si l'utilisateur a été modifié, sinon un message d'erreur ou false si problème serveur.
     */
    public function editProfile($newUser, $pkUser)
    {
        $return = false;
        $name = $newUser->getName();
        $firstname = $newUser->getFirstName();
        $password = $newUser->getPassword();
        $picture = $newUser->getPicture();
        $username = $newUser->getUsername();

        $updates = [];
        $params = [];
        if (!empty($name)) {
            $updates[] = 'name=?';
            $params[] = $name;
        }
        if (!empty($firstname)) {
            $updates[] = 'firstname=?';
            $params[] = $firstname;
        }
        if (!empty($password)) {
            $updates[] = 'password=?';
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }
        if (!empty($picture)) {
            $updates[] = 'picture=?';
            $params[] = base64_decode($picture);
        }
        if (!empty($username)) {
            $updates[] = 'username=?';
            $params[] = $username;
        }


        try {
            if ($updates) {
                $setQuery = implode(', ', $updates);
                $params[] = $pkUser;
                $request = $this->connection->executeQuery("UPDATE t_user SET $setQuery WHERE pk_user=?", $params);
                if ($request) {
                    var_dump($request);
                    $return = 'ok';
                } else {
                    $return = false;
                }
            } else {
                $return = 'noChanges';
            }
        } catch (\Exception $e) {
            $return = false;
        }

        return $return;
    }

    /**
     * Retourne ok si la voiture a été quitté, sinon un message d'erreur ou false si problème serveur.
     */
    public function leaveCar($pkUser){
        $userCar = $this->connection->selectSingleQuery('SELECT pk_car FROM t_car WHERE fk_user=?', [$pkUser]);
        $carToLeave = $this->connection->selectSingleQuery('SELECT fk_car FROM t_participation WHERE fk_user=? AND fk_car IS NOT NULL AND NOT (fk_car=?)',[$pkUser, $userCar[0]]);
        if ($carToLeave) {
            //L'utilisateur est bien dans une voiture
            $this->connection->executeQuery('DELETE FROM t_participation WHERE fk_user=? AND fk_car=?', [$pkUser,$carToLeave[0]]);
            if($this->connection->selectSingleQuery('SELECT * FROM t_participation WHERE fk_user=? AND fk_car=?', [$pkUser,$carToLeave[0]])){
                //Tout s'est bien passé
                return 'ok';
            }
            else{
                return false;
            }
        }
        else{
            //L'utilisateur n'est pas dans une voiture
            return 'noCar';
        }
    }

    /**
     * Retourne ok si le profil a été supprimé, sinon un message d'erreur ou false si problème serveur.
     */
    public function deleteProfile($pkUser)
    {
        $return = false;
        $carUSer = $this->connection->selectSingleQuery('SELECT * FROM t_car WHERE fk_user=?', [$pkUser]);
        $isDriver = $this->connection->selectSingleQuery('SELECT * FROM t_participation WHERE fk_user=? AND fk_car IS NOT NULL', [$pkUser]);
        if ($carUSer) {
            //L'utilisateur possède une voiture
            $isDriver = $this->connection->selectSingleQuery('SELECT * FROM t_participation WHERE fk_user=? AND fk_car=?', [$pkUser, $carUSer['pk_car']]);
            if (!$isDriver) {
                //Pas conducteur donc ok
                //Suppression de sa trace dans les soirées
                $this->connection->executeQuery('DELETE FROM t_participation WHERE fk_user=?', [$pkUser]);
                if (!$this->connection->selectSingleQuery('SELECT * FROM t_participation WHERE fk_user=?', [$pkUser])) {
                    //L'utilisateur n'apparait plus dans les soirées
                    //Suppression de l'utilisateur
                    $this->connection->executeQuery('DELETE FROM t_user WHERE fk_user=?', [$pkUser]);
                    if (!$this->connection->selectSingleQuery('SELECT * FROM t_user WHERE pk_user=?', [$pkUser])) {
                        //L'utilisateur a bien été supprimé
                        $return = 'ok';
                    } else {
                        //Probleme lors de la suppresion
                        $return = false;
                    }
                } else {
                    //Probleme lors de la suppression
                    $return = false;
                }
            } else {
                //L'utilisateur est conducteur donc impossible
                $return = 'isDriver';
            }
        } else {
            //L'utilsateur ne possède pas de voiture
            //Suppression de sa trace dans les soirées
            $this->connection->executeQuery('DELETE FROM t_participation WHERE fk_user=?', [$pkUser]);
            if (!$this->connection->selectSingleQuery('SELECT * FROM t_participation WHERE fk_user=?', [$pkUser])) {
                //L'utilisateur n'apparait plus dans les soirées
                //Suppression de l'utilisateur
                $this->connection->executeQuery('DELETE FROM t_user WHERE pk_user=?', [$pkUser]);
                if (!$this->connection->selectSingleQuery('SELECT * FROM t_user WHERE pk_user=?', [$pkUser])) {
                    //L'utilisateur a bien été supprimé
                    $return = 'ok';
                } else {
                    //Probleme lors de la suppresion
                    $return = false;
                }
            } else {
                //Probleme lors de la suppression
                $return = false;
            }


        }

        return $return;
    }
}




