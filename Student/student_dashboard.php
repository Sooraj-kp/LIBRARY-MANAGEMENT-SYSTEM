<?php
include "db.php";
session_start();
if(!isset($_SESSION['stud_id'])){
    header("Location:student_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <center>
         <div class="container mt-6">
         <div class="card shadow-sm mx-auto text-center p-4"
             style="max-width: 500px;">
    <h1 class="text-primary mb-4">STUDENT DASHBOARD</h1>
    <br><br>
    <h3 class="text-success mb-3">Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
    <br>    
    <p class="mb-4"><b> You are successfully Logged in</b></p>
    <div class="d-grid gap-3">
        <p><a href="student_book.php" class="btn btn-primary">View Books</a></p>

        <p><a href="student_history.php" class="btn btn-danger">Borrowing History</a></p>

        <p><a href="student_logout.php" class="btn btn-secondary">Logout</a></p> 

    </div>
</div>
</div>
</center>
</body>
</html>