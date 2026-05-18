<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Edit Seller';
require_once 'includes/header.php';

$id = $_GET['id'];
$sql = "SELECT s.*, u.firstName, u.lastName FROM SELLER s INNER JOIN `USER` u ON s.studentID = u.studentID WHERE s.sellerID='".mysqli_real_escape_string($connection, $id)."'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['btnUpdate'])) {
    $accountStatus = $_POST['txtstatus'];
    $sqlUpdate = "UPDATE SELLER SET accountStatus='".mysqli_real_escape_string($connection, $accountStatus)."' WHERE sellerID='".mysqli_real_escape_string($connection, $id)."'";
    mysqli_query($connection, $sqlUpdate);
    echo "<script>window.location.href='seller_list.php';</script>";
    exit;
}
?>

<h1>Edit Seller</h1>
<p><a href="seller_list.php">Back to List</a></p>

<form method="post">
    <label>Seller ID</label><br>
    <input type="text" value="<?php echo $row ? $row['sellerID'] : ''; ?>" disabled>
    <br><br>

    <label>Student</label><br>
    <input type="text" value="<?php echo $row ? htmlspecialchars($row['studentID'].' - '.$row['firstName'].' '.$row['lastName']) : ''; ?>" disabled>
    <br><br>

    <label>Account Status</label><br>
    <input type="text" name="txtstatus" value="<?php echo $row ? htmlspecialchars($row['accountStatus']) : ''; ?>">
    <br><br>

    <button type="submit" name="btnUpdate">Update Seller</button>
</form>

<?php require_once 'includes/footer.php'; ?>