<?php
include "db.php";
$id=$_GET['id'];
$sql="DELETE from books where book_id=$id ";
if(mysqli_query($conn,$sql)){
    header("Location: books.php");
    exit;
}
else{
    echo "Error: ".mysqli_error($conn);
}
?>