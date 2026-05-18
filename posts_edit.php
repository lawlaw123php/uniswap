<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Edit Post';
require_once 'includes/header.php';

$id = $_GET['id'];
$sql = "SELECT * FROM POSTS WHERE itemID='".mysqli_real_escape_string($connection, $id)."'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['btnUpdate'])) {
    $sellerID = $_POST['txtsellerid'];
    $categoryID = $_POST['txtcategoryid'];
    $titlePost = $_POST['txttitle'];
    $description = $_POST['txtdescription'];
    $price = $_POST['txtprice'];
    $datePosted = $_POST['txtdateposted'];
    $condition = $_POST['txtcondition'];

    $sqlUpdate = "UPDATE POSTS SET sellerID='".mysqli_real_escape_string($connection, $sellerID)."', categoryID='".mysqli_real_escape_string($connection, $categoryID)."', title='".mysqli_real_escape_string($connection, $titlePost)."', description='".mysqli_real_escape_string($connection, $description)."', price='".mysqli_real_escape_string($connection, $price)."', datePosted='".mysqli_real_escape_string($connection, $datePosted)."', `condition`='".mysqli_real_escape_string($connection, $condition)."' WHERE itemID='".mysqli_real_escape_string($connection, $id)."'";
    mysqli_query($connection, $sqlUpdate);
    echo "<script>window.location.href='posts_list.php';</script>";
    exit;
}
?>

<h1>Edit Post</h1>
<p><a href="posts_list.php">Back to List</a></p>

<form method="post">
    <label>Item ID</label><br>
    <input type="text" value="<?php echo $row ? htmlspecialchars($row['itemID']) : ''; ?>" disabled>
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

    <label>Category</label><br>
    <select name="txtcategoryid" required>
        <?php
        $resultCategory = mysqli_query($connection, "SELECT categoryID, categoryName FROM CATEGORY ORDER BY categoryName");
        while ($cat = mysqli_fetch_assoc($resultCategory)) {
            $selected = ($row && $row['categoryID'] == $cat['categoryID']) ? 'selected' : '';
            echo "<option value='".$cat['categoryID']."' $selected>".htmlspecialchars($cat['categoryID'].' - '.$cat['categoryName'])."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Title</label><br>
    <input type="text" name="txttitle" value="<?php echo $row ? htmlspecialchars($row['title']) : ''; ?>" required>
    <br><br>

    <label>Description</label><br>
    <textarea name="txtdescription" rows="4" cols="40"><?php echo $row ? htmlspecialchars($row['description']) : ''; ?></textarea>
    <br><br>

    <label>Price</label><br>
    <input type="number" step="0.01" name="txtprice" value="<?php echo $row ? htmlspecialchars($row['price']) : '0.00'; ?>">
    <br><br>

    <label>Date Posted</label><br>
    <input type="date" name="txtdateposted" value="<?php echo $row ? htmlspecialchars($row['datePosted']) : ''; ?>">
    <br><br>

    <label>Condition</label><br>
    <input type="text" name="txtcondition" value="<?php echo $row ? htmlspecialchars($row['condition']) : ''; ?>">
    <br><br>

    <button type="submit" name="btnUpdate">Update Post</button>
</form>

<?php require_once 'includes/footer.php'; ?>