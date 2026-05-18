<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Add Buyer';
require_once 'includes/header.php';

if (isset($_POST['btnSave'])) {
    $studentID = $_POST['txtstudentid'];
    $paymentMethod = $_POST['txtpaymentmethod'];

    $check = mysqli_query($connection, "SELECT buyerID FROM BUYER WHERE studentID='".mysqli_real_escape_string($connection, $studentID)."'");
    if ($check && mysqli_num_rows($check) > 0) {
        echo "<p>Buyer already exists.</p>";
    } else {
        $sql = "INSERT INTO BUYER(studentID, paymentMethod) VALUES('".mysqli_real_escape_string($connection, $studentID)."', '".mysqli_real_escape_string($connection, $paymentMethod)."')";
        mysqli_query($connection, $sql);
        echo "<script>window.location.href='buyer_list.php';</script>";
        exit;
    }
}
?>

<h1>Add Buyer</h1>
<p><a href="buyer_list.php">Back to List</a></p>

<form method="post">
    <label>Student</label><br>
    <select name="txtstudentid" required>
        <option value="">-- select student --</option>
        <?php
        $sql = "SELECT studentID, firstName, lastName FROM `USER` WHERE role='Student' AND studentID NOT IN (SELECT studentID FROM BUYER) ORDER BY firstName";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='".$row['studentID']."'>".htmlspecialchars($row['studentID'].' - '.$row['firstName'].' '.$row['lastName'])."</option>";
            }
        }
        ?>
    </select>
    <br><br>

    <label>Payment Method</label><br>
    <input type="text" name="txtpaymentmethod">
    <br><br>

    <button type="submit" name="btnSave">Save Buyer</button>
</form>

<?php require_once 'includes/footer.php'; ?>