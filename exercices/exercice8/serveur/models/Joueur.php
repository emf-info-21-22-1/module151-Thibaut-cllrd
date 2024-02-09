<?php
class Joueur implements JsonSerializable
{
  private $id;
  private $nom;
  private $points;
  
  public function __construct($id, $nom, $points){
    $this->id = $id;
    $this->nom = $nom;
    $this->points = $points;
  }
  
  public function getId(){
    return $this->id;
  }
  
  public function getNom(){
    return $this->nom;
  }
  
  public function getPoints(){
    return $this->points;
  }

  public function jsonSerialize(): mixed
  {
      return [
          'id' => $this->id,
          'nom' => $this->nom,
          'points' => $this->points,
      ];
  }
}
?>