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

// Handle form submission for posting a work story
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $work_title = htmlspecialchars($_POST['work_title']);
    $work_content = htmlspecialchars($_POST['work_content']);

    // File upload logic
    $upload_dir = 'uploads/'; // Directory to save uploaded images
    $image_path = null;

    if (isset($_FILES['work_image']) && $_FILES['work_image']['error'] == 0) {
        $image_name = basename($_FILES['work_image']['name']);
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_ext, $allowed_extensions)) {
            $unique_name = uniqid() . '.' . $image_ext; // Create a unique filename
            $target_file = $upload_dir . $unique_name;

            if (move_uploaded_file($_FILES['work_image']['tmp_name'], $target_file)) {
                $image_path = $target_file; // Save the file path to the database
            } else {
                echo "<p>Error: Failed to upload image.</p>";
            }
        } else {
            echo "<p>Error: Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.</p>";
        }
    }

    // Insert the work post into the activity_posts table
    $stmt = $conn->prepare("INSERT INTO activity_posts (id, title, content, type, image_path) VALUES (?, ?, ?, 'work', ?)");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("isss", $user_id, $work_title, $work_content, $image_path);

    if ($stmt->execute()) {
        echo "<p>Your work post has been submitted successfully!</p>";
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
    <title>Work - Personal Blog</title>
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

<!-- Work Post Form -->
<div class="work-post-container">
    <h2>Post About Your Work Experience</h2>
    <form method="POST" action="work.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="work_title">Title of Your Work Post</label>
            <input type="text" id="work_title" name="work_title" required>
        </div>
        <div class="form-group">
            <label for="work_content">Share Your Work Story</label>
            <textarea id="work_content" name="work_content" rows="10" required></textarea>
        </div>
        <div class="form-group">
            <label for="work_image">Upload an Image</label>
            <input type="file" id="work_image" name="work_image" accept="image/*">
        </div>
        <button type="submit">Post Your Work Story</button>
    </form>
</div>

</body>
</html>
