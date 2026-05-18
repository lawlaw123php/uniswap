<?php
include 'connect.php';
require_once 'includes/admin_guard.php';

$id = $_GET['id'];
mysqli_query($connection, "DELETE FROM `TRANSACTION` WHERE transactionID='".mysqli_real_escape_string($connection, $id)."'");
header('Location: transaction_list.php');
exit;
?>