<?php
session_start();
require('../config/db_connection.php');

if (isset($_POST['signin'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Requête SQL pour vérifier l'utilisateur dans la base de données
    $sql = "SELECT * FROM users WHERE login = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Utilisateur trouvé dans la base de données
            $user = $result->fetch_assoc();

            // Vérifiez le mot de passe
            if (password_verify($password, $user['password'])) {
                // Mot de passe correct, connectez l'utilisateur
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['success_success'] = "Login success !";

                if ($user['id_role'] == 1) {
                    header("location:../users/admin/index.php");
                } elseif ($user['id_role'] == 2) {
                    header("location:../users/employer/index.php");
                } else {
                    header("location:../users/employee/index.php");
                }
            } else {
                // Mot de passe incorrect
                $_SESSION['alert_message'] = "Incorrect Credentials.";
                header("location:index.php");
            }
        } else {
            // Utilisateur non trouvé dans la base de données
            $_SESSION['alert_message'] = "Incorrect Credentials.";
            header("location:index.php");
        }

        $stmt->close();
    } else {
        // Erreur de préparation de la requête
        $_SESSION['alert_message'] = "Connexion error";
        header("location:index.php");
    }
}
