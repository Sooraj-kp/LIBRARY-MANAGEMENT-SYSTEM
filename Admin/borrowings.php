
<?php
include "db.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

// Count matching borrowing records
$count_sql = "SELECT COUNT(*) AS total
FROM borrowings
LEFT JOIN members ON members.member_id = borrowings.member_id
LEFT JOIN books ON books.book_id = borrowings.book_id
WHERE members.name LIKE '$search%'
OR members.phone LIKE '%$search%'
OR members.email LIKE '%$search%'
OR books.title LIKE '%$search%'
OR books.isbn LIKE '%$search%'
OR borrowings.issue_date LIKE '%$search%'
OR borrowings.due_date LIKE '%$search%'";

$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);

$total_borrowings = $count_row['total'];
$total_pages = ceil($total_borrowings / $limit);

// Prevent page number from exceeding the last page
if ($total_pages > 0 && $page > $total_pages) {
    $page = $total_pages;
}

// Calculate offset
$offset = ($page - 1) * $limit;

// Fetch borrowing records for the current page
$sql = "SELECT
    borrowings.borrow_id,
    members.name,
    members.email,
    members.phone,
    books.title,
    books.isbn,
    borrowings.issue_date,
    borrowings.due_date,
    borrowings.status,
    books.quantity,
    books.quantity - (
        SELECT COUNT(*)
        FROM borrowings AS b
        WHERE b.book_id = books.book_id
        AND b.status = 'Issued'
    ) AS available_quantity
FROM borrowings
LEFT JOIN members ON members.member_id = borrowings.member_id
LEFT JOIN books ON books.book_id = borrowings.book_id
WHERE members.name LIKE '$search%'
OR members.phone LIKE '%$search%'
OR members.email LIKE '%$search%'
OR books.title LIKE '%$search%'
OR books.isbn LIKE '%$search%'
OR borrowings.issue_date LIKE '%$search%'
OR borrowings.due_date LIKE '%$search%'
ORDER BY borrowings.borrow_id DESC
LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>

<body class="bg-light">
    <div class="container-fluid mt-4 px-4"> 

<?php include "admin_sidebar.php"; ?>

<div class="main-content">
        <h2 class="fw-bold text-danger text-center mb-4">Borrowing List</h2>

        <form method="GET" action="borrowings.php" class="row g-2 mb-4">
            <div class="col-md-6">
            <input
                type="search"
                name="search"
                class="form-control"
                placeholder="Enter The Search Here"
                value="<?php echo htmlspecialchars($search); ?>"
            
            >
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">
                Search
                </button>
            </div>
            <div class="col-auto">
                <a href="borrowings.php" class=" btn btn-primary">
                 Clear
                </a>
            </div>
        </form>

        <br><br>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
        <table class="table table-stripped table-hover table-bordered align-middle">
            <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Available Quantity</th>
            </thead>
            </tr>
            <tbody>

            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['borrow_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['title'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['isbn'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['issue_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['available_quantity'] ?? '') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No borrowing records found.</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <br>

        <div class="pagination">

            <?php if ($page > 1) { ?>
                <a class='btn btn-outline-primary btn-sm'
                href="borrowings.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">
                    Previous
                </a>
            <?php } ?>

            <span class="mx-2" >
                Page <?php echo $page; ?> of <?php echo max(1, $total_pages); ?>
            </span>

            <?php if ($page < $total_pages) { ?>
                <a class='btn btn-outline-primary btn-sm'
                href="borrowings.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">
                    Next
                </a>
            <?php } ?>

        </div>
</div>
</div>

</body>
</html>
        