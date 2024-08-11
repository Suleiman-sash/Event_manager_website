<?php
session_start();
include 'db.php';


// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Management System</title>
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
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                    <h2>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                    <h3>Your Events:</h3>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='event-card'>";
                            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                            echo "<p>". htmlspecialchars($row["time"]) . "<p>";
                            echo "<p>" . htmlspecialchars($row['date']) . "</p>";
                            echo "<div class='event-buttons'>";
                            echo "<a href='edit-event.php?id=" . $row['id'] . "' class='btn edit-btn'>Edit</a>";
                            echo "<a href='delete-event.php?id=" . $row['id'] . "' class='btn delete-btn'>Delete</a>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "No events found.";
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
