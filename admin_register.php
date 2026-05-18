<?php
    include 'connect.php';
  require_once 'includes/admin_guard.php';
    $title = 'Admin Registration';
    require_once 'includes/header.php'; 
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Admin Registration Page</h2></p>
    </center>
</div>  

<div>
<form method="post">
<pre>
Firstname:<input type="text" name="txtfirstname">
Lastname:<input type="text" name="txtlastname">            
Gender:
<select name="txtgender">
 <option value="">----</option>
 <option value="Male">Male</option>
 <option value="Female">Female</option>
</select>

Email Address:<input type="text" name="txtemail">
Username:<input type="text" name="txtusername">
Password:<input type="password" name="txtpassword">                

<input type="submit" name="btnRegister" value="Register"> 
</pre>
</form>
</div>

<a href="admin_list.php">View Admin List</a>


<?php
if(isset($_POST['btnRegister'])){
//retrieve data from form and save the value to a variable
//for tbladminprofile
$fname = $_POST['txtfirstname'];
$lname = $_POST['txtlastname'];
$gender = $_POST['txtgender'];

//for tbladminaccount
$email = $_POST['txtemail'];
$uname = $_POST['txtusername'];
$pword = $_POST['txtpassword'];
$hashed_pword = password_hash($pword, PASSWORD_DEFAULT);

//save data to tbladminprofile
$sql1 = "Insert into tbladminprofile(firstname,lastname,gender) values('".$fname."','".$lname."','".$gender."')";
mysqli_query($connection,$sql1);

$adminprofileid = mysqli_insert_id($connection);

//Check tbladminaccount if username is already existing. Save info if false. Prompt msg if true.
$sql2 = "Select * from tbladminaccount where username='".$uname."'";
$result = mysqli_query($connection,$sql2);
$row = mysqli_num_rows($result);
if($row == 0){
$sql = "Insert into tbladminaccount(adminprofileid,emailadd,username,password) values('".$adminprofileid."','".$email."','".$uname."','".$hashed_pword."')";
mysqli_query($connection,$sql);
echo "<script language='javascript'>
alert('New admin saved.');
window.location.href='admin_list.php';
  </script>";
exit;
}else{
$sqldel = "Delete from tbladminprofile where adminprofileid='".$adminprofileid."'";
mysqli_query($connection,$sqldel);
echo "<script language='javascript'>
alert('Username already existing');
  </script>";
}


}
    

?>


<?php require_once 'includes/footer.php'; ?>
