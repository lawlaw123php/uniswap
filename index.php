<?php
session_start();
/* index.php — Landing page redirects to login if not logged in */
if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true){
    /* Already logged in: go to home/marketplace */
    include 'connect.php';
    $title = 'Home — Campus Marketplace';
    require_once 'includes/header.php';

    $recentSQL = "SELECT p.itemID, p.title, p.price, p.condition, c.categoryName
                  FROM POSTS p
                  INNER JOIN CATEGORY c ON p.categoryID = c.categoryID
                  ORDER BY p.datePosted DESC, p.itemID DESC
                  LIMIT 6";
    $recentResult = mysqli_query($connection, $recentSQL);
} else {
    /* Not logged in: redirect to login page */
    header('Location: user_login.php');
    exit;
}
?>

<div class="uni-page">

    <!-- Hero -->
    <div class="home-hero">
        <div class="home-hero-text">
            <h2>Welcome to the Campus Marketplace</h2>
            <p>Buy and sell textbooks, uniforms, and gear with fellow students.</p>
        </div>
        <div class="home-hero-img" style="background:linear-gradient(135deg,#eee,#ddd);display:flex;align-items:center;justify-content:center;">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 7H4C2.9 7 2 7.9 2 9v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2z" fill="#ccc"/>
                <path d="M16 7V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2" stroke="#aaa" stroke-width="2"/>
                <path d="M12 12v4M10 14h4" stroke="#aaa" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <!-- Search -->
    <div class="home-search">
        <form method="get" action="user_listings.php">
            <input type="text" name="search" placeholder="Search for books, lab coats, or course codes....">
            <span class="search-icon">🔍</span>
        </form>
    </div>

    <!-- Categories -->
    <div class="section-label">Browse by Category</div>
    <div class="category-grid">
        <a href="user_listings.php?cat=textbook" class="category-item">
            <div class="category-icon">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20" stroke="#7B1818" stroke-width="2" stroke-linecap="round"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" stroke="#7B1818" stroke-width="2"/>
                    <path d="M8 7h8M8 11h5" stroke="#F5C400" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <span>Textbooks</span>
        </a>
        <a href="user_listings.php?cat=uniform" class="category-item">
            <div class="category-icon">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.57a1 1 0 00.99.84H6v10a2 2 0 002 2h8a2 2 0 002-2V10h2.15a1 1 0 00.99-.84l.58-3.57a2 2 0 00-1.34-2.23z" stroke="#7B1818" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M12 2v4" stroke="#F5C400" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <span>Uniforms</span>
        </a>
        <a href="user_listings.php?cat=lab" class="category-item">
            <div class="category-icon">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.5 2v9.5l3.5 7H6l3.5-7V2" stroke="#7B1818" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 2h6" stroke="#7B1818" stroke-width="2" stroke-linecap="round"/>
                    <path d="M8 16h8" stroke="#F5C400" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <span>Lab Gear</span>
        </a>
        <a href="user_listings.php?cat=tools" class="category-item">
            <div class="category-icon">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="#7B1818" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 16l2 2" stroke="#F5C400" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <span>Tools</span>
        </a>
    </div>

    <!-- Recently Posted -->
    <div class="section-label">Recently Posted Items</div>
    <div class="listings-grid">
        <?php
        if($recentResult && mysqli_num_rows($recentResult) > 0):
            while($item = mysqli_fetch_assoc($recentResult)):
        ?>
        <a href="user_item.php?id=<?php echo $item['itemID']; ?>" class="listing-card">
            <div class="card-img" style="display:flex;align-items:center;justify-content:center;font-size:2.5rem;">
                🛍️
            </div>
            <div class="card-body">
                <div class="card-title"><?php echo htmlspecialchars($item['title']); ?></div>
                <div class="card-price">₱<?php echo number_format($item['price'],2); ?></div>
            </div>
        </a>
        <?php endwhile; else: ?>
        <p style="color:var(--text-muted);font-size:0.9rem;">No items posted yet. Be the first to sell!</p>
        <?php endif; ?>
    </div>

    <!-- Sell CTA -->
    <a href="user_new_listing.php" class="sell-btn">
        <span class="plus">+</span> Sell an Item
    </a>

</div>

<?php require_once 'includes/footer.php'; ?>
