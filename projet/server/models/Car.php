<?php


class Voiture implements JsonSerializable
{
  private $pk;
  private $depart;
  private $place;
  private $direction;
  private $commentaire;
  private $fk_user;

  #Constructor
  public function __construct($pk, $depart, $place, $direction, $commentaire, $fk_user)
  {
    $this->pk = $pk;
    $this->depart = $depart;
    $this->place = $place;
    $this->direction = $direction;
    $this->commentaire = $commentaire;
    $this->fk_user = $fk_user;
    
  }

  // Getters
  // public function getPk(){
  //   return $this->pk;
  // }

  #Serialise la classe et retourne un Json
  public function jsonSerialize(): mixed
    {
        return [
            #Par exemple :
            #'pk' => $this->pk,
            
        ];
    }

}




?>