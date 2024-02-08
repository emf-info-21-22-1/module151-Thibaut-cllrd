<?php

include_once('wrk.php');
class Ctrl
{
  private $wrk;
  public function __construct()
  {
    $this->wrk = new Wrk();
    
  }

  public function getEquipes()
  {
    // Récupérer les équipes depuis la base de données
    $equipes = $this->wrk->getEquipesFromDB();
    
    // Convertir le tableau d'objets Equipe en JSON
    $jsonEquipes = json_encode($equipes);
    
    // Retourner la chaîne JSON
    return $jsonEquipes;
  }
}


?>