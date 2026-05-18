<?php
session_start();
include 'connect.php';

$itemID = isset($_GET['id']) ? trim($_GET['id']) : '';
$item   = null;
if($itemID !== ''){
    $sql    = "SELECT p.*, c.categoryName, u.firstName, u.lastName, u.contactNumber, u.email, s.studentID AS sellerStudent
               FROM POSTS p
               INNER JOIN CATEGORY c ON p.categoryID = c.categoryID
               INNER JOIN SELLER s ON p.sellerID = s.sellerID
               INNER JOIN `USER` u ON s.studentID = u.studentID
               WHERE p.itemID='" . mysqli_real_escape_string($connection,$itemID) . "' LIMIT 1";
    $result = mysqli_query($connection, $sql);
    $item   = mysqli_fetch_assoc($result);
}

function categoryEmoji($name){
    $n = strtolower($name);
    if(strpos($n,'electron')!==false) return '💻';
    if(strpos($n,'book')!==false || strpos($n,'text')!==false) return '📚';
    if(strpos($n,'cloth')!==false || strpos($n,'uniform')!==false) return '👔';
    if(strpos($n,'lab')!==false) return '🧪';
    if(strpos($n,'tool')!==false || strpos($n,'draft')!==false) return '📐';
    if(strpos($n,'shoe')!==false || strpos($n,'bag')!==false || strpos($n,'access')!==false) return '👟';
    return '🛍️';
}

$title = $item ? htmlspecialchars($item['title']) : 'Item Not Found';
require_once 'includes/header.php';
?>

<div class="uni-page">

<?php if($item): ?>
<div class="item-detail-wrap">

    <!-- Image placeholder -->
    <div class="item-detail-img" style="display:flex;align-items:center;justify-content:center;font-size:5rem;">
        <?php echo categoryEmoji($item['categoryName']); ?>
    </div>

    <!-- Details -->
    <div class="item-detail-body">
        <h2><?php echo htmlspecialchars($item['title']); ?></h2>
        <div class="item-price">₱<?php echo number_format($item['price'],2); ?></div>

        <div style="display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap;">
            <span style="background:var(--light-gray);padding:4px 12px;border-radius:12px;font-size:0.8rem;font-weight:700;"><?php echo htmlspecialchars($item['categoryName']); ?></span>
            <?php if(!empty($item['condition'])): ?>
            <span style="background:var(--yellow);padding:4px 12px;border-radius:12px;font-size:0.8rem;font-weight:700;"><?php echo htmlspecialchars($item['condition']); ?></span>
            <?php endif; ?>
            <span style="background:var(--light-gray);padding:4px 12px;border-radius:12px;font-size:0.8rem;color:var(--text-muted);">Posted <?php echo $item['datePosted']; ?></span>
        </div>

        <div class="item-cols">
            <div class="item-col">
                <h4>Description</h4>
                <p><?php echo !empty($item['description']) ? nl2br(htmlspecialchars($item['description'])) : 'No description provided.'; ?></p>
            </div>
            <div class="item-col">
                <h4>Seller Info</h4>
                <p><strong><?php echo htmlspecialchars($item['firstName'].' '.$item['lastName']); ?></strong></p>
                <?php if(!empty($item['contactNumber'])): ?>
                <p>📞 <?php echo htmlspecialchars($item['contactNumber']); ?></p>
                <?php endif; ?>
                <?php if(!empty($item['email'])): ?>
                <p>✉️ <?php echo htmlspecialchars($item['email']); ?></p>
                <?php endif; ?>
                <p style="font-size:0.78rem;color:var(--text-muted);margin-top:6px;">ID: <?php echo htmlspecialchars($item['sellerStudent']); ?></p>
            </div>
        </div>

        <div style="margin-top:24px;display:flex;gap:12px;flex-wrap:wrap;">
            <?php if(!empty($item['contactNumber'])): ?>
            <a href="tel:<?php echo htmlspecialchars($item['contactNumber']); ?>" class="btn-yellow">📞 Call Seller</a>
            <?php endif; ?>
            <?php if(!empty($item['email'])): ?>
            <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>?subject=Inquiry: <?php echo urlencode($item['title']); ?>" class="btn-yellow">✉️ Email Seller</a>
            <?php endif; ?>
            <a href="user_listings.php" class="btn-maroon">← Back to Listings</a>
        </div>

        <!-- Own listing controls -->
        <?php
        $isOwner = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] && $_SESSION['user_studentid'] === $item['sellerStudent'];
        if($isOwner):
        ?>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--light-gray);">
            <strong style="font-size:0.8rem;color:var(--text-muted);text-transform:uppercase;">Your Listing</strong>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <a href="user_edit_listing.php?id=<?php echo urlencode($item['itemID']); ?>" style="padding:8px 18px;background:var(--yellow);border-radius:20px;font-size:0.8rem;font-weight:700;">Edit</a>
                <a href="user_delete_listing.php?id=<?php echo urlencode($item['itemID']); ?>" onclick="return confirm('Delete this listing?')" style="padding:8px 18px;background:#ef4444;color:#fff;border-radius:20px;font-size:0.8rem;font-weight:700;">Delete</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>
    <div class="dashboard-wrap" style="text-align:center;">
        <p style="margin-bottom:16px;">Item not found or has been removed.</p>
        <a href="user_listings.php" class="btn-yellow">← Back to Listings</a>
    </div>
<?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
