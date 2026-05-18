<?php
    include 'connect.php';
    $title = 'Edit User';
    require_once 'includes/header.php';

    $id = $_GET['id'];
    $sql = "SELECT * FROM `USER` WHERE studentID='".$id."'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    $studentid = $row ? $row['studentID'] : '';
    $firstname = $row ? $row['firstName'] : '';
    $lastname = $row ? $row['lastName'] : '';
    $contact = $row ? $row['contactNumber'] : '';
    $email = $row ? $row['email'] : '';
    $role = $row ? $row['role'] : 'Student';
?>

<div class="wrap">
    <h1>Edit User</h1>
    <form method="post">
        <label>Student ID</label><br><br>
        <input type="text" value="<?php echo $studentid; ?>" disabled>
        <br><br>

        <label>First Name</label><br><br>
        <input type="text" name="txtfirstname" value="<?php echo $firstname; ?>" required>
        <br><br>

        <label>Last Name</label><br><br>
        <input type="text" name="txtlastname" value="<?php echo $lastname; ?>" required>
        <br><br>

        <label>Contact Number</label><br><br>
        <input type="text" name="txtcontact" value="<?php echo $contact; ?>">
        <br><br>

        <label>Email</label><br><br>
        <input type="email" name="txtemail" value="<?php echo $email; ?>">
        <br>

        <label>Role</label><br><br>
        <select name="txtrole">
            <option value="Student" <?php echo $role == 'Student' ? 'selected' : ''; ?>>Student</option>
            <option value="Admin" <?php echo $role == 'Admin' ? 'selected' : ''; ?>>Admin</option>
        </select>
        <br>

        <button class="btn" type="submit" name="btnUpdate">Update User</button>
        <a class="btn back" href="user_list.php">Back</a>
    </form>
</div>

<?php
if(isset($_POST['btnUpdate'])){
    $firstname = $_POST['txtfirstname'];
    $lastname = $_POST['txtlastname'];
    $contact = $_POST['txtcontact'];
    $email = $_POST['txtemail'];
    $role = $_POST['txtrole'];

    $sql = "UPDATE `USER` SET firstName='".$firstname."', lastName='".$lastname."', contactNumber='".$contact."', email='".$email."', role='".$role."' WHERE studentID='".$id."'";
    mysqli_query($connection, $sql);
    echo "<script language='javascript'>
        alert('User updated.');
        window.location.href='user_list.php';
    </script>";
}
?>

<?php require_once 'includes/footer.php'; ?>
