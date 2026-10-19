<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
$id=$_GET['id'];
$sql="SELECT * FROM books where book_id = $id";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($result);

$author_sql = "SELECT * FROM authors";
$author_result = mysqli_query($conn, $author_sql);

$category_sql = "SELECT * FROM category";
$category_result = mysqli_query($conn, $category_sql);

if($_SERVER['REQUEST_METHOD']=="POST"){
    $author=$_POST['author'];
    $title=$_POST['title'];
    $category=$_POST['category'];
    $isbn=trim($_POST['isbn']);
    $quantity=$_POST['quantity'];

    $errors=[];

    if(empty($author)){
        $errors[]="Enter The Author Name";
    }
    if(empty($title)){
        $errors[]="Enter The Title";
    }
    if(empty($category)){
        $errors[]="Enter The category";
    }
    if(empty($isbn)){
        $errors[]="Enter The Isbn";
    }
    if(filter_var($quantity,FILTER_VALIDATE_INT)===false || $quantity<0){
        $errors[]="Quantity cant be non negative";
    }

    if(empty($errors)){

        $sql="UPDATE books SET title='$title',isbn='$isbn',quantity='$quantity',author_id='$author',category_id='$category' where book_id=$id";
    

        if(mysqli_query($conn,$sql)){
            header("Location: books.php");
            exit;
        } else{
            echo "Error: ".mysqli_error($conn);
        }
    } else{
        foreach($errors as $error){
            echo htmlspecialchars($error) ."<br>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container mt-6">
    <center>
    <?php include "admin_sidebar.php";?>
    <div class="main-content">
    <form method=POST>
        <h2 class="text-primary fw-bold text-center">BOOK EDITING PAGE</h2>
            <div class="card shadow-sm mx-auto" style="max-width:600px;">
                <div class="card-body">
            <label class="form-label text-danger fw-bold text-center">Author : </label>
            <select name="author" class="form-select fw-bold text-center text-secondary ">
            <option value="">Select Author</option>
    <?php
    while ($author_row = mysqli_fetch_assoc($author_result)) {

        $selected = ($row['author_id'] == $author_row['author_id'])
                    ? "selected"
                    : "";

        echo "<option value='" . $author_row['author_id'] . "' $selected>";
        echo htmlspecialchars($author_row['author_name']);
        echo "</option>";
    }
        ?>
        </select>
        <br><br>
        <label class="form-label fw-bold text-center text-danger">Title : </label>
        <input type="text" name="title" class="text-center text-secondary fw-bold form-control" placeholder="Enter The Title" value="<?php echo $row['title'];?>">
        <br><br>
        <label class="form-label text-danger fw-bold text-center">Category : </label>
        <select  name="category" class="form-select text-center text-secondary fw-bold">
    <?php
    while ($category_row = mysqli_fetch_assoc($category_result)) {

        $selected = ($row['category_id'] == $category_row['category_id'])
                    ? "selected"
                    : "";

        echo "<option value='" . $category_row['category_id'] . "' $selected>";
        echo htmlspecialchars($category_row['category_name']);
        echo "</option>";
    }
    ?>
        </select>   
        <br><br>
        <label class="form-label text-danger text-center fw-bold">Isbn : </label>
        <input type="number" name="isbn" class="form-control text-center text-secondary fw-bold "placeholder="Enter The isbn" value="<?php echo $row['isbn'];?>">   
        <br><br>  
        <label class="form-label text-danger text-center fw-bold ">Quantity : </label>
        <input type="number" name="quantity" class="form-control fw-bold text-center text-secondary" value="<?php echo $row['quantity'];?>" min=0>
        <br><br>
        <input type="submit" value="Edit" class="btn btn-success w-100">
    </form>

</div>
</body>
</html>


