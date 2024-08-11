<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, date=?, time=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssii", $title, $description, $date, $time, $id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "Event updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch event data for pre-population
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar and header -->
        <!-- (Same as index.php) -->
        
        <main>
            <div class="container mt-4">
                <h2>Edit Event</h2>
                <form action="edit-event.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($event['date']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Time:</label>
                        <input type="time" id="time" name="time" class="form-control" value="<?php echo htmlspecialchars($event['time']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Event</button>
                </form>
            </div>
        </main>
    </div>
    <script src="app.js"></script>
</body>
</html>
