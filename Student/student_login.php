
<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM student_accounts WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("SQL error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {

            session_regenerate_id(true);

            $_SESSION['stud_id'] = $row['stud_id'];
            $_SESSION['member_id'] = $row['member_id'];
            $_SESSION['username'] = $row['username'];

            mysqli_stmt_close($stmt);

            header("Location: student_dashboard.php");
            exit;

        } else {
            $error = "Incorrect password.";
        }

    } else {
        $error = "Username not found.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">

        <div class="card shadow p-4" style="max-width: 420px; width: 100%;">

            <h2 class="text-primary text-center mb-2">
                Student Login
            </h2>

            <p class="text-muted text-center mb-4">
                Login to access your library account
            </p>

            <?php if ($error != "") { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <form method="POST" action="student_login.php">

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>
                <p class="text-center mt-3 mb-0">
                    Don't have an account?
                    <a href="student_register.php" class="text-primary text-decoration-none fw-bold">
                    Register here
                    </a>
                </p>

            </form>
        </div>

    </div>

</body>
</html>
