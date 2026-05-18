<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Edit Transaction';
require_once 'includes/header.php';

$id = $_GET['id'];
$sql = "SELECT * FROM `TRANSACTION` WHERE transactionID='".mysqli_real_escape_string($connection, $id)."'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['btnUpdate'])) {
    $itemID = $_POST['txtitemid'];
    $buyerID = $_POST['txtbuyerid'];
    $sellerID = $_POST['txtsellerid'];
    $meetupLocation = $_POST['txtmeetup'];
    $transactionDate = $_POST['txtdate'];
    if ($transactionDate != '') {
        $transactionDate = date('Y-m-d H:i:s', strtotime($transactionDate));
    }
    $finalPrice = $_POST['txtprice'];

    $sqlUpdate = "UPDATE `TRANSACTION` SET itemID='".mysqli_real_escape_string($connection, $itemID)."', buyerID='".mysqli_real_escape_string($connection, $buyerID)."', sellerID='".mysqli_real_escape_string($connection, $sellerID)."', meetupLocation='".mysqli_real_escape_string($connection, $meetupLocation)."', transactionDate='".mysqli_real_escape_string($connection, $transactionDate)."', finalPrice='".mysqli_real_escape_string($connection, $finalPrice)."' WHERE transactionID='".mysqli_real_escape_string($connection, $id)."'";
    mysqli_query($connection, $sqlUpdate);
    echo "<script>window.location.href='transaction_list.php';</script>";
    exit;
}
?>

<h1>Edit Transaction</h1>
<p><a href="transaction_list.php">Back to List</a></p>

<form method="post">
    <label>Transaction ID</label><br>
    <input type="text" value="<?php echo $row ? htmlspecialchars($row['transactionID']) : ''; ?>" disabled>
    <br><br>

    <label>Item</label><br>
    <select name="txtitemid" required>
        <?php
        $resultItem = mysqli_query($connection, "SELECT itemID, title FROM POSTS ORDER BY title");
        while ($item = mysqli_fetch_assoc($resultItem)) {
            $selected = ($row && $row['itemID'] == $item['itemID']) ? 'selected' : '';
            echo "<option value='".$item['itemID']."' $selected>".htmlspecialchars($item['itemID'].' - '.$item['title'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Buyer</label><br>
    <select name="txtbuyerid" required>
        <?php
        $resultBuyer = mysqli_query($connection, "SELECT b.buyerID, u.firstName, u.lastName FROM BUYER b INNER JOIN `USER` u ON b.studentID = u.studentID ORDER BY b.buyerID DESC");
        while ($buyer = mysqli_fetch_assoc($resultBuyer)) {
            $selected = ($row && $row['buyerID'] == $buyer['buyerID']) ? 'selected' : '';
            echo "<option value='".$buyer['buyerID']."' $selected>".htmlspecialchars($buyer['buyerID'].' - '.$buyer['firstName'].' '.$buyer['lastName'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Seller</label><br>
    <select name="txtsellerid" required>
        <?php
        $resultSeller = mysqli_query($connection, "SELECT s.sellerID, u.firstName, u.lastName FROM SELLER s INNER JOIN `USER` u ON s.studentID = u.studentID ORDER BY s.sellerID DESC");
        while ($seller = mysqli_fetch_assoc($resultSeller)) {
            $selected = ($row && $row['sellerID'] == $seller['sellerID']) ? 'selected' : '';
            echo "<option value='".$seller['sellerID']."' $selected>".htmlspecialchars($seller['sellerID'].' - '.$seller['firstName'].' '.$seller['lastName'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Meetup Location</label><br>
    <input type="text" name="txtmeetup" value="<?php echo $row ? htmlspecialchars($row['meetupLocation']) : ''; ?>">
    <br><br>

    <label>Transaction Date</label><br>
    <input type="datetime-local" name="txtdate" value="<?php echo $row && $row['transactionDate'] ? date('Y-m-d\TH:i', strtotime($row['transactionDate'])) : ''; ?>">
    <br><br>

    <label>Final Price</label><br>
    <input type="number" step="0.01" name="txtprice" value="<?php echo $row ? htmlspecialchars($row['finalPrice']) : '0.00'; ?>">
    <br><br>

    <button type="submit" name="btnUpdate">Update Transaction</button>
</form>

<?php require_once 'includes/footer.php'; ?>