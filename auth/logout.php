<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // Détruit toutes les variables de session
    $_SESSION = array();

    // Détruit la session
    session_destroy();
}

// Redirige vers la page de connexion ou une autre page de votre choix
header('Location: ../index.php');
exit();
