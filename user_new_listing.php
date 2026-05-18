<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true){
    header('Location: user_login.php');
    exit;
}
include 'connect.php';

$studentID = $_SESSION['user_studentid'];
$error = '';

if(isset($_POST['btnAdd'])){
    $titlePost   = mysqli_real_escape_string($connection, trim($_POST['txttitle']));
    $price       = mysqli_real_escape_string($connection, trim($_POST['txtprice']));
    $categoryID  = mysqli_real_escape_string($connection, trim($_POST['txtcategory']));
    $description = mysqli_real_escape_string($connection, trim($_POST['txtdescription']));
    $condition   = mysqli_real_escape_string($connection, trim($_POST['txtcondition'] ?? 'Used'));

    if($titlePost === '' || $price === '' || $categoryID === ''){
        $error = 'Please fill in all required fields.';
    } else {
        /* Get or create seller record */
        $selResult = mysqli_query($connection, "SELECT sellerID FROM SELLER WHERE studentID='$studentID' LIMIT 1");
        $selRow    = mysqli_fetch_assoc($selResult);
        if($selRow){
            $sellerID = $selRow['sellerID'];
        } else {
            mysqli_query($connection, "INSERT INTO SELLER(studentID) VALUES('$studentID')");
            $sellerID = mysqli_insert_id($connection);
        }

        /* Generate unique itemID: I + 11-digit padded number */
        $maxRes = mysqli_query($connection, "SELECT MAX(CAST(SUBSTRING(itemID,2) AS UNSIGNED)) AS mx FROM POSTS");
        $maxRow = mysqli_fetch_assoc($maxRes);
        $nextNum = ($maxRow['mx'] ?? 0) + 1;
        $itemID  = 'I' . str_pad($nextNum, 11, '0', STR_PAD_LEFT);

        $datePosted = date('Y-m-d');
        $ok = mysqli_query($connection,
            "INSERT INTO POSTS(itemID,sellerID,categoryID,title,description,price,datePosted,`condition`)
             VALUES('$itemID','$sellerID','$categoryID','$titlePost','$description','$price','$datePosted','$condition')"
        );
        if($ok){
            header('Location: user_listings.php?added=1');
            exit;
        } else {
            $error = 'Database error: ' . mysqli_error($connection);
        }
    }
}

/* Categories */
$catResult = mysqli_query($connection, "SELECT categoryID, categoryName FROM CATEGORY ORDER BY categoryName");

$title = 'New Listing';
require_once 'includes/header.php';
?>

<div class="uni-page">
<div class="new-listing-card">
    <h2>New Listing</h2>

    <?php if($error): ?>
        <div class="notice error" style="background:rgba(255,0,0,0.2);color:#fff;border-left-color:var(--yellow);"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="listing-field">
            <input type="text" name="txttitle" placeholder="Title" value="<?php echo isset($_POST['txttitle']) ? htmlspecialchars($_POST['txttitle']) : ''; ?>" required>
        </div>
        <div class="listing-field">
            <input type="number" step="0.01" min="1" name="txtprice" placeholder="Price (₱)" value="<?php echo isset($_POST['txtprice']) ? htmlspecialchars($_POST['txtprice']) : ''; ?>" required>
        </div>
        <div class="listing-field">
            <select name="txtcategory" required>
                <option value="">Select Category</option>
                <?php while($cat = mysqli_fetch_assoc($catResult)): ?>
                    <option value="<?php echo $cat['categoryID']; ?>" <?php echo (isset($_POST['txtcategory']) && $_POST['txtcategory']==$cat['categoryID']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['categoryName']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="listing-field">
            <select name="txtcondition">
                <option value="New" <?php echo (isset($_POST['txtcondition']) && $_POST['txtcondition']==='New') ? 'selected' : ''; ?>>New</option>
                <option value="Good" <?php echo (!isset($_POST['txtcondition']) || $_POST['txtcondition']==='Good') ? 'selected' : ''; ?>>Good</option>
                <option value="Used" <?php echo (isset($_POST['txtcondition']) && $_POST['txtcondition']==='Used') ? 'selected' : ''; ?>>Used</option>
                <option value="For Parts" <?php echo (isset($_POST['txtcondition']) && $_POST['txtcondition']==='For Parts') ? 'selected' : ''; ?>>For Parts</option>
            </select>
        </div>
        <div class="listing-field">
            <textarea name="txtdescription" rows="4" placeholder="Description — what's included, reason for selling, meet-up preferences..."><?php echo isset($_POST['txtdescription']) ? htmlspecialchars($_POST['txtdescription']) : ''; ?></textarea>
        </div>

        <div class="btn-center" style="gap:12px;">
            <button type="submit" name="btnAdd" class="btn-yellow">Add Listing</button>
            <a href="user_listings.php" class="btn-maroon" style="padding:12px 24px;border-radius:30px;font-weight:700;font-size:0.88rem;letter-spacing:1px;text-transform:uppercase;">Cancel</a>
        </div>
    </form>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>
