<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";
$search="";
if(isset($_GET['search'])){
    $search=mysqli_escape_string($conn,$_GET['search']);
}
$limit=5;
$page=isset($_GET['page'] ) ? (int)$_GET['page']:1;
if($page<1){
    $page=1;

}
$offset=($page-1)*$limit;

$sql="SELECT * FROM members WHERE name LIKE '$search%' ORDER BY member_id DESC LIMIT $limit OFFSET $offset";
$result=mysqli_query($conn,$sql);

$count_sql = "SELECT COUNT(*) AS total FROM members
              WHERE name LIKE '$search%'";
$count_result = mysqli_query($conn, $count_sql);

$total_row = mysqli_fetch_assoc($count_result);
$total_members = $total_row['total'];

$total_pages = ceil($total_members / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body class="bg-light">
    <div class="container-fluid mt-4 px-4">
    <?php include "admin_sidebar.php";?>
    <div class="main-content">
    <h2 class="text-center text-primary fw-bold mb-4 ">VIEW MEMBERS</h2>
    <form method="GET" action="members.php" class="row g-2 mb-4">
        <div class="col-md-6">
        <input type="search" name="search" Placeholder="Search The name" class="form-control">
        </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>

            <div class="col-auto">
                <a href="members.php" class="btn btn-secondary">Clear</a>
            </div>
    </form>
    <br><br>
    <div class="card shadow-sm">
        <div class="table-responsive">
    <table class="table table-striped table-hover table-borderd align-middle">
        <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while($row=mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td>".$row['member_id']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['phone']."</td>";
            echo "<td>";
            echo "<a class='btn btn-sm btn-warning' href='edit_member.php?id=" . $row['member_id'] . "'>Edit</a> ";
            echo "  ";
            echo "<a class='btn btn-sm btn-danger' href='edit_member.php?id=" . $row['member_id'] . "'>Delete</a> ";
            echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <div class="pagination">
    <?php if ($page > 1) { ?>
        <a  class="btn btn-outline-primary btn-sm" href="members.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
    <?php } ?>

    <span class="mx-2 fw-bold">
        Page <?php echo $page; ?> of <?php echo max(1, $total_pages); ?>
    </span>

    <?php if ($page < $total_pages) { ?>
        <a class="btn btn-outline-primary btn-sm " href="members.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
    <?php } ?>
</div>
</div>
</body>
</html>