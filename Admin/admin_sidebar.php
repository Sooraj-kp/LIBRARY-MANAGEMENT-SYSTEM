<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<div class="sidebar">
    <h2>Library LMS</h2>
    <hr>

    <div class="admin-info">
        <strong>
            <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
        </strong>
        <p class="status">● Logged in</p>
    </div>

    <a href="dashboard.php">Dashboard</a>
    <a href="add_author.php">Add Author&Category</a>
    <a href="books.php">Manage Books</a>
    <a href="Add_book.php">Add Book</a>
    <a href="add_members.php">Add Members</a>
    <a href="members.php">Manage Members</a>
    <a href="issue_book.php">Issue Books</a>
    <a href="borrowings.php">View Borrowings</a>
    <a href="return_book.php">Return Books</a>
    <a href="overdue_books.php">Overdue Books</a>
    <a href="admin_logout.php" class="logout">Logout</a>
</div>

</body>
</html>