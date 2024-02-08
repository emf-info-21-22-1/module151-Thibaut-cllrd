<?php

include('models/Equipe.php');
class Wrk
{
  private $pdo;
  public function __construct()
  {
    try {
        $this->pdo = new PDO('mysql:host=mysql;port=3306;dbname=hockey_stats;charset=utf8', 'root', 'emf123');
        // set the PDO error mode to exception
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    
  }


  public function getEquipesFromDB()
  {
    $sqlQuery = 'SELECT * FROM t_equipe';
	$equipeStatement = $this->pdo->prepare($sqlQuery);
	$equipeStatement->execute();
    $rows = $equipeStatement->fetchAll();
    $equipes = array();
    foreach($rows as $row){
       $equipe = new Equipe($row['PK_equipe'],$row['Nom']);
       $equipes[] = $equipe;

    }
    return $equipes;
  }
}




?>