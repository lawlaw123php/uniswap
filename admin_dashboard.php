<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Admin Dashboard';
    require_once 'includes/header.php';
?>

<div class="layout">
    <div class="sidebar">
        <div class="brand">UniMarket</div>
        <div class="subtitle">Admin Panel</div>
        <a class="nav-link active" href="admin_dashboard.php">Dashboard</a>
        <a class="nav-link" href="user_list.php">Users</a>
        <a class="nav-link" href="buyer_list.php">Buyers</a>
        <a class="nav-link" href="seller_list.php">Sellers</a>
        <a class="nav-link" href="category_list.php">Categories</a>
        <a class="nav-link" href="posts_list.php">Posts</a>
        <a class="nav-link" href="transaction_list.php">Transactions</a>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Admin Dashboard</h1>
        </div>

        <div class="content">
            <div class="grid">
                <a class="card" href="admin_register.php">
                    <h3>Admin Users</h3>
                    <p>Create and manage admin accounts.</p>
                </a>
                <a class="card" href="category_list.php">
                    <h3>Categories</h3>
                    <p>Manage category records in CRUD format.</p>
                </a>
                <a class="card" href="buyer_list.php">
                    <h3>Buyers</h3>
                    <p>Manage buyer records and payment methods.</p>
                </a>
                <a class="card" href="seller_list.php">
                    <h3>Sellers</h3>
                    <p>Manage seller records and account status.</p>
                </a>
                <a class="card" href="posts_list.php">
                    <h3>Posts</h3>
                    <p>Handle marketplace post records.</p>
                </a>
                <a class="card" href="transaction_list.php">
                    <h3>Transactions</h3>
                    <p>Review completed transactions.</p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>