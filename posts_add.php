<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Add Post';
require_once 'includes/header.php';

if (isset($_POST['btnSave'])) {
    $itemID = $_POST['txtitemid'];
    $sellerID = $_POST['txtsellerid'];
    $categoryID = $_POST['txtcategoryid'];
    $titlePost = $_POST['txttitle'];
    $description = $_POST['txtdescription'];
    $price = $_POST['txtprice'];
    $datePosted = $_POST['txtdateposted'];
    $condition = $_POST['txtcondition'];

    $sql = "INSERT INTO POSTS(itemID, sellerID, categoryID, title, description, price, datePosted, `condition`) VALUES('".
        mysqli_real_escape_string($connection, $itemID)."', '".mysqli_real_escape_string($connection, $sellerID)."', '".mysqli_real_escape_string($connection, $categoryID)."', '".mysqli_real_escape_string($connection, $titlePost)."', '".mysqli_real_escape_string($connection, $description)."', '".mysqli_real_escape_string($connection, $price)."', '".mysqli_real_escape_string($connection, $datePosted)."', '".mysqli_real_escape_string($connection, $condition)."')";
    mysqli_query($connection, $sql);
    echo "<script>window.location.href='posts_list.php';</script>";
    exit;
}
?>

<h1>Add Post</h1>
<p><a href="posts_list.php">Back to List</a></p>

<form method="post">
    <label>Item ID</label><br>
    <input type="text" name="txtitemid" required>
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

    <label>Category</label><br>
    <select name="txtcategoryid" required>
        <option value="">-- select category --</option>
        <?php
        $result = mysqli_query($connection, "SELECT categoryID, categoryName FROM CATEGORY ORDER BY categoryName");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='".$row['categoryID']."'>".htmlspecialchars($row['categoryID'].' - '.$row['categoryName'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Title</label><br>
    <input type="text" name="txttitle" required>
    <br><br>

    <label>Description</label><br>
    <textarea name="txtdescription" rows="4" cols="40"></textarea>
    <br><br>

    <label>Price</label><br>
    <input type="number" step="0.01" name="txtprice" value="0.00">
    <br><br>

    <label>Date Posted</label><br>
    <input type="date" name="txtdateposted" value="<?php echo date('Y-m-d'); ?>">
    <br><br>

    <label>Condition</label><br>
    <input type="text" name="txtcondition">
    <br><br>

    <button type="submit" name="btnSave">Save Post</button>
</form>

<?php require_once 'includes/footer.php'; ?>