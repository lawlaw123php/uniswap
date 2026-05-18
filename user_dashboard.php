<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true){
    header('Location: user_login.php');
    exit;
}
include 'connect.php';

$studentID = $_SESSION['user_studentid'];

/* Items sold — use correct table name TRANSACTION */
$soldSQL    = "SELECT COUNT(*) AS cnt FROM `TRANSACTION` t
               INNER JOIN SELLER s ON t.sellerID = s.sellerID
               WHERE s.studentID='$studentID'";
$soldResult = mysqli_query($connection, $soldSQL);
$soldRow    = $soldResult ? mysqli_fetch_assoc($soldResult) : ['cnt'=>0];
$itemsSold  = $soldRow['cnt'] ?? 0;

/* Total revenue */
$revSQL    = "SELECT COALESCE(SUM(t.finalPrice),0) AS total FROM `TRANSACTION` t
              INNER JOIN SELLER s ON t.sellerID = s.sellerID
              WHERE s.studentID='$studentID'";
$revResult = mysqli_query($connection, $revSQL);
$revRow    = $revResult ? mysqli_fetch_assoc($revResult) : ['total'=>0];
$revenue   = $revRow['total'] ?? 0;

/* Items listed */
$listSQL    = "SELECT COUNT(*) AS cnt FROM POSTS p
               INNER JOIN SELLER s ON p.sellerID = s.sellerID
               WHERE s.studentID='$studentID'";
$listResult = mysqli_query($connection, $listSQL);
$listRow    = $listResult ? mysqli_fetch_assoc($listResult) : ['cnt'=>0];
$itemsListed = $listRow['cnt'] ?? 0;

/* Recent listings by this user */
$recentSQL    = "SELECT p.itemID, p.title, p.price, p.condition, c.categoryName, p.datePosted
                 FROM POSTS p
                 INNER JOIN SELLER s ON p.sellerID = s.sellerID
                 INNER JOIN CATEGORY c ON p.categoryID = c.categoryID
                 WHERE s.studentID='$studentID'
                 ORDER BY p.datePosted DESC, p.itemID DESC
                 LIMIT 5";
$recentResult = mysqli_query($connection, $recentSQL);

$title = 'Dashboard';
require_once 'includes/header.php';
?>

<div class="uni-page">
<div class="dashboard-wrap">
    <h2>Dashboard</h2>

    <!-- Overview -->
    <div class="dash-section-label">Overview</div>
    <div class="dash-cards-row">
        <div class="dash-card">
            <span class="dash-num"><?php echo $itemsSold; ?></span>
            <span class="dash-lbl">Items Sold</span>
        </div>
        <div class="dash-card">
            <span class="dash-num">₱<?php echo number_format($revenue,2); ?></span>
            <span class="dash-lbl">Total Revenue</span>
        </div>
        <div class="dash-card">
            <div class="stars">
                <span class="star">☆</span><span class="star">☆</span>
                <span class="star">☆</span><span class="star">☆</span><span class="star">☆</span>
            </div>
            <span class="dash-lbl">Rating</span>
        </div>
    </div>

    <!-- Your Listings Stats -->
    <div class="dash-section-label">Your Listings</div>
    <div class="dash-cards-row">
        <div class="dash-card">
            <span class="dash-num"><?php echo $itemsListed; ?></span>
            <span class="dash-lbl">Items Listed</span>
        </div>
        <div class="dash-card">
            <span class="dash-num"><?php echo $itemsSold; ?></span>
            <span class="dash-lbl">Items Sold</span>
        </div>
        <div class="dash-card">
            <span class="dash-num"><?php echo max(0, $itemsListed - $itemsSold); ?></span>
            <span class="dash-lbl">Active Listings</span>
        </div>
    </div>

    <!-- Recent Listings Table -->
    <?php if($recentResult && mysqli_num_rows($recentResult) > 0): ?>
    <div class="dash-section-label" style="margin-top:8px;">Recent Listings</div>
    <table class="admin-table" style="margin-bottom:20px;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
                <th>Condition</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($recentResult)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['categoryName']); ?></td>
                <td>₱<?php echo number_format($row['price'],2); ?></td>
                <td><?php echo htmlspecialchars($row['condition'] ?? '—'); ?></td>
                <td><?php echo $row['datePosted']; ?></td>
                <td class="admin-actions">
                    <a href="user_edit_listing.php?id=<?php echo $row['itemID']; ?>" class="edit-btn">Edit</a>
                    <a href="user_delete_listing.php?id=<?php echo $row['itemID']; ?>" class="delete-btn" onclick="return confirm('Delete this listing?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="btn-center" style="gap:12px;flex-wrap:wrap;">
        <a href="user_new_listing.php" class="btn-yellow">+ New Listing</a>
        <a href="user_listings.php" class="btn-maroon">Browse Listings</a>
        <a href="user_logout.php" class="btn-maroon" style="background:#444;">Logout</a>
    </div>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>
