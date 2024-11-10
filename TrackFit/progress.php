<?php
// progress.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Handle progress form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight = $_POST['weight'];
    $body_fat_percentage = $_POST['body_fat_percentage'];
    $date = $_POST['date'];

    // Insert progress record into the database
    $stmt = $conn->prepare("INSERT INTO progress (user_id, weight, body_fat_percentage, date) VALUES (:user_id, :weight, :body_fat_percentage, :date)");
    $stmt->execute([
        'user_id' => $user_id,
        'weight' => $weight,
        'body_fat_percentage' => $body_fat_percentage,
        'date' => $date,
    ]);

    header("Location: progress.php");
    exit();
}

// Fetch all progress records for the user
$progressQuery = $conn->prepare("SELECT * FROM progress WHERE user_id = :user_id ORDER BY date DESC");
$progressQuery->execute(['user_id' => $user_id]);
$progressRecords = $progressQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Progress</title>
    <link rel="stylesheet" href="assets/style.css"> <!-- Link to your CSS -->
</head>
<body>
    <header>
        <h1>Record Your Progress</h1>
        <nav>
            <a href="index.php">Dashboard</a> |
            <a href="exercise.php">Track Exercise</a> |
            <a href="diet.php">Log Diet</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section>
        <h2>Add New Progress</h2>
        <form method="POST" action="progress.php">
            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" name="weight" step="0.1" required>
            
            <label for="body_fat_percentage">Body Fat (%):</label>
            <input type="number" id="body_fat_percentage" name="body_fat_percentage" step="0.1" required>
            
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            
            <button type="submit">Add Progress</button>
        </form>
    </section>

    <section>
        <h2>Your Progress Records</h2>
        <?php if ($progressRecords): ?>
            <table>
                <tr>
                    <th>Weight (kg)</th>
                    <th>Body Fat (%)</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($progressRecords as $progress): ?>
                    <tr>
                        <td><?= htmlspecialchars($progress['weight']) ?></td>
                        <td><?= htmlspecialchars($progress['body_fat_percentage']) ?></td>
                        <td><?= htmlspecialchars($progress['date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No progress records yet.</p>
        <?php endif; ?>
    </section>
</body>
</html>
