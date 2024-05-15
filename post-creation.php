<?php require("./components/header.inc.php"); ?>

<div id="wrapper">
    <?php require("./components/navbar.inc.php"); ?>

    <section id="banner" class="major">
        <div class="inner">
            <header class="major">
                <h1>Create a New Blog Post</h1>
            </header>
        </div>
    </section>

    <div id="main">
        <section class="inner">
            <div>
                <form action="./backend/create-post.php" method="post" enctype="multipart/form-data">
                    <label for="image">Upload Image:</label><br>
                    <div id="imagePreview"></div>
                    <input type="file" id="image" name="image" accept="image/*" style="display:none;">
                    <button type="button" onclick="document.getElementById('image').click();">Choose Image</button>
                    <br><br>

                    <label for="title">Title:</label><br>
                    <input type="text" id="title" name="title" required><br><br>

                    <label for="content">Content:</label><br>
                    <textarea id="content" name="content" rows="6" cols="50" required></textarea><br><br>

                    <input type="submit" value="Create Blog Post">
                </form>
            </div>
        </section>
    </div>

    <link rel="stylesheet" href="./assets/css/post-creation.css" />

    <script src="./assets/js/post-creation.js"></script>

    <?php require("./components/footer.inc.php") ?>
</div>
