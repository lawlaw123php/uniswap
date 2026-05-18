<?php
include 'connect.php';
require_once 'includes/admin_guard.php';
$title = 'Buyer Management';
require_once 'includes/header.php';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
?>

<h1>Buyer Management</h1>
<p><a href="admin_dashboard.php">Back to Dashboard</a> | <a href="buyer_add.php">Add Buyer</a></p>

<form method="get">
    <input type="text" name="search" placeholder="Search buyer" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Buyer ID</th>
        <th>Student ID</th>
        <th>Name</th>
        <th>Payment Method</th>
        <th>Actions</th>
    </tr>
<?php
$sql = "SELECT b.buyerID, b.studentID, b.paymentMethod, u.firstName, u.lastName FROM BUYER b INNER JOIN `USER` u ON b.studentID = u.studentID";
if ($search != '') {
    $safe = mysqli_real_escape_string($connection, $search);
    $sql .= " WHERE b.studentID LIKE '%".$safe."%' OR u.firstName LIKE '%".$safe."%' OR u.lastName LIKE '%".$safe."%' OR b.paymentMethod LIKE '%".$safe."%'";
}
$sql .= " ORDER BY b.buyerID DESC";
$result = mysqli_query($connection, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['buyerID']."</td>";
        echo "<td>".$row['studentID']."</td>";
        echo "<td>".htmlspecialchars($row['firstName'].' '.$row['lastName'])."</td>";
        echo "<td>".htmlspecialchars($row['paymentMethod'])."</td>";
        echo "<td><a href='buyer_edit.php?id=".$row['buyerID']."'>Edit</a> | <a href='buyer_delete.php?id=".$row['buyerID']."' onclick=\"return confirm('Delete this buyer?');\">Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No buyers found.</td></tr>";
}
?>
</table>

<?php require_once 'includes/footer.php'; ?>