<?php


class Car implements JsonSerializable
{
  private $pk;
  private $start;
  private $place;
  private $direction;
  private $comment;
  private $fk_user;

  #Constructor
  public function __construct($fk_user)
  {
    $this->fk_user = $fk_user;

  }

  # Getters
  public function getPk()
  {
    return $this->pk;
  }

  public function getStart()
  {
    return $this->start;
  }

  public function getPlace()
  {
    return $this->place;
  }

  public function getDirection()
  {
    return $this->direction;
  }

  public function getComment()
  {
    return $this->comment;
  }

  public function getFkUser()
  {
    return $this->fk_user;
  }

  # Setters
  public function setPk($pk)
  {
    $this->pk = $pk;
  }

  public function setStart($start)
  {
    $this->start = $start;
  }

  public function setPlace($place)
  {
    $this->place = $place;
  }

  public function setDirection($direction)
  {
    $this->direction = $direction;
  }

  public function setComment($comment)
  {
    $this->comment = $comment;
  }

  public function setFkUser($fk_user)
  {
    $this->fk_user = $fk_user;
  }

  #Serialise la classe et retourne un Json
  public function jsonSerialize(): mixed
  {
    return [
      'start' => $this->start,
      'place' => $this->place,
      'direction' => $this->direction,
      'comment' => $this->comment

    ];
  }

}




?>