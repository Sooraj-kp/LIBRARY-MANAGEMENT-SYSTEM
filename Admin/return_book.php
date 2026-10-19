<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
$sql = "SELECT borrowings.borrow_id, members.name, books.title,borrowings.issue_date,borrowings.due_date
        FROM borrowings
        LEFT JOIN books ON books.book_id = borrowings.book_id
        LEFT JOIN members ON members.member_id = borrowings.member_id
        WHERE borrowings.status = 'Issued'";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
    
</head>
<body class="bg-light">
    <div class="container-fluid mt-4 px-4">
        <?php include "admin_sidebar.php";?>
            <div class="main-content">
                <form method=POST class="row g-2 mb-4">
                <h2 class="text-danger fw-bold text-center">RETURN BOOK</h2>
                <?php
                if (isset($_GET['success']) && $_GET['success'] == '1') {
                ?>
                <div class="alert alert-success" role="alert">
                    Book returned successfully!
                </div>
                <?php
                }
                ?>
        <br><br><br>
        <div class="col-12 d-flex justify-content-center">
        <table class="table table-bordered table-striped table-hover mx-auto w-auto">
        <thead class="table-danger">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Book</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Action</th>
        </tr>
        </thead>
    <tbody>
    <?php
    while($row=mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".$row['borrow_id']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['title']."</td>";
        echo "<td>".$row['issue_date']."</td>";
        echo "<td>".$row['due_date']."</td>";
        echo "<td><a class='btn btn-warning'
        href='update_return.php?id=".$row['borrow_id']."'>Return</a></td>";
        echo "</tr>";
    }
    ?>
    </tbody>    
    </table>
</div>
</form>
</div>
</body>
</html>