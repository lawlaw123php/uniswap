<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Posts Management';
    require_once 'includes/header.php';
?>

<?php
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
?>

<h1>Posts Management</h1>
<p><a href="admin_dashboard.php">Back to Dashboard</a> | <a href="posts_add.php">Add Post</a></p>

<form method="get">
    <input type="text" name="search" placeholder="Search post" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Item ID</th>
        <th>Title</th>
        <th>Seller</th>
        <th>Category</th>
        <th>Price</th>
        <th>Date</th>
        <th>Condition</th>
        <th>Actions</th>
    </tr>
<?php
$sql = "SELECT p.*, s.studentID AS sellerStudent, u.firstName, u.lastName, c.categoryName FROM POSTS p INNER JOIN SELLER s ON p.sellerID = s.sellerID INNER JOIN `USER` u ON s.studentID = u.studentID INNER JOIN CATEGORY c ON p.categoryID = c.categoryID";
if ($search != '') {
    $safe = mysqli_real_escape_string($connection, $search);
    $sql .= " WHERE p.itemID LIKE '%".$safe."%' OR p.title LIKE '%".$safe."%' OR c.categoryName LIKE '%".$safe."%' OR u.firstName LIKE '%".$safe."%' OR u.lastName LIKE '%".$safe."%'";
}
$sql .= " ORDER BY p.datePosted DESC, p.itemID DESC";
$result = mysqli_query($connection, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['itemID']."</td>";
        echo "<td>".htmlspecialchars($row['title'])."</td>";
        echo "<td>".htmlspecialchars($row['sellerStudent'].' - '.$row['firstName'].' '.$row['lastName'])."</td>";
        echo "<td>".htmlspecialchars($row['categoryName'])."</td>";
        echo "<td>".$row['price']."</td>";
        echo "<td>".htmlspecialchars($row['datePosted'])."</td>";
        echo "<td>".htmlspecialchars($row['condition'])."</td>";
        echo "<td><a href='posts_edit.php?id=".$row['itemID']."'>Edit</a> | <a href='posts_delete.php?id=".$row['itemID']."' onclick=\"return confirm('Delete this post?');\">Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No posts found.</td></tr>";
}
?>
</table>

<?php require_once 'includes/footer.php'; ?>