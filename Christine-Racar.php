<?php
session_start();
require("./components/header.inc.php");
?>

<div id="wrapper">

    <?php include("./components/navbar.inc.php"); ?>

    <div id="main">
        <section id="one">
            <div class="inner">
                <div>
                    <a href="aboutus.php" class="button" >Back</a>
                </div>
                <header class="mb-3 d-flex justify-content-between align-items-stretch" style="border-bottom: 2px solid #212121;">
                    <div style="flex: 0.8;">
                        <h2 class="mt-3">Christine Racar</h2>
                        <div class="contact-info mb-3">
                            <h4>Email: christine@example.com</h4>
                            <h4>Phone: (123) 456-7890</h4>
                        </div>
                        <div class="portfolio-description">
                            <p>
                                Christine Racar is an accomplished artist with a passion for creating stunning visual art.
                                With years of experience in various mediums, Christine's portfolio showcases her versatility and creativity.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center text-right">
                        <img src="images/christineRacar.jpg" alt="Christine Racar" class="rounded-circle w-100 h-auto object-fit-cover" style="max-width: 300px; max-height: 300px; border: 1px solid #212529;">
                    </div>
                </header>

                <div class="portfolio">
                    <h3>Portfolio</h3>
                    <div class="row d-flex justify-content-center" style="gap: 20px;">
                        <div class="col-md-4 col-sm-6 co-xs-12 text-center">
                            <img src="images/Christine-Portfolio/art1.jpg" class="img-responsive portfolio-image w-auto h-100 border border-secondary" alt="Art 1">
                        </div>
                        <div class="col-md-4 col-sm-6 co-xs-12 text-center">
                            <img src="images/Christine-Portfolio/art2.jpg" class="img-responsive portfolio-image w-auto h-100 border border-secondary" alt="Art 2">
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <?php require("./components/footer.inc.php") ?>
</div>