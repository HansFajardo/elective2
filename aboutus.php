<?php
session_start();
require("./components/header.inc.php") ?>

<div id="wrapper">

	<?php include("./components/navbar.inc.php"); ?>


	<div id="main">

		<section id="one">
			<div class="inner">
				<header class="major">
					<h1>Our team members</h1>
				</header>

				<div class="row">
					<div class="col-md-4 col-sm-6 co-xs-12 text-center">
						<a href="Christine-Racar.php">
							<img src="images/christineRacar.jpg" class="img-responsive square-image" alt="" width="200px" height="200px" style="object-fit: cover;" />
							<h3>Christine Racar</h3>
						</a>

					</div>

					<div class="col-md-4 col-sm-6 co-xs-12 text-center">
						<a href="Daniel-Rol.php">
							<img src="images/danielRol.jpg" class="img-responsive square-image" alt="" width="200px" height="200px" style="object-fit: cover;">
						</a>
						<h3>Daniel Rol</h3>

					</div>

					<div class="col-md-4 col-sm-6 co-xs-12 text-center">
						<a href="Daryl-Cornejo.php">
							<img src="images/darylCornejo.jpg" class="img-responsive square-image" alt="" width="200px" height="200px" style="object-fit: cover;">
						</a>
						<h3>Daryl Cornejo</h3>

					</div>

					<div class="col-md-6 col-sm-6 co-xs-12 text-center">
						<a href="Diana-Lazaro.php">
							<img src="images/dianaLazaro.jpg" class="img-responsive square-image" alt="" width="200px" height="200px" style="object-fit: cover;">
						</a>
						<h3>Diana Lazaro</h3>

					</div>

					<div class="col-md-6 col-sm-6 co-xs-12 text-center">
						<a href="Nicole-Danganan.php">
							<img src="images/nicoleDanganan.jpg" class="img-responsive square-image" alt="">
						</a>
						<h3>Nicole Danganan</h3>

					</div>

				</div>
			</div>
		</section>

	</div>

	<?php require("./components/footer.inc.php") ?>