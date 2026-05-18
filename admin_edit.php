<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Edit Admin';
    require_once 'includes/header.php'; 
?>

<?php
$id = $_GET['id'];

$sql = "Select p.adminprofileid,p.firstname,p.lastname,p.gender,a.emailadd,a.username,a.adminaccountid from tbladminprofile p,tbladminaccount a where p.adminprofileid=a.adminprofileid and a.adminaccountid='".$id."'";
$result = mysqli_query($connection,$sql);
$row = mysqli_fetch_array($result);

$adminprofileid = $row['adminprofileid'];
$fname = $row['firstname'];
$lname = $row['lastname'];
$gender = $row['gender'];
$email = $row['emailadd'];
$uname = $row['username'];
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Edit Admin Page</h2></p>
    </center>
</div>

<div>
<form method="post">
<pre>
Firstname:<input type="text" name="txtfirstname" value="<?php echo $fname; ?>">
Lastname:<input type="text" name="txtlastname" value="<?php echo $lname; ?>">            
Gender:
<select name="txtgender">
 <option value="Male" <?php if($gender == 'Male'){ echo 'selected'; } ?>>Male</option>
 <option value="Female" <?php if($gender == 'Female'){ echo 'selected'; } ?>>Female</option>
</select>

Email Address:<input type="text" name="txtemail" value="<?php echo $email; ?>">
Username:<input type="text" name="txtusername" value="<?php echo $uname; ?>">              

<input type="submit" name="btnUpdate" value="Update"> 
</pre>
</form>
</div>

<a href="admin_list.php">Back to List</a>


<?php
if(isset($_POST['btnUpdate'])){
$fname = $_POST['txtfirstname'];
$lname = $_POST['txtlastname'];
$gender = $_POST['txtgender'];
$email = $_POST['txtemail'];
$uname = $_POST['txtusername'];

$sql1 = "Update tbladminprofile set firstname='".$fname."',lastname='".$lname."',gender='".$gender."' where adminprofileid='".$adminprofileid."'";
mysqli_query($connection,$sql1);

$sql2 = "Update tbladminaccount set emailadd='".$email."',username='".$uname."' where adminaccountid='".$id."'";
mysqli_query($connection,$sql2);

echo "<script language='javascript'>
alert('Record updated.');
window.location.href='admin_list.php';
  </script>";
}
?>

<?php require_once 'includes/footer.php'; ?>
