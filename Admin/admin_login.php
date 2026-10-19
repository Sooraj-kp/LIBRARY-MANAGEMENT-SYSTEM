<?php
include "db.php";
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {

            $admin = mysqli_fetch_assoc($result);

            if (password_verify($password, $admin['password'])) {

                session_regenerate_id(true);

                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];

                mysqli_stmt_close($stmt);

                header("Location: dashboard.php");
                exit;
            }
        }

        $error = "Invalid username or password.";

        mysqli_stmt_close($stmt);

    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow-sm border-0"
             style="width: 100%; max-width: 450px;">

            <div class="card-body p-4">

                <h2 class="text-center text-primary fw-bold mb-4">
                    ADMIN LOGIN
                </h2>

                <?php if ($error != "") { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php } ?>

                <form method="POST" action="">

                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="form-control"
                            placeholder="Enter your username"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>

                </form>

            </div>
        </div>

    </div>

</body>
</html>