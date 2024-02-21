<?php
session_start();
require('../../config/db_connection.php');
$user_id = $_SESSION['user_id'];
$id_offer = $_GET['offer_id'];
$offer_title = $_GET['offer_name'];

// Requête SQL pour sauvegarder job application
$sql = "DELETE FROM job_application WHERE id_offer = ? AND id_user = ?";
$stmt = $connection->prepare($sql);
if ($stmt) {
    $stmt->bind_param('ii', $id_offer, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}


// Requête SQL pour sauvegarder job notification
$sql = "INSERT into offer_notifications(notif_title,notif_message,notif_date,id_user,id_offer,notif_status) VALUES (?,?,?,?,?,?)";
$stmt = $connection->prepare($sql);
$title = 'Cancel Job Application';
$notification = 'You have canceled your application to the offer : "' . $offer_title . '"!';
$date = date("Y-m-d H:i:s");
$status = 'unread';



if ($stmt) {
    $stmt->bind_param('sssiis', $title, $notification, $date, $user_id, $id_offer, $status);
    $stmt->execute();
    $result = $stmt->get_result();
}
header('location:offerDetails.php?offer_id=' . $id_offer);
