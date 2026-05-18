<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Admin List';
    require_once 'includes/header.php'; 
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Admin List Page</h2></p>
    </center>
</div>

<div>
    <a href="admin_register.php">Register New Admin</a>
    <br>
    <a href="admin_dashboard.php">Back to Admin Dashboard</a>
    <br><br>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Username</th>
            <th>Action</th>
        </tr>

        <?php
        $sql = "Select p.firstname,p.lastname,p.gender,a.emailadd,a.username,a.adminaccountid from tbladminprofile p,tbladminaccount a where p.adminprofileid=a.adminprofileid";
        $result = mysqli_query($connection,$sql);

        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
            echo "<td>".$row['firstname']."</td>";
            echo "<td>".$row['lastname']."</td>";
            echo "<td>".$row['gender']."</td>";
            echo "<td>".$row['emailadd']."</td>";
            echo "<td>".$row['username']."</td>";
            echo "<td><a href='admin_edit.php?id=".$row['adminaccountid']."'>Edit</a> | <a href='admin_delete.php?id=".$row['adminaccountid']."'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
