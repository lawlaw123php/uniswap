<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Edit Buyer';
require_once 'includes/header.php';

$id = $_GET['id'];
$sql = "SELECT b.*, u.firstName, u.lastName FROM BUYER b INNER JOIN `USER` u ON b.studentID = u.studentID WHERE b.buyerID='".mysqli_real_escape_string($connection, $id)."'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['btnUpdate'])) {
    $paymentMethod = $_POST['txtpaymentmethod'];
    $sqlUpdate = "UPDATE BUYER SET paymentMethod='".mysqli_real_escape_string($connection, $paymentMethod)."' WHERE buyerID='".mysqli_real_escape_string($connection, $id)."'";
    mysqli_query($connection, $sqlUpdate);
    echo "<script>window.location.href='buyer_list.php';</script>";
    exit;
}
?>

<h1>Edit Buyer</h1>
<p><a href="buyer_list.php">Back to List</a></p>

<form method="post">
    <label>Buyer ID</label><br>
    <input type="text" value="<?php echo $row ? $row['buyerID'] : ''; ?>" disabled>
    <br><br>

    <label>Student</label><br>
    <input type="text" value="<?php echo $row ? htmlspecialchars($row['studentID'].' - '.$row['firstName'].' '.$row['lastName']) : ''; ?>" disabled>
    <br><br>

    <label>Payment Method</label><br>
    <input type="text" name="txtpaymentmethod" value="<?php echo $row ? htmlspecialchars($row['paymentMethod']) : ''; ?>">
    <br><br>

    <button type="submit" name="btnUpdate">Update Buyer</button>
</form>

<?php require_once 'includes/footer.php'; ?>