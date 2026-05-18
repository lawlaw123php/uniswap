<?php
session_start();
include 'connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cat    = isset($_GET['cat'])    ? trim($_GET['cat'])    : '';

$sql = "SELECT p.itemID, p.title, p.price, p.condition, c.categoryName, c.categoryID
        FROM POSTS p
        INNER JOIN CATEGORY c ON p.categoryID = c.categoryID";
$where = [];
if($search !== ''){
    $s = mysqli_real_escape_string($connection, $search);
    $where[] = "(p.title LIKE '%$s%' OR c.categoryName LIKE '%$s%' OR p.description LIKE '%$s%')";
}
if($cat !== ''){
    $c2 = mysqli_real_escape_string($connection, $cat);
    $where[] = "(c.categoryName LIKE '%$c2%' OR c.categoryID='$c2')";
}
if($where) $sql .= ' WHERE '.implode(' AND ', $where);
$sql .= " ORDER BY p.datePosted DESC, p.itemID DESC";
$result = mysqli_query($connection, $sql);

/* Category list for filter bar */
$catListRes = mysqli_query($connection, "SELECT categoryID, categoryName FROM CATEGORY ORDER BY categoryName");

function categoryEmoji($name){
    $n = strtolower($name);
    if(strpos($n,'electron')!==false || strpos($n,'laptop')!==false || strpos($n,'phone')!==false) return '💻';
    if(strpos($n,'book')!==false || strpos($n,'text')!==false) return '📚';
    if(strpos($n,'cloth')!==false || strpos($n,'uniform')!==false) return '👔';
    if(strpos($n,'lab')!==false || strpos($n,'dissect')!==false) return '🧪';
    if(strpos($n,'tool')!==false || strpos($n,'draft')!==false || strpos($n,'t-square')!==false) return '📐';
    if(strpos($n,'shoe')!==false || strpos($n,'bag')!==false || strpos($n,'access')!==false) return '👟';
    return '🛍️';
}

$title = 'Listings';
require_once 'includes/header.php';
?>

<div class="uni-page">

    <?php if(isset($_GET['added'])): ?>
        <div class="notice success" style="margin-bottom:16px;">✅ Your listing was added successfully!</div>
    <?php endif; ?>

    <!-- Search -->
    <div class="home-search" style="margin-bottom:16px;">
        <form method="get">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search listings...">
            <span class="search-icon">🔍</span>
        </form>
    </div>

    <!-- Category filter pills -->
    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:24px;">
        <a href="user_listings.php" style="padding:6px 16px;border-radius:20px;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;background:<?php echo $cat==='' && $search==='' ? 'var(--maroon)' : 'var(--light-gray)'; ?>;color:<?php echo $cat==='' && $search==='' ? '#fff' : 'var(--dark)'; ?>;">All</a>
        <?php
        $catListRes2 = mysqli_query($connection, "SELECT categoryID, categoryName FROM CATEGORY ORDER BY categoryName");
        while($cl = mysqli_fetch_assoc($catListRes2)):
            $active = ($cat == $cl['categoryID']);
        ?>
        <a href="user_listings.php?cat=<?php echo $cl['categoryID']; ?>" style="padding:6px 16px;border-radius:20px;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;background:<?php echo $active ? 'var(--maroon)' : 'var(--light-gray)'; ?>;color:<?php echo $active ? '#fff' : 'var(--dark)'; ?>;"><?php echo htmlspecialchars($cl['categoryName']); ?></a>
        <?php endwhile; ?>
    </div>

    <div class="section-label">
        <?php echo $search ? 'Results for "'.htmlspecialchars($search).'"' : ($cat ? '' : 'All Listings'); ?>
    </div>

    <div class="listings-grid">
        <?php
        $count = 0;
        if($result && mysqli_num_rows($result) > 0):
            while($item = mysqli_fetch_assoc($result)):
                $count++;
        ?>
        <a href="user_item.php?id=<?php echo urlencode($item['itemID']); ?>" class="listing-card">
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;font-size:2.8rem;">
                <?php echo categoryEmoji($item['categoryName']); ?>
            </div>
            <div class="card-body">
                <div class="card-title"><?php echo htmlspecialchars($item['title']); ?></div>
                <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:4px;"><?php echo htmlspecialchars($item['categoryName']); ?></div>
                <div class="card-price">₱<?php echo number_format($item['price'],2); ?></div>
                <?php if(!empty($item['condition'])): ?>
                <div style="font-size:0.7rem;margin-top:4px;display:inline-block;background:var(--light-gray);padding:2px 8px;border-radius:10px;"><?php echo htmlspecialchars($item['condition']); ?></div>
                <?php endif; ?>
            </div>
        </a>
        <?php endwhile; ?>
        <?php if($count === 0): ?>
            <p style="color:var(--text-muted);font-size:0.9rem;grid-column:1/-1;">No items found<?php echo $search ? ' for "'.htmlspecialchars($search).'"' : ''; ?>.</p>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
    <a href="user_new_listing.php" class="sell-btn">
        <span class="plus">+</span> Sell an Item
    </a>
    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
