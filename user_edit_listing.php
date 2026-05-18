<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true){
    header('Location: user_login.php');
    exit;
}
include 'connect.php';

$studentID = $_SESSION['user_studentid'];
$itemID    = isset($_GET['id']) ? trim($_GET['id']) : '';
$error     = '';
$success   = '';

/* Verify this listing belongs to logged-in user */
$checkSQL = "SELECT p.* FROM POSTS p
             INNER JOIN SELLER s ON p.sellerID = s.sellerID
             WHERE p.itemID='" . mysqli_real_escape_string($connection,$itemID) . "'
             AND s.studentID='$studentID' LIMIT 1";
$checkRes = mysqli_query($connection, $checkSQL);
$post     = mysqli_fetch_assoc($checkRes);

if(!$post){
    header('Location: user_dashboard.php');
    exit;
}

if(isset($_POST['btnUpdate'])){
    $title2      = mysqli_real_escape_string($connection, trim($_POST['txttitle']));
    $price       = mysqli_real_escape_string($connection, trim($_POST['txtprice']));
    $categoryID  = mysqli_real_escape_string($connection, trim($_POST['txtcategory']));
    $description = mysqli_real_escape_string($connection, trim($_POST['txtdescription']));
    $condition   = mysqli_real_escape_string($connection, trim($_POST['txtcondition'] ?? 'Used'));

    if($title2 === '' || $price === '' || $categoryID === ''){
        $error = 'Please fill in all required fields.';
    } else {
        $ok = mysqli_query($connection,
            "UPDATE POSTS SET title='$title2', price='$price', categoryID='$categoryID',
             description='$description', `condition`='$condition'
             WHERE itemID='" . mysqli_real_escape_string($connection,$itemID) . "'"
        );
        if($ok){
            $success = 'Listing updated successfully.';
            /* Refresh post data */
            $checkRes = mysqli_query($connection, $checkSQL);
            $post     = mysqli_fetch_assoc($checkRes);
        } else {
            $error = 'Database error: ' . mysqli_error($connection);
        }
    }
}

$catResult = mysqli_query($connection, "SELECT categoryID, categoryName FROM CATEGORY ORDER BY categoryName");

$title = 'Edit Listing';
require_once 'includes/header.php';
?>

<div class="uni-page">
<div class="new-listing-card">
    <h2>Edit Listing</h2>

    <?php if($error): ?><div class="notice error" style="background:rgba(255,0,0,0.2);color:#fff;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if($success): ?><div class="notice success" style="background:rgba(0,200,0,0.2);color:#fff;"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

    <form method="post">
        <div class="listing-field">
            <input type="text" name="txttitle" placeholder="Title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="listing-field">
            <input type="number" step="0.01" min="1" name="txtprice" placeholder="Price (₱)" value="<?php echo htmlspecialchars($post['price']); ?>" required>
        </div>
        <div class="listing-field">
            <select name="txtcategory" required>
                <option value="">Select Category</option>
                <?php while($cat = mysqli_fetch_assoc($catResult)): ?>
                    <option value="<?php echo $cat['categoryID']; ?>" <?php echo ($cat['categoryID']==$post['categoryID']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['categoryName']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="listing-field">
            <select name="txtcondition">
                <?php foreach(['New','Good','Used','For Parts'] as $cond): ?>
                    <option value="<?php echo $cond; ?>" <?php echo ($post['condition']===$cond) ? 'selected' : ''; ?>><?php echo $cond; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="listing-field">
            <textarea name="txtdescription" rows="4" placeholder="Description..."><?php echo htmlspecialchars($post['description'] ?? ''); ?></textarea>
        </div>

        <div class="btn-center" style="gap:12px;">
            <button type="submit" name="btnUpdate" class="btn-yellow">Save Changes</button>
            <a href="user_dashboard.php" class="btn-maroon" style="padding:12px 24px;border-radius:30px;font-weight:700;font-size:0.88rem;text-transform:uppercase;">Cancel</a>
        </div>
    </form>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>
