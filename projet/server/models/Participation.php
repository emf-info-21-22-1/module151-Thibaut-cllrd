<?php


class Participation implements JsonSerializable
{
  private $pk;
  private $isConducteur;
  private $fk_voiture;
  private $fk_user;
  private $fk_fete;

  #Constructor
  public function __construct($pk, $isConducteur, $fk_voiture, $fk_user, $fk_fete)
  {
    $this->pk = $pk;
    $this->isConducteur = $isConducteur;
    $this->fk_voiture = $fk_voiture;
    $this->fk_user = $fk_user;
    $this->fk_fete = $fk_fete;
    
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