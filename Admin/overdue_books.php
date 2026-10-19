<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
$sql="SELECT  members.name,books.title,borrowings.issue_date,borrowings.due_date from borrowings LEFT JOIN members ON members.member_id=borrowings.member_id LEFT JOIN books ON books.book_id=borrowings.book_id WHERE borrowings.due_date<CURDATE() AND borrowings.status='Issued'";
$result=mysqli_query($conn,$sql);
if(!$result){
    die("Failed:".mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OverDue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container-fluid mt-4 px-4">
    <?php include "admin_sidebar.php";?>
    <div class="main-content">
    <h2 class="text-primary text-center fw-bold mb-4">OVER DUE LISTINGS</h2>
    <div class="table-responsive">
    <table class="table table-bordered table-striped text-center table-hover align-middle">
        <thead class="table-danger">
        <tr>
            <th>Name </th>
            <th>Title</th>
            <th>Issue Date</th>
            <th>Due Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(mysqli_num_rows($result)==0){
            echo "<tr><td colspan='4'>NO OVERDUE RECORDS</td></tr>";

        }
        else{
            while($row=mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['issue_date']."</td>";
                echo "<td>".$row['due_date']."</td>";
                echo "</tr>";
            }
        }

        ?>
        </tbody>
    </table>
</div>
</div>
</div>  
</div>
</body>
</html>