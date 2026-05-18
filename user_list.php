<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'User Management';
    require_once 'includes/header.php';

    $search = '';
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }

    $sqlTotal = "SELECT COUNT(*) AS total FROM `USER`";
    $resultTotal = mysqli_query($connection, $sqlTotal);
    $rowTotal = mysqli_fetch_assoc($resultTotal);
    $totalUsers = $rowTotal ? $rowTotal['total'] : 0;

    $sqlSellers = "SELECT COUNT(*) AS total FROM SELLER";
    $resultSellers = mysqli_query($connection, $sqlSellers);
    $rowSellers = mysqli_fetch_assoc($resultSellers);
    $totalSellers = $rowSellers ? $rowSellers['total'] : 0;

    $sqlBuyers = "SELECT COUNT(*) AS total FROM BUYER";
    $resultBuyers = mysqli_query($connection, $sqlBuyers);
    $rowBuyers = mysqli_fetch_assoc($resultBuyers);
    $totalBuyers = $rowBuyers ? $rowBuyers['total'] : 0;
?>

<h1>User Management</h1>
<p>Admin Panel / User Management</p>
<p>Total users: <?php echo $totalUsers; ?> | Sellers: <?php echo $totalSellers; ?> | Buyers: <?php echo $totalBuyers; ?></p>

<form method="get">
    <input type="text" name="search" placeholder="Search by student ID, name, or email..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <a href="user_add.php">Add user</a>
    <a href="admin_dashboard.php">Dashboard</a>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Student ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php
        $sql = "SELECT * FROM `USER`";
        if($search != ''){
            $safeSearch = mysqli_real_escape_string($connection, $search);
            $sql .= " WHERE studentID LIKE '%".$safeSearch."%' OR firstName LIKE '%".$safeSearch."%' OR lastName LIKE '%".$safeSearch."%' OR email LIKE '%".$safeSearch."%'";
        }
        $sql .= " ORDER BY studentID DESC";

        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['studentID']."</td>";
                echo "<td>".$row['firstName']."</td>";
                echo "<td>".$row['lastName']."</td>";
                echo "<td>".$row['contactNumber']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".(isset($row['role']) ? $row['role'] : 'Student')."</td>";
                echo "<td><a href='user_edit.php?id=".$row['studentID']."'>Edit</a> | <a href='user_delete.php?id=".$row['studentID']."' onclick=\"return confirm('Delete this user?');\">Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No users found.</td></tr>";
        }
    ?>
</table>

<?php require_once 'includes/footer.php'; ?>