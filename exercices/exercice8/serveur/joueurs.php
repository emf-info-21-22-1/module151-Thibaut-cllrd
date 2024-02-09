<?php
include_once('ctrl/Ctrl.php');

$ctrl  = new Ctrl();



switch ($_GET['action']) {
    case 'equipe':
		echo $ctrl->getEquipes();
        break;
    case 'joueur':
		echo $ctrl->getJoueurs($_GET['equipeId']);
        break;
}

?>