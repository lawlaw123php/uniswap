<?php
include 'connect.php';
require_once 'includes/admin_guard.php';

$id = $_GET['id'];
$buyer = mysqli_query($connection, "SELECT studentID FROM BUYER WHERE buyerID='".mysqli_real_escape_string($connection, $id)."'");
$buyerRow = $buyer ? mysqli_fetch_assoc($buyer) : null;
if ($buyerRow) {
	mysqli_query($connection, "DELETE FROM `TRANSACTION` WHERE buyerID='".mysqli_real_escape_string($connection, $id)."'");
}
$sql = "DELETE FROM BUYER WHERE buyerID='".mysqli_real_escape_string($connection, $id)."'";
mysqli_query($connection, $sql);
header('Location: buyer_list.php');
exit;
?>