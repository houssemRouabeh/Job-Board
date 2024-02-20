<?php
require('../../config/db_connection.php');

$id = $_GET['offerId'];

$sql = "DELETE FROM offer WHERE id_offer = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('location:offers.php');
} else {
    // Gérer les erreurs de préparation de la requête
    echo "Erreur de préparation de la requête.";
}


$connection->close();
