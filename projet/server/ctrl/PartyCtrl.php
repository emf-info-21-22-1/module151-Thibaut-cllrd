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
            if ($this->session->has('party')) {
                $party = $this->session->get('party');
                if (!empty($party)) {
                    $result = $this->partyService->getParticipationsOf($party);
                    if (!empty($result)) {
                        //Les partys sont retournés sous la forme d'un json, tout s'est bien passé
                        http_response_code(200);
                        echo $result;
                    } else {
                        //Une erreur est survenue côté serveur
                        http_response_code(500);
                    }
                } else {
                    //La party est vide
                    http_response_code(400);
                }
            } else {
                //Si il n'est plus dans une party alors on ne trouve pas ce qu'il cherche error 404
                http_response_code(404);
            }
        } else {
            //Si il n'est plus connectà alors error 401
            http_response_code(401);
        }
    }

    public function joinCar($usernameToJoin)
    {
        if (!empty($usernameToJoin)) {
            if ($this->session->has('mail')) {
                if ($this->session->has('party')) {
                    //Si il est toujours dans une party
                    var_dump($this->session->get('inCarOf'));
                    if (!$this->session->has('inCarOf')) {
                        //Si il n'est pas déjà dans une voiture
                        $mailJoiner = $this->session->get('mail');
                        $result = $this->partyService->joinCar($usernameToJoin, $mailJoiner, $this->session->get('party'));
                        if ($result) {
                            //Si l'ajout à reussi alors
                            $this->session->set('inCarOf', $usernameToJoin);
                            http_response_code(200);

                        } else {
                            //sinon si il n a pas pu etre bien réalisé
                            http_response_code(500);
                        }
                    } else {
                        //Si il est déjà dans une voiture
                        //code 409 = conflict
                        http_response_code(409);
                    }
                } else {
                    //Si il n'est plus dans une party alors
                    http_response_code(404);
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





}




?>