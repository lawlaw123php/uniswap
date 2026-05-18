<?php
include 'connect.php';
require_once 'includes/admin_guard.php';

$id = $_GET['id'];
mysqli_query($connection, "DELETE FROM `TRANSACTION` WHERE itemID='".mysqli_real_escape_string($connection, $id)."'");
mysqli_query($connection, "DELETE FROM POSTS WHERE itemID='".mysqli_real_escape_string($connection, $id)."'");
header('Location: posts_list.php');
exit;
?>