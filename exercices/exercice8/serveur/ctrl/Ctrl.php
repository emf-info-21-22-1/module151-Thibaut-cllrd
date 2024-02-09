<?php

include_once('wrk/EquipesWrk.php');
include_once('wrk/JoueursWrk.php');
class Ctrl
{
  private $equipesWrk;
  private $joueursWrk;
  public function __construct()
  {
    $this->equipesWrk = new EquipesWrk();
    $this->joueursWrk = new JoueursWrk();
  }
  public function getEquipes()
  {
    // Récupérer les équipes depuis la base de données
    $equipes = $this->equipesWrk->getEquipesFromDB();

    // Convertir le tableau d'objets Equipe en JSON
    $jsonEquipes = json_encode($equipes);

    // Retourner la chaîne JSON
    return $jsonEquipes;
  }
  public function getJoueurs($equipeID)
  {
    // Récupérer les équipes depuis la base de données
    $joueurs = $this->joueursWrk->getJoueursFromDB($equipeID);

    // Convertir le tableau d'objets Equipe en JSON
    $jsonJoueurs = json_encode($joueurs);

    // Retourner la chaîne JSON
    return $jsonJoueurs;
  }
}


?>