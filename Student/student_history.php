<?php
include "db.php";
session_start();
if (!isset($_SESSION['stud_id'])) {
    header("Location: student_login.php");
    exit;
}
$sql="SELECT books.title,borrowings.issue_date,borrowings.due_date,borrowings.return_date,borrowings.status FROM borrowings JOIN books ON books.book_id=borrowings.book_id WHERE borrowings.member_id =? ORDER BY borrowings.borrow_id DESC";
$stmt=mysqli_prepare($conn,$sql);
if(!$stmt){
    die("ERROR OCCURED".mysqli_error($conn));
}
$member_id=$_SESSION['member_id'];
mysqli_stmt_bind_param($stmt, "i",$member_id);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK HISTORY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

</head>
<body class="bg-light">
    <div class="container mt-6">
        
    <center>
        <br><br>
    <h2 class="text-primary text-centered mb-4">STUDENT BOOK HISTORY</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-primary">
                <tr>
                    
                    <th>Title</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['issue_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";

                    echo "<td>" .
                    ($row['return_date'] !== null
                    ? htmlspecialchars($row['return_date'])
                    :   "Not returned")
                    . "</td>";

                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                    }

                } else {
                echo '<tr><td colspan="5">No borrowing history found.</td></tr>';
                }
                ?>
                </tbody>    
        </table>
        </div>
        <a href="student_dashboard.php" class="btn btn-secondary mt-3">Back To Dashboard</a>
    
        </div>
    </div>
</div>
</center>
</body>
</html>