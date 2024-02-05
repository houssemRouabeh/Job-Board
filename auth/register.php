
<?php
session_start();
require('../config/db_connection.php');

$dateDuJour = new DateTime();
$createdAt = $dateDuJour->format("Y-m-d");

// Function to check if a user with the given login already exists
function userExists($login, $connection)
{
    $sql = "SELECT COUNT(*) as count FROM users WHERE login = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

        return $count > 0;
    } else {
        return false;
    }
}

// Verification si employé enregistrement
if (isset($_POST['employeeSubmit'])) {
    $login = $_POST['email'];

    // Check if the user already exists
    if (userExists($login, $connection)) {
        // User already exists, handle accordingly (e.g., display an error message)
        $_SESSION['alert_message'] =  "User with this email already exists.";
        header("location:index.php");
    } else {
        // Continue with user registration
        $sql = "INSERT INTO users (login, password, id_role) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);

        if ($stmt) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $idRole = '3'; // Rôle par défaut Employé

            $stmt->bind_param("ssi", $login, $password, $idRole);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Requête SQL préparée pour l'insertion
                $sql = "INSERT INTO employee (register_date, id_user) VALUES (?, ?)";
                $stmt2 = $connection->prepare($sql);
                if ($stmt2) {
                    // Stockage de valeurs dans des variables temporaires
                    $idUser = $stmt->insert_id;   //id de l'utilisateur inseré

                    // Liaison des valeurs
                    $stmt2->bind_param("si", $createdAt, $idUser);

                    // Exécution de la requête
                    $stmt2->execute();
                    // Vérification si l'insertion a réussi
                    if ($stmt2->affected_rows > 0) {
                        $_SESSION['success_message'] = "Employer account has been created successfully.";
                        //redirection vers login
                        header("location:index.php");
                    }
                }
            } else {
                echo "Erreur lors de l'insertion du user.";
            }

            $stmt->close();
        } else {
            echo "Erreur lors de la préparation de la requête : " . $connection->error;
        }
    }
} else {
    $login = $_POST['login'];
    if (userExists($login, $connection)) {
        // User already exists, handle accordingly (e.g., display an error message)
        $_SESSION['alert_message'] =  "User with this email already exists.";
        header("location:index.php");
    } else {
        // Requête SQL préparée pour l'insertion
        $sql = "INSERT INTO users (login, password, id_role) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);

        // Vérification si la préparation a réussi
        if ($stmt) {
            // Stockage de valeurs dans des variables temporaires
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $idRole = '2';   //Rôle par défaut Employé

            // Liaison des valeurs
            $stmt->bind_param("ssi", $login, $password, $idRole);

            // Exécution de la requête
            $stmt->execute();

            // Vérification si l'insertion a réussi
            if ($stmt->affected_rows > 0) {
                // Requête SQL préparée pour l'insertion
                $sql = "INSERT INTO employer (company_name, industry,register_date, id_user) VALUES (?, ?,?, ?)";
                $stmt2 = $connection->prepare($sql);
                if ($stmt2) {
                    // Stockage de valeurs dans des variables temporaires
                    $company_name = $_POST['name'];
                    $industry = $_POST['industry'];
                    $idUser = $stmt->insert_id;   //Rôle par défaut Employé

                    // Liaison des valeurs
                    $stmt2->bind_param("sssi", $company_name, $industry, $createdAt, $idUser);

                    // Exécution de la requête
                    $stmt2->execute();
                    // Vérification si l'insertion a réussi
                    if ($stmt2->affected_rows > 0) {
                        $_SESSION['success_message'] = "Employer account has been created successfully.";
                        //redirection vers login
                        header("location:index.php");
                    }
                }
            } else {
                echo "Erreur lors de l'insertion du user.";
            }

            // Fermeture de la requête préparée
            $stmt->close();
        } else {
            echo "Erreur lors de la préparation de la requête : " . $connection->error;
        }
    }
}
