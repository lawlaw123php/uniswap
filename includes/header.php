<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title).' — UniSwap' : 'UniSwap'; ?></title>
    <link rel="stylesheet" href="css/uniswap.css">
    <?php if (isset($stylesheets) && is_array($stylesheets)): ?>
        <?php foreach ($stylesheets as $stylesheet): ?>
            <link rel="stylesheet" href="<?php echo $stylesheet; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
<?php
$hide_nav = isset($hide_navbar) && $hide_navbar === true;
if (!$hide_nav):
    $current = basename($_SERVER['PHP_SELF']);
    $is_home     = in_array($current, ['index.php','']);
    $is_listings = in_array($current, ['user_listings.php']);
    $is_dash     = in_array($current, ['user_dashboard.php']);
?>
<nav class="uni-navbar">
    <a class="nav-brand" href="index.php">
        <span class="brand-text">UNI<br>SWAP</span>
        <span class="brand-x">x</span>
        <img src="img/cit_logo.png" alt="CIT-U" class="brand-logo">
    </a>
    <div class="nav-links">
        <a href="index.php" <?php if($is_home) echo 'class="active"'; ?>>Home</a>
        <a href="user_listings.php" <?php if($is_listings) echo 'class="active"'; ?>>Listings</a>
        <a href="user_dashboard.php" <?php if($is_dash) echo 'class="active"'; ?>>Dashboard</a>
        <a href="user_profile.php" class="nav-profile" style="margin-left:auto;text-decoration:none;">
            <div class="profile-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>
            <span>My Profile</span>
        </a>
    </div>
</nav>
<?php endif; ?>
