<?php
require_once('services/PartyService.php');
class PartyCtrl
{
    private $partyService;
    private $session;

    public function __construct($session)
    {
        $this->partyService = new PartyService();
        $this->session = $session;
    }

    public function getParticipationsOf()
    {
        //Check si l'utilisateur est toujours connecté
        if ($this->session->has('mail')) {
            //Si il est dans une party alors essayer de lui envoyer les véhicules dispo de la party
            $result = $this->partyService->getParticipationsOf($this->session->get('pkUser'));
            if ($result == 'unfound') {
                //Il n'est pas dans une party
                http_response_code(404);
            } elseif (empty($result)) {
                //Une erreur est survenue côté serveur
                http_response_code(500);
            } elseif ($result) {
                //Json envoyé!
                http_response_code(200);
                echo $result;
            }
        } else {
            //Si il n'est plus connecté alors error 401
            http_response_code(401);
        }
    }

    public function joinCar($usernameToJoin)
    {
        if (!empty($usernameToJoin)) {
            if ($this->session->has('mail')) {
                //Si il n'est pas déjà dans une voiture
                $pkUser = $this->session->get('pkUser');
                $result = $this->partyService->joinCar($usernameToJoin, $pkUser);
                if ($result == 'ok') {
                    //Ajout réussi
                    http_response_code(200);
                } elseif ($result == 'notSameParty') {
                    //Pas dans la meme soirée
                    http_response_code(403);
                } elseif ($result == 'carNotExist') {
                    //La voiture a rejoindre n'existe pas
                    http_response_code(404);
                } elseif ($result == 'alreadyInCar') {
                    //L'utilisateur est déjà dans une voiture
                    http_response_code(409);
                } elseif ($result == 'notInParty') {
                    //Pas dans une soirée
                    http_response_code(403);
                } else {
                    //sinon si il n a pas pu etre bien réalisé
                    http_response_code(500);
                }
            } else {
                //L'utilisateur n'est pas connecté
                http_response_code(401);
            }
        } else {
            //Le conducteur est vide
            http_response_code(400);
        }
    }

    public function addCarParty(){
        if($this->session->has('mail')){
            //L'utilisateur est toujours connecté
            $result = $this->partyService->addCarParty($this->session->get('pkUser'));
            if ($result == 'ok') {
                //L'opération s'est bien passée
                http_response_code(200);
            }
            elseif ($result == 'noCar') {
                //L'utilisateur n'a pas de voiture
                http_response_code(404);
            }
            elseif( $result == 'alreadyInCar') {
                //L'utilisateur est déjà dans une voiture
                http_response_code(409);
            }
            elseif($result == 'notInParty') {
                //L'utilisateur n'est pas dans une party
                http_response_code(404);
            }
            else{
                //Probleme serveur
                http_response_code(500);
            }
        }
        else{
            //L'utilisateur n'est plus connecté
            http_response_code(401);
        }
    }

    public function removeCar()
    {
        if (!empty($this->session->get('mail'))) {
            $pkUser = $this->session->get('pkUser');
            $result = $this->partyService->removeCar($pkUser);
            if ($result == 'ok') {
                //Tout s'est bien passé
                http_response_code(200);
            } elseif ($result == 'errorTime') {
                //Les temps obligatoires sont dépassés
                http_response_code(422);
            } elseif ($result == 'notInParty') {
                //La voitures n'est pas dans une party
                //409 = conflict                
                http_response_code(409);
            } elseif ($result == 'notHaveCar') {
                //L'utilisateur n'a pas de voiture
                http_response_code(404);
            } else {
                //Autre erreur technique
                http_response_code(500);
            }
        } else {
            http_response_code(401);
        }
    }



}



