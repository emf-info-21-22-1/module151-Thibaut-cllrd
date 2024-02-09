<?php
require_once('wrk/ConnexionDb.php');
require_once('models/Equipe.php');
class EquipesWrk
{
  private $connexion;
  public function __construct()
  {
    $this->connexion = ConnexionDb::getInstance();
    
  }


  public function getEquipesFromDB()
  {
    $rows = $this->connexion->selectQuery("select PK_equipe, Nom from t_equipe;", null);

    $equipes = array();
    foreach($rows as $row){
       $equipe = new Equipe($row['PK_equipe'],$row['Nom']);
       $equipes[] = $equipe;

    }
    return $equipes;
  }
}




?>