<?php
session_start();
include "db.php";
if(!isset($_SESSION['stud_id'])){
    header("Location:student_login.php");
    exit;
}
$sql="SELECT books.book_id,books.title,authors.author_name,category.category_name,books.isbn,books.quantity FROM books LEFT JOIN authors ON books.author_id=authors.author_id LEFT JOIN category ON books.category_id=category.category_id ORDER BY books.book_id DESC";
$result=mysqli_query($conn,$sql);

if(!$result){
    die("Error".mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Book</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
    <center>
    <h2 class="text-primary text-center mb-4 ">STUDENTS BOOKS</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
    <table class="table table-striped table-hover table-bordered align-middle">
        <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Isbn</th>
            <th>Quantity</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while($row=mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td>".$row['book_id']."</td>";
            echo "<td>".htmlspecialchars($row['title'])."</td>";
            echo "<td>".htmlspecialchars($row['author_name'])."</td>";
            echo "<td>".htmlspecialchars($row['category_name'])."</td>";
            echo "<td>".htmlspecialchars($row['isbn'])."</td>";
            echo "<td>".htmlspecialchars($row['quantity'])."</td>"; 
            echo "</tr>";
        }
        ?>
        </tbody>
            </table>
                </div>
        <br>
        <a href="student_dashboard.php " class="btn btn-secondary   ">Back To Dashboard</a>
        </div> 
    </div>
    </center>

</body>
</html>
