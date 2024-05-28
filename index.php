<?php
session_start();
require_once("./backend/config.php");

$sql = "SELECT id, author, title, description, upload_date, image FROM posts";
$result = mysqli_query($connection, $sql);

$posts = [];

if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$posts[] = $row;
	}
} else {
	echo "No posts found.";
}

mysqli_close($connection);
?>

<?php require("./components/header.inc.php"); ?>

<div id="wrapper">
	<?php require("./components/navbar.inc.php"); ?>

	<section id="banner" class="major">
		<div class="inner">
			<header class="major2">
				<h1>Welcome to Guhit Mo!</h1>
			</header>
			<div class="content">
				<p>The World in Your Hands!</p>
				<ul class="actions">
					<li><a href="javascript:void(0);" onclick="scrollToArtSection();" class="button button2 next scrolly">See Arts</a></li>
				</ul>
			</div>
		</div>
	</section>

	<div id="main">
		<section class="inner">
			<div>
				<header class="major">
					<h2>What is Guhit Mo?</h2>
				</header>
				<p>Welcome to Guhit Mo, the virtual sanctuary where art comes to life and creativity finds its home.
					Step into our digital gallery, where every stroke, every hue, and every expression paints a vibrant
					tapestry of imagination. Here, we celebrate the boundless spirit of artists from all walks of life,
					offering a cozy lounge where inspiration flows freely and appreciation knows no bounds.
					Whether you're a seasoned creator or an admirer of the arts, Guhit Mo invites you to explore,
					connect, and immerse yourself in a world where every masterpiece tells a story. Join us as we journey
					through the depths of creativity and unveil the beauty that lies within each Guhit (Stroke) — for here,
					art isn't just observed, it's experienced.</p>
			</div>
		</section>

		<section class="inner">
			<div>
				<header class="major">
					<h2>Our Mission</h2>
				</header>
				<p>Our mission is to provide a dynamic and supportive space for artists
					to showcase their work, share their stories, and connect with a broader
					audience. We strive to foster an appreciation for the arts by presenting
					diverse styles and perspectives, making art accessible and enjoyable for everyone.</p>
			</div>
		</section>

		<section id="art-section" class="inner">
			<header class="major">
				<h2>See some arts!</h2>
			</header>
			<div class="tiles d-flex flex-row justify-content-center">
				<?php foreach ($posts as $post) : ?>
					<article class="m-3">
						<span class="image">
							<?php
							$imageData = base64_encode($post['image']);
							$imageSrc = 'data:image/jpeg;base64,' . $imageData;
							?>
							<img src="<?php echo $imageSrc; ?>" alt="<?php echo $post['title']; ?>" style="width: 100%; height: auto;">
						</span>
						<header class="major2 text-white">
							<h4 style="color: #ffffff;"><?php echo $post['title']; ?></h4>
							<p><br> <span><?php echo $post['author']; ?></span> | <span><?php echo $post['upload_date']; ?></span></p>
							<div class="major-actions">
								<a href="art-details.php?id=<?php echo $post['id']; ?>" class="button button2 small next scrolly">Open Art</a>
							</div>
						</header>
					</article>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="inner">
			<div>
				<header class="major">
					<h2>Who We Are</h2>
				</header>
				<div class="row">
					<div class="col-6">
						<p><em>Guhit Mo is a collective of passionate individuals united by a
								love for art and a commitment to nurturing talent.
								Our team comprises artists, curators, and enthusiasts who believe
								in the transformative power of creativity. We aim to create an inclusive
								environment where every artist, regardless of their medium or experience
								level, feels valued and inspired.</em></p>
					</div>
				</div>
				<ul class="actions">
					<li><a href="aboutus.php" class="button next">About Us</a></li>
				</ul>
			</div>
		</section>
	</div>

	<?php require("./components/footer.inc.php") ?>
</div>