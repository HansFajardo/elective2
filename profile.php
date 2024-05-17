<?php
session_start();
require_once './backend/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $email = '';
$username_err = $email_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle profile picture upload
    if(isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0){
        $profile_picture = file_get_contents($_FILES["profile_pic"]["tmp_name"]);
    }

    if (empty(trim($_POST["username"]))) {
        $username_err = 'Please enter a username.';
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = 'Please enter an email.';
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($username_err) && empty($email_err)) {
        // Update username and email
        $sql = "UPDATE users SET username = ?, email = ?";
        $params = array($username, $email);

        // Check if profile picture is uploaded
        if(isset($profile_picture)){
            $sql .= ", picture = ?";
            $params[] = $profile_picture;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $user_id;

        if ($stmt = mysqli_prepare($connection, $sql)) {
            $types = str_repeat('s', count($params)); // Generate type string for bind_param
            mysqli_stmt_bind_param($stmt, $types, ...$params);

            if (mysqli_stmt_execute($stmt)) {
                header("location: profile.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
} else {
    $sql = "SELECT username, email FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $user_id;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $username, $email);
            mysqli_stmt_fetch($stmt);
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }
}

?>

<?php require("./components/header.inc.php"); ?>

<div id="wrapper">

    <?php require("./components/navbar.inc.php"); ?>

    <section id="banner" class="major">
        <div class="inner">
            <header class="major2">
                <h1>Edit Profile</h1>
            </header>
        </div>
    </section>

    <div id="main">
        <section class="inner">
            <div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="text-center">
                        <?php
                        $sql = "SELECT picture FROM users WHERE email = ?";
                        if ($stmt = mysqli_prepare($connection, $sql)) {
                            mysqli_stmt_bind_param($stmt, "s", $param_email);
                            $param_email = $_SESSION["email"];
                            if (mysqli_stmt_execute($stmt)) {
                                mysqli_stmt_bind_result($stmt, $profile_picture);
                                mysqli_stmt_fetch($stmt);
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            mysqli_stmt_close($stmt);
                        }

                        echo '<label for="profile_pic" style="color: #242943;">Profile Picture:</label><br>';
                        echo '<div id="imagePreview">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($profile_picture) . '" alt="Profile Picture" id="profile_picture" class="rounded-circle" width="200px" height="200px" <br/>';
                        echo '</div>';
                        ?>

                        <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="display:none;">
                        <button type="button" onclick="document.getElementById('profile_pic').click();">Choose Image</button>
                    </div>


                    <label for="username" style="color: #242943;">Username:</label><br>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>"><br><br>
                    <span class="text-danger"><?php echo $username_err; ?></span>

                    <label for="email" style="color: #242943;">Email:</label><br>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br><br>
                    <span class="text-danger"><?php echo $email_err; ?></span>

                    <input type="submit" class="btn btn-primary" value="Save Changes">
                </form>
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
            </div>
        </section>
    </div>

    <script src="./assets/js/edit-profile.js"></script>
    <?php mysqli_close($connection); ?>
    <?php require("./components/footer.inc.php") ?>
</div>