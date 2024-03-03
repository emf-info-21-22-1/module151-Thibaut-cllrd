<?php
require_once('services/ProfileService.php');
class ProfileCtrl
{

   private $session;
   private $profileService;
   public function __construct($session)
   {
      $this->session = $session;
      $this->profileService = new ProfileService();
   }

   public function createCar($start, $place, $direction, $comment)
   {
      if (!empty($start) && !empty($place) && !empty($direction) && !empty($comment)) {
         //Si la requete POST est complète
         if ($this->session->has("mail")) {
            //Si l'utilisateur est toujours connecté
            $result = $this->profileService->createCar($start, $place, $direction, $comment, $this->session->get('mail'));
            if ($result) {
               //L'opération s'est bien passée
               http_response_code(200);
            }
            elseif($result == 'alreadyHave'){
               //L'utilisateur a deja une voiture, conflict
               http_response_code(409);
            }
            else{
               http_response_code(500);
            }
         } else {
            //Si il n'est plus connecté
            http_response_code(401);
         }
      } else {
         //bad request il manque des infos dans le POST
         http_response_code(400);
      }




   }





}




?>