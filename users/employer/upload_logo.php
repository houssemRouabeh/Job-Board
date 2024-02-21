<?php
session_start();
require('../../config/db_connection.php');

$id = $_SESSION['user_id'];

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
    // Emplacement où vous souhaitez enregistrer l'image
    $uploadDir = 'images/';
    $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);

    // Vérifiez si le fichier a été téléchargé avec succès
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
        // Mettez à jour le chemin de l'image dans la base de données
        $updateImageQuery = "UPDATE employer SET logo = ? WHERE id_user = ?";
        $stmt = $connection->prepare($updateImageQuery);

        if ($stmt) {
            $stmt->bind_param('si', $uploadFile, $id);
            $stmt->execute();
            $stmt->close();
        }
        header('location:profile.php');
    }
}
exit();
