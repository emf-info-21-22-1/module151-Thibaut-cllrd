<?php

include('models/Joueur.php');
require_once('wrk/ConnexionDb.php');
class JoueursWrk
{
  private $connexion;
  public function __construct()
  {
    $this->connexion = ConnexionDb::getInstance();
  }


  public function getJoueursFromDB($equipeId)
  {
    $query = $this->connexion->selectQuery('select pk_joueur, nom, points from t_joueur where fk_equipe=' . $equipeId . ';', null);
    $joueurs = array();
    foreach($query as $row){
       $joueur = new Joueur($row['PK_equipe'],$row['Nom'],$row['points']);
       $joueurs[] = $joueur;

    }
    return $joueurs;
  }
}




?>