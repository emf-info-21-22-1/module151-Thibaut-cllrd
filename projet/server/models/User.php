<?php


class User implements JsonSerializable
{
  private $pk;
  private $name;
  private $mail;
  private $firstname;
  private $password;
  private $picture;

  private $username;

  private $pk_car;

  #Constructor
  public function __construct($username)
  {

    $this->username = $username;
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

  public function getUsername(){
    return $this->username;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getPicture()
  {
    return $this->picture;
  }

  public function getPk_car($pk_car){
    return $this->pk_car;
  }

  //setters
  public function setPk($pk)
  {
    $this->pk = $pk;
  }

  public function setUsername($username){
    $this->username = $username;
  }

  public function setMail($mail){
    $this->mail = $mail;}

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

  public function setPk_car($pk_car){
    $this->pk_car = $pk_car;
  }

  //Serialise la classe et retourne un Json
  public function jsonSerialize(): mixed
  {
    return [
      'username' => $this->username,
      'mail' => $this->mail,
      'picture' => $this->picture,
      'name' => $this->name,
      'firstname' => $this->firstname,
      'pkCar' => $this->pk_car
    ];
  }

}




?>