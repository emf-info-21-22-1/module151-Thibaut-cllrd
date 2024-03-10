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

   /**
    * Retourne 200 si la voiture a été créé, sinon un code d'erreur.
    */
   public function createCar($start, $place, $direction, $comment)
   {
      if (!empty($start) && !empty($place) && !empty($direction) && !empty($comment)) {
         //Si la requete POST est complète
         if ($this->session->has("mail")) {
            //Si l'utilisateur est toujours connecté
            $result = $this->profileService->createCar($start, $place, $direction, $comment, $this->session->get('pkUser'));
            if ($result == 'ok') {
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

   /**
    * Retourne les infos de la voiture de l'utilisteur et sinon un code d'erreur.
    */
   public function getCarInfo()
   {
      //Verifier que l'utilisateur est toujours connecté
      if ($this->session->has('mail')) {
         $result = $this->profileService->getCarInfo($this->session->get('pkUser'));
         echo $result;
      } else {
         //L'utilisateur n'est pas connecté alors 401
         http_response_code(401);
      }
   }

   /**
    * Retourne tous les utilisateurs qui sont dans la voiture de l'utilisateur, sinon un code d'erreur.
    */
   public function getUserInCar(){
      if ($this->session->has('mail')) {
         $result = $this->profileService->getUserInCar($this->session->get('pkUser'));
         if($result == 'noCar'){
            http_response_code(404);
         }
         else{
            http_response_code(200);
            echo $result;
         }
      }
      else{
         http_response_code(401);
      }
   }

   /**
    * Retourne 200 si la voiture a été modifié, sinon un code d'erreur.
    */
   public function editCar($start, $place, $direction, $comment)
   {
      //Verifie que l'utilisateur est toujours connecté
      if ($this->session->has('mail')) {
         $result = $this->profileService->editCar($start, $place, $direction, $comment, $this->session->get('pkUser'));
         if ($result == 'ok') {
            //La modif s'est bien passée
            http_response_code(200);
         } elseif ($result == 'noChanges') {
            //Il n y a eu aucune modification car pas besoin succès quand même
            http_response_code(200);
         } elseif ($result == 'notHave') {
            //La voiture n a pas été trouvée
            http_response_code(404);
         } elseif ($result = 'timeError') {
            //422 Unprocessable Entity
            http_response_code(422);
         } else {
            //Autres problèmes techniques
            http_response_code(500);
         }
      } else {
         //L'utilisateur n'est pas connecté
         http_response_code(401);
      }
   }

   /**
    * Retourne 200 si la voiture a été supprimé, sinon un code d'erreur.
    */
   public function deleteCar()
   {
      if ($this->session->has('mail')) {
         $result = $this->profileService->deleteCar($this->session->get('pkUser'));
         if ($result == 'ok') {
            http_response_code(200);
         } elseif ($result == 'notHave') {
            http_response_code(404);
         } elseif ($result == 'timeError') {
            http_response_code(422);
         } else {
            http_response_code(500);
         }
      } else {
         http_response_code(401);
      }
   }

   /**
    * Retourne toutes les informations de l'utilisateur, sinon un code d'erreur.
    */
   public function getProfile()
   {
      if ($this->session->has('mail')){
         $result = $this->profileService->getProfile($this->session->get('pkUser'));
         if ($result) {
            http_response_code(200);
            echo $result;
         } else {
            http_response_code(500);
         }
      } else {
         http_response_code(401);
      }
   }

   /**
    * Retourne 200 si le profil a été modifié correctement, sinon un code d'erreur.
    */
   public function editProfile($name, $firstname, $password, $picture, $username)
   {
      $user = new User($username);
      $user->setName($name);
      $user->setFirstname($firstname);
      $user->setPassword($password);
      $user->setPicture($picture);
      if ($this->session->has('pkUser')) {
         $result = $this->profileService->editProfile($user, $this->session->get('pkUser'));
         if ($result == 'ok') {
            http_response_code(200);
         } elseif ($result == 'noChanges') {
            http_response_code(200);
         } else {
            http_response_code(500);
         }
      } else {
         http_response_code(401);
      }
   }

   /**
    * Retourne 200 si le profil a été supprimé, sinon un code d'erreur.
    */
   public function deleteProfile()
   {
      if ($this->session->has('pkUser')) {
         $result = $this->profileService->deleteProfile($this->session->get('pkUser'));
         if ($result == 'ok') {
            http_response_code(200);
            $this->session->clear();
         } elseif ($result == 'isDriver') {
            http_response_code(403);
         }  else {
            http_response_code(500);
         }
      } else {
         http_response_code(401);
      }
   }

   /**
    * Retourne 200 si l'utilisateur a quitté la voiture, sinon un code d'erreur.
    */
   public function leaveCar(){
      if ($this->session->has('mail')) {
         $result = $this->profileService->leaveCar($this->session->get('pkUser'));
         if ($result == 'ok') {
            http_response_code(200);
         }
         elseif( $result == 'noCar') {
            http_response_code(404);
         }
         else{
            http_response_code(500);
         }
      }
      else {
         http_response_code(401);
      }
   }

   /**
    * Déconnecte l'utilisateur et renvoie 200 (ou 204).
    */
   public function disconnect()
   {
      if ($this->session->has('mail')) {
         //L'utilisateur s'est déconnecté avec succès
         http_response_code(200);
         $this->session->clear();

      } else {
         //204 no content, l'utilisateur est déjà déconnecté
         http_response_code(204);
      }
   }


}
