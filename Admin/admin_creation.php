<?php
include "db.php";
$username="admin";
$password="Admin@123";
$hashed_password=password_hash($password,PASSWORD_DEFAULT);
$sql="INSERT INTO admins (username,password) values (?,?)";
$stmt=mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"ss",$username,$hashed_password);
if(mysqli_stmt_execute($stmt)){
    echo "Admin Account Created Successfully";
}else{
    echo "Errors:".mysqli_error($conn);
}
?>