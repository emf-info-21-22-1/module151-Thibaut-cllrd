<?php


class Participation implements JsonSerializable
{
  private $pk;
  
  private $fk_car;
  private $fk_user;
  private $fk_party;

  #Constructor
  public function __construct($pk, $fk_car, $fk_user, $fk_party)
  {
    $this->pk = $pk;
    $this->fk_car = $fk_car;
    $this->fk_user = $fk_user;
    $this->fk_party = $fk_party;
    
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