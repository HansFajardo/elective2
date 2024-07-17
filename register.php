<?php
require_once './backend/config.php';

$username = $email = $password = $confirm_password = '';
$username_err = $email_err = $password_err = $confirm_password_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = trim($_POST["username"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    if(isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0){
        $allowed_types = array('jpg', 'jpeg', 'png');
        $file_extension = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);

        if(in_array($file_extension, $allowed_types)){
            $picture = $_FILES["profile_pic"]["tmp_name"];
        } else {
            echo "Only JPG, JPEG, and PNG files are allowed.";
            exit;
        }
    } else {
        $picture = "./images/default_profile_pic.jpg";
    }

    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        $sql = "INSERT INTO users (username, email, password, picture) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_email, $param_password, $param_picture);

            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_picture = file_get_contents($picture); 

            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
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
    <style>
        .profile-pic-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-pic-upload img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile-pic-upload input[type="file"] {
            display: none;
        }

        .upload-btn {
            background-color: #007bff;
            color: #fff;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body style="background: linear-gradient(45deg, #242943, #445297);">
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="container text-center bg-white p-3 rounded-2" style="max-width: 400px;">
            <h2>Join the Gallery</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group profile-pic-upload">
                    <label class="ml-2">Profile Picture</label>
                    <?php
                        if(isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0){
                            echo '<img id="preview" src="'.htmlspecialchars($_FILES["profile_pic"]["tmp_name"]).'" alt="Profile Picture">';
                        } else {
                            echo '<img id="preview" src="./images/default_profile_pic.jpg" alt="Profile Picture">';
                        }
                    ?>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" onchange="previewImage(event)">
                    <label for="profile_pic" class="upload-btn">Upload</label>
                </div>

                <div class="form-group text-left">
                    <label class="ml-2">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="text-danger"><?php echo $username_err; ?></span>
                </div>

                <div class="form-group text-left">
                    <label class="ml-2">Email</label>
                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="text-danger"><?php echo $email_err; ?></span>
                </div>

                <div class="form-group text-left">
                    <label class="ml-2">Password</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="text-danger"><?php echo $password_err; ?></span>
                </div>

                <div class="form-group text-left">
                    <label class="ml-2">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                </div>

                <div class="form-group d-flex justify-content-around">
                    <input type="reset" class="btn btn-secondary" style="width: 30%;" value="Reset">
                    <input type="submit" class="btn btn-primary" style="width: 30%;" value="Submit">
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>
