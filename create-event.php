<?php

session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO events (title, description, date, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $date, $user_id);

    if ($stmt->execute()) {
        echo "Event created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Event Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-dark text-light p-4 d-none">
            <button id="sidebar-cancel" class="btn btn-light">×</button>
            <h4>Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a href="create-event.html"><i class="fas fa-calendar-plus"></i> Create Event</a>
                </li>
                <li class="nav-item">
                <li>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </li>
            </ul>
        </div>

        <!-- Main content -->
        <div id="main-content" class="container-fluid">
            <header class="navbar navbar-expand-md navbar-dark bg-primary">
                <a class="navbar-brand" href="#">Event Manager</a>
                <button id="sidebar-toggle" class="btn btn-light ml-auto">☰</button>
            </header>

            <main>
                <div class="container mt-4">
                    <h2>Create Event</h2>
                    <form action="create-event.php" method="POST">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Time:</label>
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Event</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html>
