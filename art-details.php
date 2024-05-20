<?php
session_start();
require_once './backend/config.php';

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

$sqlComments = "SELECT author, author_email, author_image, comment, comment_date FROM comments WHERE art_id = '$artId' ORDER BY comment_date DESC";
$resultComments = mysqli_query($connection, $sqlComments);
$comments = [];
if (mysqli_num_rows($resultComments) > 0) {
    while ($row = mysqli_fetch_assoc($resultComments)) {
        $comments[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $author = mysqli_real_escape_string($connection, $_SESSION['username']);
    $author_email = mysqli_real_escape_string($connection, $_SESSION['email']);
    $comment = mysqli_real_escape_string($connection, $_POST['comment']);

    $sqlImage = "SELECT picture FROM users WHERE email = ?";
    if ($stmt = mysqli_prepare($connection, $sqlImage)) {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $_SESSION["email"];
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $author_image);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        } else {
            echo "Error retrieving image: " . mysqli_error($connection);
        }
    }

    $sqlInsert = "INSERT INTO comments (art_id, author, author_email, author_image, comment) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($connection, $sqlInsert)) {
        mysqli_stmt_bind_param($stmt, "issss", $artId, $author, $author_email, $author_image, $comment);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: art-details.php?id=$artId");
            exit;
        } else {
            echo "Error inserting comment: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    }
}

?>

<?php require("./components/header.inc.php"); ?>

<div id="wrapper">
    <?php require("./components/navbar.inc.php"); ?>
    <div id="main">
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
                                            <?php if (!empty($comment['author_image'])) : ?>
                                                <span id="imagePreview">
                                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($comment['author_image']); ?>" alt="Profile Picture" class="profile-picture rounded-circle mr-2" width="40px" height="40px">
                                                </span>
                                            <?php endif; ?>
                                            <span class="comment-author"><?php echo $comment['author']; ?></span>
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
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
                    <form action="art-details.php?id=<?php echo $artId; ?>" method="POST">
                        <div>
                            <label for="comment" style="color: #242943;">Your Comment:</label>
                            <textarea name="comment" id="comment" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="mt-5">Submit Comment</button>
                    </form>
                <?php else : ?>
                    <p>Please <a href="login.php">log in</a> to leave a comment.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
    <?php require("./components/footer.inc.php") ?>
</div>