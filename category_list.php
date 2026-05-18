<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Category Management';
    require_once 'includes/header.php';

    $search = '';
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }

    $sqlTotal = "SELECT COUNT(*) AS total FROM CATEGORY";
    $resultTotal = mysqli_query($connection, $sqlTotal);
    $rowTotal = mysqli_fetch_assoc($resultTotal);
    $totalCategories = $rowTotal ? $rowTotal['total'] : 0;

    $sqlWithPosts = "SELECT COUNT(*) AS total FROM CATEGORY c WHERE EXISTS (SELECT 1 FROM POSTS p WHERE p.categoryID = c.categoryID)";
    $resultWithPosts = mysqli_query($connection, $sqlWithPosts);
    $rowWithPosts = mysqli_fetch_assoc($resultWithPosts);
    $categoriesWithPosts = $rowWithPosts ? $rowWithPosts['total'] : 0;

    $categoriesWithoutPosts = $totalCategories - $categoriesWithPosts;
?>

<h1>Category Management</h1>
<p>Admin Panel / Category Management</p>
<p>Total categories: <?php echo $totalCategories; ?> | Categories with posts: <?php echo $categoriesWithPosts; ?> | Empty categories: <?php echo $categoriesWithoutPosts; ?></p>

<form method="get">
    <input type="text" name="search" placeholder="Search categories..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <a href="category_add.php">Add category</a>
    <a href="admin_dashboard.php">Dashboard</a>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Category ID</th>
        <th>Category Name</th>
        <th>Posts</th>
        <th>Actions</th>
    </tr>
    <?php
        $sql = "SELECT c.categoryID, c.categoryName, COUNT(p.itemID) AS postcount FROM CATEGORY c LEFT JOIN POSTS p ON c.categoryID = p.categoryID";
        if($search != ''){
            $safeSearch = mysqli_real_escape_string($connection, $search);
            $sql .= " WHERE c.categoryName LIKE '%".$safeSearch."%'";
        }
        $sql .= " GROUP BY c.categoryID, c.categoryName ORDER BY c.categoryID DESC";

        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>CAT".str_pad($row['categoryID'], 3, '0', STR_PAD_LEFT)."</td>";
                echo "<td>".$row['categoryName']."</td>";
                echo "<td>".$row['postcount']." post".($row['postcount'] == 1 ? '' : 's')."</td>";
                echo "<td><a href='category_edit.php?id=".$row['categoryID']."'>Edit</a> | <a href='category_delete.php?id=".$row['categoryID']."' onclick=\"return confirm('Delete this category?');\">Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No categories found.</td></tr>";
        }
    ?>
</table>

<?php require_once 'includes/footer.php'; ?>