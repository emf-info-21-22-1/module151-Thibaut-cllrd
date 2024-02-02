<?php
try {
    // Connexion à la base de données
    $bdd = new PDO('mysql:host=localhost;dbname=nomDB', 'root', 'pwd');
    // Assurez-vous que les erreurs sont lancées comme exceptions
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête pour sélectionner tous les titres des jeux vidéo
    $reponse = $bdd->query('SELECT titre FROM jeux_video');

    // Boucle pour afficher chaque titre de jeu vidéo
    while ($donnees = $reponse->fetch()) {
        echo $donnees['titre'] . '<br />';
    }

    // Fermeture du curseur d'analyse des résultats
    $reponse->closeCursor();
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
