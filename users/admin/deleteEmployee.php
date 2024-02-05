<?php
require('../../config/db_connection.php');

$id = $_GET['employeeId'];

$sql = "DELETE FROM users WHERE id_user = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
} else {
    // Gérer les erreurs de préparation de la requête
    echo "Erreur de préparation de la requête.";
}
$sql = "DELETE FROM employee WHERE id_user = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('location:employee.php');
} else {
    // Gérer les erreurs de préparation de la requête
    echo "Erreur de préparation de la requête.";
}

$connection->close();
