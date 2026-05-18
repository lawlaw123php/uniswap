<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Transaction Management';
    require_once 'includes/header.php';
?>

<?php
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
?>

<h1>Transaction Management</h1>
<p><a href="admin_dashboard.php">Back to Dashboard</a> | <a href="transaction_add.php">Add Transaction</a></p>

<form method="get">
    <input type="text" name="search" placeholder="Search transaction" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Transaction ID</th>
        <th>Item</th>
        <th>Buyer</th>
        <th>Seller</th>
        <th>Meetup Location</th>
        <th>Transaction Date</th>
        <th>Final Price</th>
        <th>Actions</th>
    </tr>
<?php
$sql = "SELECT t.*, p.title, b.studentID AS buyerStudent, sb.studentID AS sellerStudent FROM `TRANSACTION` t INNER JOIN POSTS p ON t.itemID = p.itemID INNER JOIN BUYER b ON t.buyerID = b.buyerID INNER JOIN SELLER sb ON t.sellerID = sb.sellerID";
if ($search != '') {
    $safe = mysqli_real_escape_string($connection, $search);
    $sql .= " WHERE t.transactionID LIKE '%".$safe."%' OR p.title LIKE '%".$safe."%' OR t.meetupLocation LIKE '%".$safe."%'";
}
$sql .= " ORDER BY t.transactionDate DESC";
$result = mysqli_query($connection, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['transactionID']."</td>";
        echo "<td>".htmlspecialchars($row['title'])."</td>";
        echo "<td>".$row['buyerStudent']."</td>";
        echo "<td>".$row['sellerStudent']."</td>";
        echo "<td>".htmlspecialchars($row['meetupLocation'])."</td>";
        echo "<td>".htmlspecialchars($row['transactionDate'])."</td>";
        echo "<td>".$row['finalPrice']."</td>";
        echo "<td><a href='transaction_edit.php?id=".$row['transactionID']."'>Edit</a> | <a href='transaction_delete.php?id=".$row['transactionID']."' onclick=\"return confirm('Delete this transaction?');\">Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No transactions found.</td></tr>";
}
?>
</table>

<?php require_once 'includes/footer.php'; ?>