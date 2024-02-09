<?php


class Equipe implements JsonSerializable
{
  private $pk;
  private $nom;
  public function __construct($pk, $nom)
  {
    $this->pk = $pk;
    $this->nom = $nom;
  }

  public function getNom(){
    return $this->nom;
  }
  public function getPk(){
    return $this->pk;
  }

  public function jsonSerialize(): mixed
    {
        return [
            'pk' => $this->pk,
            'nom' => $this->nom,
        ];
    }

}




?>