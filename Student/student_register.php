
<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $member_id = (int) $_POST['member_id'];

    if (strlen($password) < 8) {

        $error = "Password must contain at least 8 characters.";

    } else {

        $check_sql = "SELECT member_id FROM members WHERE member_id = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param($check_stmt, "i", $member_id);
        mysqli_stmt_execute($check_stmt);

        $result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($result) > 0) {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_sql = "INSERT INTO student_accounts
                           (member_id, username, password)
                           VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conn, $insert_sql);

            mysqli_stmt_bind_param(
                $stmt,
                "iss",
                $member_id,
                $username,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_stmt_close($check_stmt);

                header("Location: student_login.php");
                exit;
            } else {
                if (mysqli_stmt_errno($stmt) == 1062) {
                    $error = "This Member ID or username is already registered.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }

            mysqli_stmt_close($stmt);

        } else {
            $error = "Invalid Member ID. Please check your Member ID.";
        }

        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f4f7fb;
        }

        .register-card {
            max-width: 450px;
            width: 100%;
            border: none;
            border-radius: 12px;
        }

        .register-title {
            color: #173b65;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div class="container min-vh-100 d-flex
                justify-content-center align-items-center py-4">

        <div class="card register-card shadow p-4 p-md-5">

            <h2 class="register-title text-center mb-2">
                Student Registration
            </h2>

            <p class="text-secondary text-center mb-4">
                Create your library account
            </p>

            <?php if ($error != "") { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <form method="POST" action="student_register.php">

                <div class="mb-3">
                    <label for="member_id" class="form-label">
                        Member ID
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="member_id"
                        name="member_id"
                        min="1"
                        required
                        placeholder="Enter your Member ID"
                        value="<?php echo isset($_POST['member_id']) ? htmlspecialchars($_POST['member_id']) : ''; ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        required
                        maxlength="100"
                        placeholder="Choose a username"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
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
                        required
                        minlength="8"
                        placeholder="Minimum 8 characters"
                    >

                    <div class="form-text">
                        Your password must contain at least 8 characters.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Register
                </button>

            </form>

            <p class="text-center mt-4 mb-0">
                Already have an account?
                <a href="student_login.php"
                   class="text-decoration-none fw-semibold">
                    Login here
                </a>
            </p>

        </div>

    </div>

</body>
</html>
