<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];

    $sql="  INSERT INTO  members (name,email,phone) values ('$name','$email','$phone') ";
    

    if(mysqli_query($conn,$sql)){
        header("Location: members.php");
        exit;
    }
    else{
        echo "Error: ".mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container mt-3">
    <?php include "admin_sidebar.php"; ?>
    <div class="main-content">
    <h2 class="text-primary fw-bold mb-4 text-center">Members Adding</h2>
    <div class="card shadow-sm mx-auto" style="max-width:650px;">
        <div class="card-body">
    <form method=POST>
        <label class="form-label fw-bold text-success">Name: </label>
        <input type="text" name="name" class="form-control fw-bold text-primary" placeholder="Enter The Name" required>
        <br><br>
        <label class="form-label fw-bold text-success">Email: </label>
        <input type="email" name="email" class="text-primary form-control fw-bold" placeholder="Enter The Mail" required>
        <br><br>
        <label class="form-label fw-bold text-success">Phone</label>
        <input type="number" name="phone" class="fw-bold form-control text-primary" placeholder="Enter The Phone" required>
        <br><br>
        <input type="submit" value="Add Member" class="btn btn-danger w-150">
    </form>
    </div>
    </div>
</div>   
</body>
</html>
