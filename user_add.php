<?php
    include 'connect.php';
    $title = 'Add User';
    require_once 'includes/header.php';
?>

<div class="wrap">
    <h1>Add User</h1>
    <form method="post">
        <label>Student ID</label><br><br>
        <input type="text" name="txtstudentid" placeholder="e.g., S123456789" maxlength="10" required>
        <br><br>

        <label>First Name</label><br><br>
        <input type="text" name="txtfirstname" required>
        <br><br>

        <label>Last Name</label><br><br>
        <input type="text" name="txtlastname" required>
        <br><br>

        <label>Contact Number</label><br><br>
        <input type="text" name="txtcontact" placeholder="e.g., 09171234567">
        <br><br>

        <label>Email</label><br><br>
        <input type="email" name="txtemail" placeholder="e.g., student@example.com">
        <br>

        <label>Role</label><br><br>
        <select name="txtrole">
            <option value="Student">Student</option>
            <option value="Admin">Admin</option>
        </select>
        <br>

        <button class="btn" type="submit" name="btnSave">Save User</button>
        <a class="btn back" href="user_list.php">Back</a>
    </form>
</div>

<?php
if(isset($_POST['btnSave'])){
    $studentid = $_POST['txtstudentid'];
    $firstname = $_POST['txtfirstname'];
    $lastname = $_POST['txtlastname'];
    $contact = $_POST['txtcontact'];
    $email = $_POST['txtemail'];
    $role = $_POST['txtrole'];

    // Check if student ID already exists
    $checkSql = "SELECT * FROM `USER` WHERE studentID='".$studentid."'";
    $checkResult = mysqli_query($connection, $checkSql);

    if(mysqli_num_rows($checkResult) > 0){
        echo "<script language='javascript'>
            alert('Student ID already exists.');
        </script>";
    } else {
        $sql = "INSERT INTO `USER`(studentID, firstName, lastName, contactNumber, email, role) VALUES('".$studentid."', '".$firstname."', '".$lastname."', '".$contact."', '".$email."', '".$role."')";
        mysqli_query($connection, $sql);
        echo "<script language='javascript'>
            alert('User saved.');
            window.location.href='user_list.php';
        </script>";
    }
}
?>

<?php require_once 'includes/footer.php'; ?>
