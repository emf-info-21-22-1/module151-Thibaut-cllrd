<?php
//heberger sous /151/ 
//PROD
//header('Access-Control-Allow-Origin: https://colliardt.emf-informatique.ch/151/client/views');
//LAB
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
date_default_timezone_set('Europe/Paris');

require_once('ctrl/LoginCtrl.php');
require_once('ctrl/PartyCtrl.php');
require_once('ctrl/ProfileCtrl.php');
require_once('ctrl/SessionCtrl.php');
$sessionCtrl = new SessionCtrl();
$loginCtrl = new LoginCtrl($sessionCtrl);
$partyCtrl = new PartyCtrl($sessionCtrl);
$profileCtrl = new ProfileCtrl($sessionCtrl);

$putdata = file_get_contents("php://input");
parse_str($putdata, $_PUT);
parse_str($putdata, $_DELETE);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if ($_POST['action'] == "checkLogin") {
    $loginCtrl->checkLogin($_POST['mail'], $_POST['password']);
  }

  if ($_POST['action'] == "createProfile") {
    $username = $_POST['username'];
    $mail = $_POST['mail'];
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $password = $_POST['password'];


    if (isset($_POST['picture'])) {
      $loginCtrl->createProfile($username, $mail, $name, $firstname, $password, $_POST['picture']);
    } else {
      $loginCtrl->createProfile($username, $mail, $name, $firstname, $password, null);
    }

  }

  if ($_POST['action'] == 'joinCar') {
    
    $usernameToJoin = $_POST['username'];
    $partyCtrl->joinCar($usernameToJoin);
  }

  if ($_POST['action'] == 'createCar') {
    $start = $_POST['start'];
    $place = $_POST['place'];
    $direction = $_POST['direction'];
    $comment = $_POST['comment'];
    $profileCtrl->createCar($start, $place, $direction, $comment);
  }

  if($_POST['action'] == 'addCarParty'){
    $partyCtrl->addCarParty();
  }

  if ($_POST['action'] == "disconnect") {
    $profileCtrl->disconnect();
  }
}


if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  if ($_PUT['action'] == 'editCar') {
    $start = $_PUT['start'];
    $place = $_PUT['place'];
    $direction = $_PUT['direction'];
    $comment = $_PUT['comment'];
    $profileCtrl->editCar($start, $place, $direction, $comment);
  }

  if ($_PUT['action'] == 'editProfile') {
    $name = $_PUT['name'];
    $firstname = $_PUT['firstname'];
    $password = $_PUT['password'];
    $picture = $_PUT['picture'];
    $username = $_PUT['username'];

    $profileCtrl->editProfile($name, $firstname, $password, $picture, $username);
  }
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if ($_GET['action'] == "getParticipations") {
    $partyCtrl->getParticipationsOf();
  }

  if ($_GET['action'] == 'getCarInfo') {
    $profileCtrl->getCarInfo();
  }

  if ($_GET['action'] == 'getProfile') {
    $profileCtrl->getProfile();
  }

  if ($_GET['action'] == 'getUserInCar') {
    $profileCtrl->getUserInCar();
  }
}

//Ecouteur des requêtes DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  if ($_DELETE['action'] == 'deleteCar') {
    $profileCtrl->deleteCar();
  }

  //Appelé lorsque l'utilisateur veut retirer sa voiture de la soirée
  if ($_DELETE['action'] == 'removeCar') {
    $partyCtrl->removeCar();
  }

  if ($_DELETE['action'] == 'deleteProfile') {
    $profileCtrl->deleteProfile();
  }

  if ($_DELETE['action'] == 'leaveCar') {
    $profileCtrl->leaveCar();
  }

}

?>