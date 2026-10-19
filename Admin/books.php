
<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}


$limit = 7;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$count_sql = "SELECT COUNT(*) AS total
              FROM books
              LEFT JOIN authors
                  ON books.author_id = authors.author_id
              LEFT JOIN category
                  ON books.category_id = category.category_id
              WHERE books.title LIKE '%$search%'
                 OR authors.author_name LIKE '%$search%'
                 OR category.category_name LIKE '%$search%'";

$count_result = mysqli_query($conn, $count_sql);

if (!$count_result) {
    die("Count query failed: " . mysqli_error($conn));
}

$count_row = mysqli_fetch_assoc($count_result);

$total_books = $count_row['total'];
$total_pages = (int) ceil($total_books / $limit);


if ($total_pages > 0 && $page > $total_pages) {
    $page = $total_pages;
    $offset = ($page - 1) * $limit;
}


$sql = "SELECT books.book_id, books.title, books.isbn,
               books.quantity, authors.author_name,
               category.category_name
        FROM books
        LEFT JOIN authors
            ON books.author_id = authors.author_id
        LEFT JOIN category
            ON books.category_id = category.category_id
        WHERE books.title LIKE '%$search%'
           OR authors.author_name LIKE '%$search%'
           OR category.category_name LIKE '%$search%' ORDER BY books.book_id  DESC
        LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Books query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>

<body class="bg-light">
    <div class="container-fluid mt-4 px-4">  
    <?php include "admin_sidebar.php"; ?>

    <div class="main-content">
    <h2 class="text-primary fw-bold text-center mb-4">AVAILABLE BOOKS</h2>

<form method="GET" action="books.php" class="row g-2 mb-4">

    <div class="col-md-6">
        <input
            type="search"
            name="search"
            class="form-control"
            placeholder="Search the book here"
            value="<?php echo htmlspecialchars($search); ?>"
        >
    </div>

    <div class="col-auto">
        <button type="submit" class="btn btn-success">
            Search
        </button>
    </div>

    <div class="col-auto">
        <a href="books.php" class="btn btn-secondary">
            Clear
        </a>
    </div>

</form>

    <br><br>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table responsive">
    <table class="table table-striped table-hover table-bordered align-middle">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>ISBN</th>
            <th>Quantity</th>
            <th>Author</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['book_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . htmlspecialchars($row['author_name'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['category_name'] ?? '') . "</td>";
            echo "<td>";
            echo "<a class='btn btn-sm btn-warning' href='edit_book.php?id=" . $row['book_id'] . "'>Edit</a> ";
            echo "<a class='btn btn-sm btn-danger' href='delete_book.php?id=" . $row['book_id'] . "'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }

        if ($total_books == 0) {
            echo "<tr><td colspan='7'>No books found.</td></tr>";
        }
        ?>
    </table>

    <br>

    <?php
    if ($page > 1) {
            echo "<a class='btn btn-outline-primary btn-sm' href='books.php?page=" . ($page - 1) .
                "&search=" . urlencode($search) . "'>Previous</a> ";
    }

    echo "<span class='mx-2 fw-bold'>Page " . $page . " of " . $total_pages . "</span>";

    if ($page < $total_pages) {
        echo "<a class='btn btn-outline-primary btn-sm' href='books.php?page=" . ($page + 1) .
             "&search=" . urlencode($search) . "'>Next</a>";
    }
    ?>
    </div>
</div>
</div>
</div>
</body>
</html>
