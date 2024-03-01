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

    public function getParticipationsOf($party)
    {
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
    }

    public function joinCar($driver)
    {
        if (!empty($driver)) {
            if ($this->session->has('mail', $this->session)) {
                $mailUser = $this->session->get('mail');
                $result = $this->partyService->joinCar($driver, $mailUser);
            }
            else{
                //L'utilisateur n'est pas connecté
            }

        } else {
            //Le conducteur est vide
            http_response_code(400);
        }

    }





}




?>