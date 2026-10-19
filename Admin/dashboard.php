<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include "db.php";

$sql = "SELECT COUNT(*) AS Total FROM members";
$sql2 = "SELECT COUNT(*) AS Total_books FROM books";
$sql3 = "SELECT COUNT(*) AS Total_Issue FROM borrowings WHERE status='Issued'";
$sql4 = "SELECT COUNT(*) AS Total_Returned FROM borrowings WHERE status='Returned'";
$sql5 = "SELECT COUNT(*) AS Total_Ongoing_due FROM borrowings WHERE status='Issued' AND due_date < CURDATE()";

$result = mysqli_query($conn, $sql);
$result1 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);
$result4 = mysqli_query($conn, $sql4);
$result5 = mysqli_query($conn, $sql5);

if (!$result || !$result1 || !$result3 || !$result4 || !$result5) {
    die("Dashboard query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$row1 = mysqli_fetch_assoc($result1);
$row2 = mysqli_fetch_assoc($result3);
$row4 = mysqli_fetch_assoc($result4);
$row5 = mysqli_fetch_assoc($result5);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-subtle">

    <?php include "admin_sidebar.php"; ?>

    <div class="main-content">
        <h1 class="text-primary fw-bold text-center">Library Dashboard</h1>

       
<div class="dashboard">

    <div class="card text-secondary text-center" >
        <h3>Total Members</h3>
        <p><?php echo $row['Total']; ?></p>
    </div>

    <div class="card">
        <h3>Total Books</h3>
        <p><?php echo $row1['Total_books']; ?></p>
    </div>

    <div class="card">
        <h3>Total Books Issued</h3>
        <p><?php echo $row2['Total_Issue']; ?></p>
    </div>

    <div class="card text-success">
        <h3>Total Books Returned</h3>
        <p><?php echo $row4['Total_Returned']; ?></p>
    </div>

    <div class="card text-danger">
        <h3>Overdue Books</h3>
        <p><?php echo $row5['Total_Ongoing_due']; ?></p>
    </div>

</div>


    </div>

</body>
</html> 