<?php
include_once('wrk.php');
class Ctrl {
  private $wrk;

  public function __construct(Wrk $wrk) {
      $this->wrk = $wrk;
  }

  public function getEquipes() {
      
      return $this->wrk->getEquipesFromDB();
  }
}
?>