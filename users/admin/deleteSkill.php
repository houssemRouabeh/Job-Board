<?php
require('../../config/db_connection.php');

$id = $_GET['adminId'];

$sql = "DELETE FROM skills WHERE id_skill = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('location:skills.php');
} else {
    // Gérer les erreurs de préparation de la requête
    echo "Erreur de préparation de la requête.";
}

$connection->close();
