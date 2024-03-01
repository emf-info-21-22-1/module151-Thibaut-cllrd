<?php


class Party implements JsonSerializable
{
  private $pk;
  private $nom;
  private $dateDepart;
  private $dateFin;
  private $description;

  #Constructor
  public function __construct($pk, $nom, $dateDepart, $dateFin, $description)
  {
    $this->pk = $pk;
    $this->nom = $nom;
    $this->dateDepart = $dateDepart;
    $this->dateFin = $dateFin;
    $this->description = $description;
    
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