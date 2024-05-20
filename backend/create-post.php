<?php
session_start();

require_once("./config.php");

$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }

    $author = $_SESSION["username"];
    $author_email = $_SESSION["email"];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $upload_date = date("Y-m-d");

    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        echo "Error uploading image.";
        exit;
    }

    $stmt = $connection->prepare("INSERT INTO posts (author, author_email, title, description, upload_date, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $author, $author_email, $title, $content, $upload_date, $image_data);

    if ($stmt->execute()) {
        $success_message = '<div id="successMessage" style="position: fixed; top: 20px; right: 20px; background-color: #4CAF50; color: white; padding: 10px; border-radius: 5px; z-index: 999;">Art post created successfully!</div>';
        header("Location: /post-creation.php");
        exit;
    } else {
        echo "Error creating blog post.";
    }

    $stmt->close();
}

$connection->close();
?>