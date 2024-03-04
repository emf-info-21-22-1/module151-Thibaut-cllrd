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
            } elseif ($result == 'alreadyHave') {
               //L'utilisateur a deja une voiture, conflict
               http_response_code(409);
            } else {
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

   public function getCarInfo()
   {
      //Verifier que l'utilisateur est toujours connecté
      if ($this->session->has('mail')) {
         $mailUser = $this->session->get('mail');
         $result = $this->profileService->getCarInfo($mailUser);
         echo $result;
      } else {
         //L'utilisateur n'est pas connecté alors 401
         http_response_code(401);
      }
   }

   public function editCar($start, $place, $direction, $comment)
   {
      //Verifie que l'utilisateur est toujours connecté
      if ($this->session->has('mail')) {
         $mailUser = $this->session->get('mail');
         $result = $this->editCar($start, $place, $direction, $comment, $mailUser);
      } else {
         //L'utilisateur n'est pas connecté
         http_response_code(401);
      }
   }


}




?>