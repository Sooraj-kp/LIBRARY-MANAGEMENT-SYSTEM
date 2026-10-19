<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $author_name=$_POST['author_name'];
    $category_name=$_POST['category_name'];

    $sql1="INSERT INTO authors (author_name) values ('$author_name')";
    $sql2="INSERT INTO category (category_name) values ('$category_name')";

    if(mysqli_query($conn,$sql1)&& mysqli_query($conn,$sql2)){
        echo "Authors and Category Inserted";
    }
    else{
        echo "Error adding Author and category";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author And Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        
    <?php include "admin_sidebar.php";?>
    <div class="main-content">
        <h2 class="text-primary fw-bold text-center">Author And Category Adding</h2>
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <form method=POST>
                    <label class="form-label text-primary fw-bold" >Author Name: </label>
                    <input type="text" class="form-control fw-bold text-success" name="author_name" placeholder="Enter The Author name">
                    <br><br>
                    <label class="form-label text-primary fw-bold" >Category Name: </label>
                    <input type="text" class="form-control fw-bold text-success" name="category_name" placeholder="Enter the Category name">
                    <br><br>
                    <input type="submit" value="Add" class="btn btn-primary w-100">

                </form>
            <br><br>
            </div>
        </div>
    </div>
</div>   
</body>
</html>
