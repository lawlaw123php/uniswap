<?php
    include 'connect.php';
  require_once 'includes/admin_guard.php';
    $title = 'Delete Admin';
    require_once 'includes/header.php';
?>

<?php
if(isset($_GET['id'])){
    $id = $_GET['id'];

$sql1 = "Select adminprofileid from tbladminaccount where adminaccountid='".$id."'";
$result1 = mysqli_query($connection,$sql1);
$row1 = mysqli_fetch_array($result1);

if($row1){
    $adminprofileid = $row1['adminprofileid'];

    $sql2 = "Delete from tbladminaccount where adminaccountid='".$id."'";
    mysqli_query($connection,$sql2);

    $sql3 = "Delete from tbladminprofile where adminprofileid='".$adminprofileid."'";
    mysqli_query($connection,$sql3);

    echo "<script language='javascript'>
alert('Record deleted.');
window.location.href='admin_list.php';
  </script>";
} else {
    echo "<script language='javascript'>
alert('Record not found.');
window.location.href='admin_list.php';
  </script>";
}

} else {
echo "<script language='javascript'>
alert('Invalid request.');
window.location.href='admin_list.php';
  </script>";
}
?>

<?php require_once 'includes/footer.php'; ?>
