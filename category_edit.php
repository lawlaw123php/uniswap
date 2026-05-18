<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Edit Category';
    require_once 'includes/header.php';

    $id = $_GET['id'];
    $sql = "SELECT * FROM CATEGORY WHERE categoryID='".$id."'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    $cname = $row ? $row['categoryName'] : '';
?>

<div class="wrap">
    <h1>Edit Category</h1>
    <form method="post">
        <label>Category Name</label><br><br>
        <input type="text" name="txtcategoryname" value="<?php echo $cname; ?>" required>
        <br>
        <button class="btn" type="submit" name="btnUpdate">Update Category</button>
        <a class="btn back" href="category_list.php">Back</a>
    </form>
</div>

<?php
if(isset($_POST['btnUpdate'])){
    $cname = $_POST['txtcategoryname'];
    $sql = "UPDATE CATEGORY SET categoryName='".$cname."' WHERE categoryID='".$id."'";
    mysqli_query($connection, $sql);
    echo "<script language='javascript'>
        alert('Category updated.');
        window.location.href='category_list.php';
    </script>";
}
?>

<?php require_once 'includes/footer.php'; ?>