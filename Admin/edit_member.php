<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
$id=$_GET['id'];
$sql="SELECT * FROM members where member_id=$id";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD']=="POST"){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];

    $sql="UPDATE members set name='$name',email='$email',phone='$phone' where member_id=$id";
    if(mysqli_query($conn,$sql)){
        header("Location: members.php");
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
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container mt-6">
    <?php include "admin_sidebar.php"?>;
    <div class="main-content">
    <form method=POST>
        <h2 class="text-center text-primary fw-bold">MEMBERS EDIT</h2> 
        <div class="card shadow-sm mx-auto" style="max-width:600px;">
            <div class="card-body">
        <label class="form-label fw-bold">Name: </label> 
        <input type="text" name="name" class="text-center text-primary fw-bold form-control"placeholder="Enter the name" value="<?php echo $row['name'];?>">
        <br><br>
        <label class="form-label fw-bold">Email: </label>
        <input type="email" name="email" class="text-primary text-center fw-bold form-control"placeholder="Enter the Email"  value="<?php echo $row['email'];?>">     
        <br><br>
        <label class="form-label fw-bold text-primary">Phone : </label>
        <input type="text" name="phone" class="form-control text-primary text-center fw-bold"placeholder="Enter the phone" value="<?php echo $row['phone'];?>">
        <br><br>
        <input type="submit" value="Edit" class="btn btn-danger w-100">
        </div>
</div>
    </form>
</div>
</div> 
</body>
</html>