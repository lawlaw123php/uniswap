<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';
    $title = 'Add Category';
    require_once 'includes/header.php';
?>

<div class="wrap">
    <h1>Add Category</h1>
    <form method="post">
        <label>Category Name</label><br><br>
        <input type="text" name="txtcategoryname" required>
        <br>
        <button class="btn" type="submit" name="btnSave">Save Category</button>
        <a class="btn back" href="category_list.php">Back</a>
    </form>
</div>

<?php
if(isset($_POST['btnSave'])){
    $cname = $_POST['txtcategoryname'];
    $sql = "INSERT INTO CATEGORY(categoryName) VALUES('".$cname."')";
    mysqli_query($connection, $sql);
    echo "<script language='javascript'>
        alert('Category saved.');
        window.location.href='category_list.php';
    </script>";
}
?>

<?php require_once 'includes/footer.php'; ?>