<?php


class User implements JsonSerializable
{
  private $pk;
  private $name;
  private $mail;
  private $firstname;
  private $password;
  private $picture;

  #Constructor
  public function __construct($mail)
  {

    $this->mail = $mail;
    $this->picture = null;

  }




  //Getters
  public function getName()
  {
    return $this->name;
  }

  public function getFirstname()
  {
    return $this->firstname;
  }

  public function getMail()
  {
    return $this->mail;
  }

  public function getPk()
  {
    return $this->pk;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getPicture()
  {
    return $this->picture;
  }

  //setters
  public function setPk($pk)
  {
    $this->pk = $pk;
  }

  public function setName($name)
  {
    $this->name = $name;
  }
  public function setFirstname($firstname)
  {
    $this->firstname = $firstname;
  }

  public function setPicture($picture)
  {
    $this->picture = $picture;
  }

  public function setPassword($password){
    $this->password = $password;
  }

  //Serialise la classe et retourne un Json
  public function jsonSerialize(): mixed
  {
    return [
      #Par exemple :
      'pk' => $this->pk,
      'mail' => $this->mail,
      'picture' => $this->picture,
      'name' => $this->name,
      'firstname' => $this->firstname,
      
    ];
  }

}




?>