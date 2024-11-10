<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity = $_POST['activity'];
    $duration = $_POST['duration'];
    $calories_burned = $_POST['calories_burned'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO exercises (user_id, activity, duration, calories_burned, date) VALUES (:user_id, :activity, :duration, :calories_burned, :date)");
    $stmt->execute([
        'user_id' => $user_id,
        'activity' => $activity,
        'duration' => $duration,
        'calories_burned' => $calories_burned,
        'date' => $date,
    ]);

    header("Location: exercise.php");
    exit();
}

$exerciseQuery = $conn->prepare("SELECT * FROM exercises WHERE user_id = :user_id ORDER BY date DESC");
$exerciseQuery->execute(['user_id' => $user_id]);
$exercises = $exerciseQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Exercise</title>
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>
    <header>
        <h1>Track Your Exercise</h1>
        <nav>
            <a href="index.php">Dashboard</a> |
            <a href="diet.php">Log Diet</a> |
            <a href="progress.php">Record Progress</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section>
        <h2>Add New Exercise</h2>
        <form method="POST" action="exercise.php">
            <label for="activity">Activity:</label>
            <input type="text" id="activity" name="activity" required>
            
            <label for="duration">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" required>
            
            <label for="calories_burned">Calories Burned:</label>
            <input type="number" id="calories_burned" name="calories_burned" required>
            
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            
            <button type="submit">Add Exercise</button>
        </form>
    </section>

    <section>
        <h2>Your Exercises</h2>
        <?php if ($exercises): ?>
            <table>
                <tr>
                    <th>Activity</th>
                    <th>Duration (mins)</th>
                    <th>Calories Burned</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($exercises as $exercise): ?>
                    <tr>
                        <td><?= htmlspecialchars($exercise['activity']) ?></td>
                        <td><?= htmlspecialchars($exercise['duration']) ?></td>
                        <td><?= htmlspecialchars($exercise['calories_burned']) ?></td>
                        <td><?= htmlspecialchars($exercise['date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No exercises recorded yet.</p>
        <?php endif; ?>
    </section>
</body>
</html>
