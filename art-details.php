<?php
require("./backend/config.php");

// Fetch artwork details
if (isset($_GET['id'])) {
	$artId = mysqli_real_escape_string($connection, $_GET['id']);

	$sql = "SELECT author, title, description, upload_date, image FROM posts WHERE id = '$artId'";
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		$artDetails = mysqli_fetch_assoc($result);
	} else {
		echo "Art not found.";
		exit;
	}
} else {
	echo "Invalid request.";
	exit;
}

$sqlComments = "SELECT author, comment, comment_date FROM comments WHERE art_id = '$artId' ORDER BY comment_date DESC";
$resultComments = mysqli_query($connection, $sqlComments);
$comments = [];
if (mysqli_num_rows($resultComments) > 0) {
	while ($row = mysqli_fetch_assoc($resultComments)) {
		$comments[] = $row;
	}
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$author = mysqli_real_escape_string($connection, $_POST['author']);
	$comment = mysqli_real_escape_string($connection, $_POST['comment']);

	$sqlInsert = "INSERT INTO comments (art_id, author, comment) VALUES ('$artId', '$author', '$comment')";
	$resultInsert = mysqli_query($connection, $sqlInsert);

	if ($resultInsert) {
		header("Location: art-details.php?id=$artId");
		exit;
	} else {
		echo "Error: " . mysqli_error($connection);
	}
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

		<?php if (!empty($comments)) : ?>

			<section id="comments" class="comments-section">
				<div class="inner">
					<h2>Comments</h2>
					<ul class="comment-list">
						<?php foreach ($comments as $comment) : ?>
							<li class="comment-item">
								<article class="comment">
									<header class="comment-header">
										<div class="comment-meta d-flex flex-row align-items-center">
											<?php
											$email = $_SESSION['email'];
											$sqlProfilePic = "SELECT picture FROM users WHERE email = '$email'";
											$resultProfilePic = mysqli_query($connection, $sqlProfilePic);
											$profilePic = "";
											if (mysqli_num_rows($resultProfilePic) > 0) {
												$rowProfilePic = mysqli_fetch_assoc($resultProfilePic);
												$profilePic = $rowProfilePic['picture'];
											}
											?>
											<?php if (!empty($profilePic)) : ?>
												<span id="imagePreview">
													<img src="data:image/jpeg;base64,<?php echo base64_encode($profilePic); ?>" alt="Profile Picture" class="profile-picture rounded-circle mr-2" width="200px" height="200px">
												</span>
											<?php endif; ?>
											<span class="comment-author"><?php echo $_SESSION['username']; ?></span>

										</div>
										<div class="comment-date"><?php echo date('F j, Y, g:i a', strtotime($comment['comment_date'])); ?></div>
									</header>
									<div class="comment-content">
										<p><?php echo $comment['comment']; ?></p>
									</div>
								</article>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>



		<?php endif; ?>

		<section id="comment-form">
			<div class="inner">
				<h2>Leave a Comment</h2>
				<form action="art-details.php?id=<?php echo $artId; ?>" method="POST">
					<div>
						<label for="comment">Your Comment:</label>
						<textarea name="comment" id="comment" rows="4" required></textarea>
					</div>
					<button type="submit" class="mt-5">Submit Comment</button>
				</form>
			</div>
		</section>

	</div>
	<?php require("./components/footer.inc.php") ?>
</div>