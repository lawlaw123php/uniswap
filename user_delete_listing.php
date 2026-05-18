<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true){
    header('Location: user_login.php');
    exit;
}
include 'connect.php';

$studentID = $_SESSION['user_studentid'];
$itemID    = isset($_GET['id']) ? trim($_GET['id']) : '';

if($itemID !== ''){
    /* Verify ownership before deleting */
    $checkSQL = "SELECT p.itemID FROM POSTS p
                 INNER JOIN SELLER s ON p.sellerID = s.sellerID
                 WHERE p.itemID='" . mysqli_real_escape_string($connection,$itemID) . "'
                 AND s.studentID='$studentID' LIMIT 1";
    $checkRes = mysqli_query($connection, $checkSQL);
    if(mysqli_num_rows($checkRes) > 0){
        /* Delete any transactions referencing this item first */
        mysqli_query($connection, "DELETE FROM `TRANSACTION` WHERE itemID='" . mysqli_real_escape_string($connection,$itemID) . "'");
        mysqli_query($connection, "DELETE FROM POSTS WHERE itemID='" . mysqli_real_escape_string($connection,$itemID) . "'");
    }
}

header('Location: user_dashboard.php?deleted=1');
exit;
?>
