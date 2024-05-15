<?php
require("./backend/config.php");

// Check if the ID parameter is present in the URL
if (isset($_GET['id'])) {
	// Sanitize the ID to prevent SQL injection
	$artId = mysqli_real_escape_string($connection, $_GET['id']);

	// Fetch the art details from the database based on the ID
	$sql = "SELECT author, title, description, upload_date, image FROM posts WHERE id = '$artId'";
	$result = mysqli_query($connection, $sql);

	// Check if the art exists
	if (mysqli_num_rows($result) > 0) {
		$artDetails = mysqli_fetch_assoc($result);
	} else {
		echo "Art not found.";
		exit; // Stop further execution if the art is not found
	}
} else {
	echo "Invalid request.";
	exit; // Stop further execution if the ID parameter is not present in the URL
}
?>

<?php require("./components/header.inc.php"); ?>

<div id="wrapper">

	<?php require("./components/navbar.inc.php"); ?>

	<div id="main" class="alt">

		<section id="one">
			<div class="inner">
				<a href="index.php" class="button mb-4 px-4 py-0">Back</a>
				<header class="major">
					<h1><?php echo $artDetails['title']; ?></h1>

					<h4><i class="fa fa-user mr-3"></i><?php echo $artDetails['author']; ?> &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-calendar mr-3"></i> <?php echo $artDetails['upload_date']; ?></h4>
				</header>
				<span class="image main">
					<?php
					$imageData = base64_encode($artDetails['image']);
					$imageSrc = 'data:image/jpeg;base64,' . $imageData;
					?>
					<img src="<?php echo $imageSrc; ?>" alt="<?php echo $artDetails['title']; ?>" />
				</span>
				<p><?php echo $artDetails['description']; ?></p>
			</div>
		</section>

	</div>

	<?php require("./components/footer.inc.php") ?>
</div>