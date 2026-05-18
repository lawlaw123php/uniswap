<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Add Seller';
require_once 'includes/header.php';

if (isset($_POST['btnSave'])) {
    $studentID = $_POST['txtstudentid'];
    $accountStatus = $_POST['txtstatus'];

    $check = mysqli_query($connection, "SELECT sellerID FROM SELLER WHERE studentID='".mysqli_real_escape_string($connection, $studentID)."'");
    if ($check && mysqli_num_rows($check) > 0) {
        echo "<p>Seller already exists.</p>";
    } else {
        $sql = "INSERT INTO SELLER(studentID, accountStatus) VALUES('".mysqli_real_escape_string($connection, $studentID)."', '".mysqli_real_escape_string($connection, $accountStatus)."')";
        mysqli_query($connection, $sql);
        echo "<script>window.location.href='seller_list.php';</script>";
        exit;
    }
}
?>

<h1>Add Seller</h1>
<p><a href="seller_list.php">Back to List</a></p>

<form method="post">
    <label>Student</label><br>
    <select name="txtstudentid" required>
        <option value="">-- select student --</option>
        <?php
        $sql = "SELECT studentID, firstName, lastName FROM `USER` WHERE role='Student' AND studentID NOT IN (SELECT studentID FROM SELLER) ORDER BY firstName";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='".$row['studentID']."'>".htmlspecialchars($row['studentID'].' - '.$row['firstName'].' '.$row['lastName'])."</option>";
            }
        }
        ?>
    </select>
    <br><br>

    <label>Account Status</label><br>
    <input type="text" name="txtstatus" value="Active">
    <br><br>

    <button type="submit" name="btnSave">Save Seller</button>
</form>

<?php require_once 'includes/footer.php'; ?>