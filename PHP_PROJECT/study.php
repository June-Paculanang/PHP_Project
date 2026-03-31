<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Include the database connection
include('db.php');

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Debugging: Check if user ID is being passed correctly
// echo "Session User ID: " . htmlspecialchars($user_id);

// Handle form submission for posting a study story
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $study_title = htmlspecialchars($_POST['study_title']);
    $study_content = htmlspecialchars($_POST['study_content']);

    // File upload logic
    $upload_dir = 'uploads/'; // Directory to save uploaded images
    $image_path = null;

    if (isset($_FILES['study_image']) && $_FILES['study_image']['error'] == 0) {
        $image_name = basename($_FILES['study_image']['name']);
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_ext, $allowed_extensions)) {
            $unique_name = uniqid() . '.' . $image_ext; // Create a unique filename
            $target_file = $upload_dir . $unique_name;

            if (move_uploaded_file($_FILES['study_image']['tmp_name'], $target_file)) {
                $image_path = $target_file; // Save the file path to the database
            } else {
                echo "<p>Error: Failed to upload image.</p>";
            }
        } else {
            echo "<p>Error: Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.</p>";
        }
    }

    // Insert the study post into the activity_posts table
    $stmt = $conn->prepare("INSERT INTO activity_posts (id, title, content, type, image_path) VALUES (?, ?, ?, 'study', ?)");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("isss", $user_id, $study_title, $study_content, $image_path);

    if ($stmt->execute()) {
        echo "<p>Your study post has been submitted successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study - Personal Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <ul class="nav-list">
        <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="activity_feed.php" class="nav-link">My Activities</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
    </ul>
</nav>

<!-- Study Post Form -->
<div class="study-post-container">
    <h2>Post About Your Study Experience</h2>
    <form method="POST" action="study.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="study_title">Title of Your Study Post</label>
            <input type="text" id="study_title" name="study_title" required>
        </div>
        <div class="form-group">
            <label for="study_content">Share Your Study Story</label>
            <textarea id="study_content" name="study_content" rows="10" required></textarea>
        </div>
        <div class="form-group">
            <label for="study_image">Upload an Image</label>
            <input type="file" id="study_image" name="study_image" accept="image/*">
        </div>
        <button type="submit">Post Your Study Story</button>
    </form>
</div>

</body>
</html>
