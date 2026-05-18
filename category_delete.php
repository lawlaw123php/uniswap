<?php
    include 'connect.php';
    require_once 'includes/admin_guard.php';

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $sqldeleteposts = "DELETE FROM POSTS WHERE categoryID='".$id."'";
        mysqli_query($connection, $sqldeleteposts);

        $sqldelete = "DELETE FROM CATEGORY WHERE categoryID='".$id."'";
        mysqli_query($connection, $sqldelete);

        echo "<script language='javascript'>
            alert('Category deleted.');
            window.location.href='category_list.php';
        </script>";
    } else {
        echo "<script language='javascript'>
            alert('Invalid request.');
            window.location.href='category_list.php';
        </script>";
    }
?>