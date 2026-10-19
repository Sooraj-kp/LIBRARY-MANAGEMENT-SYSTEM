<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $issuedate=$_POST['issuedate'];
    $duedate=$_POST['duedate'];
    $status=$_POST['status'];
    $member_id=$_POST['member_id'];
    $book_id=$_POST['book_id'];

    $sql_check="SELECT quantity FROM books where book_id=$book_id";
    $sql_issued="SELECT COUNT(borrow_id) As Issued_count FROM borrowings WHERE  book_id=$book_id AND status='Issued'";
    $result_check=mysqli_query($conn,$sql_check);
    $result_Issued=mysqli_query($conn,$sql_issued);
    $quantity_row=mysqli_fetch_assoc($result_check);
    $issued_row=mysqli_fetch_assoc($result_Issued);
    $quantity=$quantity_row['quantity']-$issued_row['Issued_count'];
    
    if($quantity>0){
    $sql="INSERT INTO borrowings (book_id,member_id,issue_date,due_date,return_date,status) values ('$book_id','$member_id','$issuedate','$duedate',NULL,'$status')";
    if(mysqli_query($conn,$sql)){
        header("Location: borrowings.php");
        exit;
    }
    else{
        echo "Error: ".mysqli_error($conn);
    }
    }else{
        echo "This Book is not Available";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Issuing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
    <?php include "admin_sidebar.php"?>;
    <div class="main-content">
    <form method=POST>
        <h2 class="text-danger fw-bold text-center">BOOK ISSUE PAGE</h2>
        <div class="card shadow-sm mx-auto" style="max-width:650px;">
            <div class="card-body">
        <label class="form-label fw-bold text-danger" >Member: </label>
        <select name="member_id" class="form-select fw-bold text-secondary"required>  
            <option value="">Select Member</option> 
            <?php
            $sql="SELECT member_id,name FROM  members";
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result)){
                echo "<option value='".$row['member_id']."'>".$row['name']."</option>";
            }
            ?>
        </select>
        <br><br>
        <label class="form-label fw-bold text-danger">Select Book: </label>
        <select name="book_id" class="form-select fw-bold text-secondary"required>
            <option value="">Select Book</option>
            <?php
            $sql="SELECT book_id,title FROM books";
            $result=mysqli_query($conn,$sql);
            while($row=mysqli_fetch_assoc($result)){
                echo "<option value='".$row['book_id']."'>".$row['title']."</option>";
                
            }
            ?>
        </select>
        <br><br>
        <label for="issuedate" class="form-label fw-bold text-danger">Issue Date: </label >
        <input type="date" id="issuedate" class=" form-control fw-bold text-secondary "name="issuedate"  required>
        <br><br>
        <label for="duedate" class="form-label text-danger fw-bold">Due Date: </label >
        <input type="date" id="duedate" class="form-control fw-bold text-secondary"name="duedate" required>
        <br><br>
        <label class="form-label fw-bold text-danger">Status : </label>
        <select name="status" class="form-select fw-bold text-secondary">
            <option>Select Status: </option>
            <option value="Issued">Issued</option>
            
        <select>
        <br><br>
        <input type="submit" value="Issue" class="btn btn-primary fw-bold w-100">

        </center>
    </form>
</div>   
</body>
</html>