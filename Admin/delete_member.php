<?php
include "db.php";
$id=$_GET['id'];
$sql="DELETE FROM members where member_id=$id";
if(mysqli_query($conn,$sql)){
    header("Location: members.php");
    exit;
}
else{
    echo "Error: ".mysqli_error($conn);
}
?>