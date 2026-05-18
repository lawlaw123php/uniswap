<?php
include 'connect.php';
require_once 'includes/admin_guard.php';

$id = $_GET['id'];

mysqli_query($connection, "DELETE t FROM `TRANSACTION` t INNER JOIN POSTS p ON t.itemID = p.itemID WHERE p.sellerID='".mysqli_real_escape_string($connection, $id)."'");
mysqli_query($connection, "DELETE FROM POSTS WHERE sellerID='".mysqli_real_escape_string($connection, $id)."'");
mysqli_query($connection, "DELETE FROM SELLER WHERE sellerID='".mysqli_real_escape_string($connection, $id)."'");

header('Location: seller_list.php');
exit;
?>