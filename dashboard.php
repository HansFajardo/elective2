<?php

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include the header file
require_once "./components/header.inc.php";
?>

<!-- Wrapper -->
<div id="wrapper">

    <?php require_once "./components/navbar.inc.php"; ?>

    <!-- Dashboard Content -->
    <section id="dashboard" class="major">
        <div class="inner">
            <header class="major">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
            </header>
            <p>This is your dashboard. You can customize it with your own content.</p>
            <p>Here, you can display user-specific content or provide links to other sections of the site.</p>
            <ul class="actions">
                <li><a href="#" class="button next">View Profile</a></li>
                <li><a href="#" class="button next">Edit Settings</a></li>
                <li><a href="logout.php" class="button next">Logout</a></li>
            </ul>
        </div>
    </section>

</div>

<?php require_once "./components/footer.inc.php"; ?>
