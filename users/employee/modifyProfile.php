<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Update personal details in the 'employee' table
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $city = $_POST['city'];
    $postalCode = $_POST['cp'];
    $address = $_POST['adress'];
    $birthDate = $_POST['birthDate'];
    $phone = $_POST['phone'];

    $updateEmployeeSql = "UPDATE employee SET 
        firstName = ?, 
        lastName = ?, 
        city = ?, 
        postal_code = ?, 
        adress = ?, 
        birthDate = ?, 
        phone = ?
        WHERE id_user = ?";

    $stmtEmployee = $connection->prepare($updateEmployeeSql);

    if ($stmtEmployee) {
        $stmtEmployee->bind_param('sssssssi', $firstName, $lastName, $city, $postalCode, $address, $birthDate, $phone, $id);
        $stmtEmployee->execute();
    }

    // Prepare statements for education and experience
    $insertEducationSql = "INSERT INTO education (degree_type, field_of_study, institution, graduation_year, additional_notes, id_user) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtEducation = $connection->prepare($insertEducationSql);

    $insertExperienceSql = "INSERT INTO experience ( job_title, company, start_date, end_date, achievements,id_user) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtExperience = $connection->prepare($insertExperienceSql);

    // Iterate over $_POST and check for education and experience fields
    foreach ($_POST as $key => $value) {
        // Check if the field is related to education
        if (strpos($key, 'degree_type_') === 0) {
            // Process education data
            $educationCounter = substr($key, strlen('degree_type_'));

            $fieldOfStudy = $_POST['field_of_study_' . $educationCounter];
            $institution = $_POST['institution_' . $educationCounter];
            $graduationYear = $_POST['graduation_year_' . $educationCounter];
            $additionalNotes = $_POST['additional_notes_' . $educationCounter];

            // Insert education data into the database
            $stmtEducation->bind_param('sssisi', $_POST[$key], $fieldOfStudy, $institution, $graduationYear, $additionalNotes, $id);
            if (!$stmtEducation->execute()) {
                echo "Error in education insert: " . $stmtEducation->error;
            }
        }

        // Check if the field is related to experience
        if (strpos($key, 'job_title_') === 0) {
            // Process experience data
            $experienceCounter = substr($key, strlen('job_title_'));

            $job_title = $_POST['job_title_' . $experienceCounter];
            $employer = $_POST['employer_' . $experienceCounter];
            $startDate = $_POST['start_date_' . $experienceCounter];
            $endDate = $_POST['end_date_' . $experienceCounter];
            $jobDescription = $_POST['job_description_' . $experienceCounter];

            // Insert experience data into the database
            $stmtExperience->bind_param('sssssi', $job_title, $employer, $startDate, $endDate, $jobDescription, $id);
            if (!$stmtExperience->execute()) {
                echo "Error in experience insert: " . $stmtExperience->error;
            }
        }
    }

    // Reset the statements after processing education and experience details
    $stmtEducation->close();
    $stmtEducation = $connection->prepare($insertEducationSql);

    $stmtExperience->close();
    $stmtExperience = $connection->prepare($insertExperienceSql);
    header("location:profile.php");
}
