<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Debugging: Check if user ID is being passed correctly
echo "Session User ID: " . htmlspecialchars($user_id);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'blog_system');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch posts by the logged-in user's ID
$sql = "SELECT * FROM activity_posts WHERE id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if (!$stmt) {
    die("SQL error: " . $conn->error);
}

// Bind parameters and execute
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch posts
$posts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Close the connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activity - Personal Blog</title>
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

<!-- Activity Feed -->
<div class="activity-feed-container">
    <h2>My Activities:</h2>

    <!-- Display all posts -->
    <div id="activity-header" class="activity-feed">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-container">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><strong>Activity Type: </strong><?php echo ucfirst(htmlspecialchars($post['type'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    
                    <!-- Display image if available -->
                    <?php if ($post['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" class="post-image">
                    <?php endif; ?>
                    
                    <!-- Display the creation date and time -->
                    <p><small>Posted on <?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts available yet.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
