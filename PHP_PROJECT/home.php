<?php
// Start the session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

	<!-- Navigation Bar -->
<nav>
    <ul class="nav-list">
        <li class="nav-item"><a href="home.php" class="nav-link">HOME</a></li>
        <li class="nav-item"><a href="activity_feed.php" class="nav-link">MY ACTIVITIES</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">LOG OUT</a></li>
    </ul>
</nav>

<!-- Main Content for Home -->
<div class="categories">
    <h1>MY PERSONAL BLOG</h1>
	
    <div class="category-container">
        <a href="travel.php" class="category">
            <img src="upl/travel-icon.png" alt="Travel" class="icon">
            <span>Travel</span>
        </a>
        <a href="food.php" class="category">
            <img src="upl/food-icon.png" alt="Food" class="icon">
            <span>Food</span>
        </a>
        <a href="study.php" class="category">
            <img src="upl/study-icon.png" alt="Study" class="icon">
            <span>Study</span>
        </a>
        <a href="work.php" class="category">
            <img src="upl/work-icon.png" alt="Work" class="icon">
            <span>Work</span>
        </a>
        <a href="fitness.php" class="category">
            <img src="upl/workout-icon.png" alt="Fitness" class="icon">
            <span>Fitness</span>
        </a>
        <a href="others.php" class="category">
            <img src="upl/others-icon.png" alt="Others" class="icon">
            <span>Others</span>
        </a>
    </div>
</div>

</body>
</html>
