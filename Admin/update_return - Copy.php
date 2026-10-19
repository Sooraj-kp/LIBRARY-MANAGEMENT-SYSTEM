
<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include "db.php";

if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (int) $_GET['id'];
    $return_date = $_POST['return_date'];

    $sql = "UPDATE borrowings
            SET status = 'Returned',
                return_date = '$return_date'
            WHERE borrow_id = $id
            AND status = 'Issued'";

    if (mysqli_query($conn, $sql)) {
        header("Location: return_book.php?success=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Return Book</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="admin_style.css">
</head>

<body class="bg-light">
    <div class="container-fluid mt-4 px-4">

        <?php include "admin_sidebar.php"; ?>

        <div class="main-content">

            <h2 class="text-danger fw-bold text-center mb-4">
                RETURN BOOK
            </h2>

            <div class="row justify-content-center">

                <div class="col-12 col-md-8 col-lg-6">

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-danger text-white text-center">
                            <h5 class="mb-0">Confirm Book Return</h5>
                        </div>

                        <div class="card-body p-4">

                            <form method="POST">

                                <div class="mb-4">
                                    <label for="return_date"
                                           class="form-label fw-bold">
                                        Return Date:
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control"
                                        id="return_date"
                                        name="return_date"
                                        required
                                    >
                                </div>

                                <div class="text-center">

                                    <button type="submit"
                                            class="btn btn-success">
                                        Confirm Return
                                    </button>

                                    <a href="return_book.php"
                                       class="btn btn-secondary ms-2">
                                        Cancel
                                    </a>

                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>
</html>