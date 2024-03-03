<?php
//heberger sous /151/ 

header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Credentials: true');

require_once('ctrl/LoginCtrl.php');
require_once('ctrl/PartyCtrl.php');
require_once('ctrl/ProfileCtrl.php');
require_once('ctrl/SessionCtrl.php');
$sessionCtrl = new SessionCtrl();
$loginCtrl = new LoginCtrl($sessionCtrl);
$partyCtrl = new PartyCtrl($sessionCtrl);
$profileCtrl = new ProfileCtrl($sessionCtrl);


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
    $sessionCtrl->set('mail', 'leo@example.com');
    $start = $_POST['start'];
    $place = $_POST['place'];
    $direction = $_POST['direction'];
    $comment = $_POST['comment'];
    $profileCtrl->createCar($start, $place, $direction, $comment);
  }



  if ($_POST['action'] == "disconnect") {


  }
}



if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if ($_GET['action'] == "getParticipations") {
    $partyCtrl->getParticipationsOf();
  }
}

?>