<?php
session_start();
require('../../config/db_connection.php');
$user_id = $_SESSION['user_id'];
$id_offer = $_GET['offer_id'];
$offer_title = $_GET['offer_name'];
var_dump($offer_title);

// Requête SQL pour sauvegarder job application
$sql = "INSERT into job_application(id_offer,id_user) VALUES (?,?)";
$stmt = $connection->prepare($sql);
if ($stmt) {
    $stmt->bind_param('ii', $id_offer, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}


// Requête SQL pour sauvegarder job notification
$sql = "INSERT into offer_notifications(notif_title,notif_message,notif_date,id_user,id_offer,notif_status) VALUES (?,?,?,?,?,?)";
$stmt = $connection->prepare($sql);
$title = 'New Application';
$notification = 'You have successfully applied to the offer : "' . $offer_title . '"!';
$date = date("Y-m-d H:i:s");
$status = 'unread';



if ($stmt) {
    $stmt->bind_param('sssiis', $title, $notification, $date, $user_id, $id_offer, $status);
    $stmt->execute();
    $result = $stmt->get_result();
}
header('location:offerDetails.php?offer_id=' . $id_offer);
