<?php
session_start();
require_once './backend/config.php';

$username_or_email = $password = '';
$username_or_email_err = $password_err = $login_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_or_email_err = 'Please enter username or email.';
    } else {
        $username_or_email = trim($_POST["username"]);
    }

    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    if (empty($username_or_email_err) && empty($password_err)) {
        if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT id, username, email, password FROM users WHERE email = ?";
        } else {
            $sql = "SELECT id, username, email, password FROM users WHERE username = ?";
        }

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username_or_email);

            $param_username_or_email = $username_or_email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;

                            session_write_close();

                            header("location: index.php");
                            exit();
                        } else {
                            $login_err = 'Invalid username or password.';
                        }
                    }
                } else {
                    $login_err = 'Invalid username or password.';
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Guhit Mo</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
</head>

<body style="background: linear-gradient(45deg, #445297, #242943);">
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="container text-center bg-white p-3 rounded-2" style="max-width: 400px;">
            <div class="text-left">
                <button type="button" class="btn btn-secondary text-left" onclick="window.location.href='index.php';">Back</button>
            </div>
            <h2>Enter Studio</h2>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group text-left">
                    <label class="ml-2">Username or Email</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username_or_email; ?>">
                    <span class="text-danger"><?php echo $username_or_email_err; ?></span>
                </div>
                <div class="form-group text-left">
                    <label class="ml-2">Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="text-danger"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Login">
                </div>
                <p class="text-danger"><?php echo $login_err; ?></p>
                <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>

            </form>
        </div>
    </div>
</body>

</html>