<?php
session_start();
require_once './backend/config.php';

$username = $password = '';
$username_err = $password_err = $login_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = 'Please enter username.';
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: index.php");
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
    <title>Login</title>
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
            <p><?php echo $login_err; ?></p>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>

</html>
