<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
$author_sql = "SELECT * FROM authors";
$author_result = mysqli_query($conn, $author_sql);

if (!$author_result) {
    die("Author query error: " . mysqli_error($conn));
}

$category_sql = "SELECT * FROM category";
$category_result = mysqli_query($conn, $category_sql);

if (!$category_result) {
    die("Category query error: " . mysqli_error($conn));
}

if($_SERVER['REQUEST_METHOD']=="POST"){

    $author=$_POST['authors'];
    $title=$_POST['title'];
    $category=$_POST['category'];
    $isbn=trim($_POST['isbn']);
    $quantity=$_POST['quantity'];

    $errors=[];
    if(empty($author)){
        $errors[]="Author Is Required";
    }
    if(empty(trim($title))){
        $errors[]="Title cant be empty";
    }
    if(empty($category)){
        $errors[]="Please Select a valid category";
    }
    if(empty(trim($isbn))){
        $errors[]="enetr the Isbn";
    }
    if(filter_var($quantity,FILTER_VALIDATE_INT ) === false || $quantity<0){
        $errors[]="quantity must be non negative";
    }

    if(empty($errors)){

        $sql="INSERT INTO books (title,isbn,quantity,author_id,category_id) values ('$title','$isbn','$quantity','$author','$category')";

    if(mysqli_query($conn,$sql)){
        header("Location: books.php");
        exit;
    }
    else{
        echo "Error:".mysqli_error($conn);
        }
    } else{
        foreach($errors as $error){
            echo $error . "<br>";
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
    <?php include "admin_sidebar.php"; ?>

    <div class="main-content">
    <h2 class="text-primary text-center mb-4 fw-bold">BOOK ADDING SECTION</h2>
    <form method="POST">
        <div class="card shadow-sm mx-auto" style="max-width:650px;">
            <div class="card-body">
        <label class="form-label fw-bold text-primary">Author : </label>
        <select name="authors" class="form-select text-success fw-bold" required>
            <option value="">Select Author  </option>
             <?php
            while ($row = mysqli_fetch_assoc($author_result)) {
                echo "<option value='" . $row['author_id'] . "'>";
                echo htmlspecialchars($row['author_name']);
                echo "</option>";
            }
            ?>
        </select>
        <br><br>
        <label class="form-label fw-bold text-primary ">Title : </label>
        <input type="text" name="title" class="form-control text-success fw-bold" placeholder="Enter The Title">
        <br><br>
        <label class="form-label fw-bold text-primary">Category : </label>
        <select name="category" class="form-select  text-success fw-bold" required>
            <option value="">Select The category</option>
             <?php
            while ($row = mysqli_fetch_assoc($category_result)) {
                echo "<option value='" . $row['category_id'] . "'>";
                echo htmlspecialchars($row['category_name']);
                echo "</option>";
            }
            ?>
        </select>
        <br><br>
        <label class="form-label fw-bold text-primary">Isbn : </label>
        <input type="number" name="isbn" class="form-control text-success fw-bold" Placeholder="Enter The Isbn" required>
        <br><br>
        <label class="form-label text-primary fw-bold">Quantity : </label>
        <input type="number" name="quantity" class="form-control text-success fw-bold" placeholder="Enter The Quantity" min="0" required>
        <br><br>    
        <input type="submit" value="Add Book" class="btn btn-danger w-100">

    </form> 
</div>
</div>
</center>  
</div> 
</body>
</html>