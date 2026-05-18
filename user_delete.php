<?php
    include 'connect.php';

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $sqlBuyer = "SELECT buyerID FROM BUYER WHERE studentID='".$id."'";
        $resultBuyer = mysqli_query($connection, $sqlBuyer);
        while($buyerRow = mysqli_fetch_assoc($resultBuyer)){
            mysqli_query($connection, "DELETE FROM `TRANSACTION` WHERE buyerID='".$buyerRow['buyerID']."'");
        }

        $sqlSeller = "SELECT sellerID FROM SELLER WHERE studentID='".$id."'";
        $resultSeller = mysqli_query($connection, $sqlSeller);
        while($sellerRow = mysqli_fetch_assoc($resultSeller)){
            mysqli_query($connection, "DELETE t FROM `TRANSACTION` t INNER JOIN POSTS p ON t.itemID = p.itemID WHERE p.sellerID='".$sellerRow['sellerID']."'");
            mysqli_query($connection, "DELETE FROM POSTS WHERE sellerID='".$sellerRow['sellerID']."'");
        }

        $sqlDeleteBuyer = "DELETE FROM BUYER WHERE studentID='".$id."'";
        mysqli_query($connection, $sqlDeleteBuyer);

        $sqlDeleteSeller = "DELETE FROM SELLER WHERE studentID='".$id."'";
        mysqli_query($connection, $sqlDeleteSeller);

        $sqlDelete = "DELETE FROM `USER` WHERE studentID='".$id."'";
        mysqli_query($connection, $sqlDelete);

        echo "<script language='javascript'>
            alert('User deleted.');
            window.location.href='user_list.php';
        </script>";
    } else {
        echo "<script language='javascript'>
            alert('Invalid request.');
            window.location.href='user_list.php';
        </script>";
    }
?>
