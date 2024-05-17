<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $loggedIn = true;
    $userProfileName = isset($_SESSION["username"]) ? $_SESSION["username"] : "Unknown User";
} else {
    $loggedIn = false;
}
?>
<header id="header" class="alt">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid d-flex flex-row align-items-center">

            <a href="index.php" class="logo">
                <div class="d-flex flex-row align-items-center">
                    <img src="../images/GuhitMoLogo.png" width="50px" height="50px" />
                    <span>uhit</span> <strong>Mo</strong>
                </div>

            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <?php if ($loggedIn) : ?>
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $userProfileName; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="../profile.php">Profile</a>
                                <a class="dropdown-item" href="../post-creation.php">Create a Post</a>
                                <a class="dropdown-item" href="./backend/logout.php">Logout</a>
                            </div>
                        <?php else : ?>
                    <li> <a href="login.php">Login</a> </li>
                <?php endif; ?>
                </li>
                </ul>
            </div>
        </div>
    </nav>
</header>