<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Seller Management';
require_once 'includes/header.php';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
?>

<h1>Seller Management</h1>
<p><a href="admin_dashboard.php">Back to Dashboard</a> | <a href="seller_add.php">Add Seller</a></p>

<form method="get">
    <input type="text" name="search" placeholder="Search seller" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Seller ID</th>
        <th>Student ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
<?php
$sql = "SELECT s.sellerID, s.studentID, s.accountStatus, u.firstName, u.lastName FROM SELLER s INNER JOIN `USER` u ON s.studentID = u.studentID";
if ($search != '') {
    $safe = mysqli_real_escape_string($connection, $search);
    $sql .= " WHERE s.studentID LIKE '%".$safe."%' OR u.firstName LIKE '%".$safe."%' OR u.lastName LIKE '%".$safe."%' OR s.accountStatus LIKE '%".$safe."%'";
}
$sql .= " ORDER BY s.sellerID DESC";
$result = mysqli_query($connection, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['sellerID']."</td>";
        echo "<td>".$row['studentID']."</td>";
        echo "<td>".htmlspecialchars($row['firstName'].' '.$row['lastName'])."</td>";
        echo "<td>".htmlspecialchars($row['accountStatus'])."</td>";
        echo "<td><a href='seller_edit.php?id=".$row['sellerID']."'>Edit</a> | <a href='seller_delete.php?id=".$row['sellerID']."' onclick=\"return confirm('Delete this seller?');\">Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No sellers found.</td></tr>";
}
?>
</table>

<?php require_once 'includes/footer.php'; ?>