<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Add Transaction';
require_once 'includes/header.php';

if (isset($_POST['btnSave'])) {
    $transactionID = $_POST['txttransactionid'];
    $itemID = $_POST['txtitemid'];
    $buyerID = $_POST['txtbuyerid'];
    $sellerID = $_POST['txtsellerid'];
    $meetupLocation = $_POST['txtmeetup'];
    $transactionDate = $_POST['txtdate'];
    if ($transactionDate != '') {
        $transactionDate = date('Y-m-d H:i:s', strtotime($transactionDate));
    }
    $finalPrice = $_POST['txtprice'];

    $sql = "INSERT INTO `TRANSACTION`(transactionID, itemID, buyerID, sellerID, meetupLocation, transactionDate, finalPrice) VALUES('".
        mysqli_real_escape_string($connection, $transactionID)."', '".mysqli_real_escape_string($connection, $itemID)."', '".mysqli_real_escape_string($connection, $buyerID)."', '".mysqli_real_escape_string($connection, $sellerID)."', '".mysqli_real_escape_string($connection, $meetupLocation)."', '".mysqli_real_escape_string($connection, $transactionDate)."', '".mysqli_real_escape_string($connection, $finalPrice)."')";
    mysqli_query($connection, $sql);
    echo "<script>window.location.href='transaction_list.php';</script>";
    exit;
}
?>

<h1>Add Transaction</h1>
<p><a href="transaction_list.php">Back to List</a></p>

<form method="post">
    <label>Transaction ID</label><br>
    <input type="text" name="txttransactionid" required>
    <br><br>

    <label>Item</label><br>
    <select name="txtitemid" required>
        <option value="">-- select item --</option>
        <?php
        $result = mysqli_query($connection, "SELECT itemID, title FROM POSTS ORDER BY title");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='".$row['itemID']."'>".htmlspecialchars($row['itemID'].' - '.$row['title'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Buyer</label><br>
    <select name="txtbuyerid" required>
        <option value="">-- select buyer --</option>
        <?php
        $result = mysqli_query($connection, "SELECT b.buyerID, u.firstName, u.lastName FROM BUYER b INNER JOIN `USER` u ON b.studentID = u.studentID ORDER BY b.buyerID DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='".$row['buyerID']."'>".htmlspecialchars($row['buyerID'].' - '.$row['firstName'].' '.$row['lastName'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Seller</label><br>
    <select name="txtsellerid" required>
        <option value="">-- select seller --</option>
        <?php
        $result = mysqli_query($connection, "SELECT s.sellerID, u.firstName, u.lastName FROM SELLER s INNER JOIN `USER` u ON s.studentID = u.studentID ORDER BY s.sellerID DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='".$row['sellerID']."'>".htmlspecialchars($row['sellerID'].' - '.$row['firstName'].' '.$row['lastName'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Meetup Location</label><br>
    <input type="text" name="txtmeetup">
    <br><br>

    <label>Transaction Date</label><br>
    <input type="datetime-local" name="txtdate">
    <br><br>

    <label>Final Price</label><br>
    <input type="number" step="0.01" name="txtprice" value="0.00">
    <br><br>

    <button type="submit" name="btnSave">Save Transaction</button>
</form>

<?php require_once 'includes/footer.php'; ?>