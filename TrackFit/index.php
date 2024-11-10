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

$exerciseQuery = $conn->prepare("SELECT * FROM exercises WHERE user_id = :user_id ORDER BY date DESC LIMIT 5");
$exerciseQuery->execute(['user_id' => $user_id]);
$exercises = $exerciseQuery->fetchAll();

$dietQuery = $conn->prepare("SELECT * FROM diet_plans WHERE user_id = :user_id ORDER BY date DESC LIMIT 5");
$dietQuery->execute(['user_id' => $user_id]);
$diets = $dietQuery->fetchAll();

$progressQuery = $conn->prepare("SELECT * FROM progress WHERE user_id = :user_id ORDER BY date DESC LIMIT 5");
$progressQuery->execute(['user_id' => $user_id]);
$progressRecords = $progressQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <h1>Welcome to Your Fitness Tracker Dashboard</h1>
        <nav>
            <a href="exercise.php">Track Exercise</a> |
            <a href="diet.php">Log Diet</a> |
            <a href="progress.php">Record Progress</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section>
        <h2>Recent Exercises</h2>
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

    <section>
        <h2>Recent Diet Plans</h2>
        <?php if ($diets): ?>
            <table>
                <tr>
                    <th>Meal Type</th>
                    <th>Description</th>
                    <th>Calories</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($diets as $diet): ?>
                    <tr>
                        <td><?= htmlspecialchars($diet['meal_type']) ?></td>
                        <td><?= htmlspecialchars($diet['description']) ?></td>
                        <td><?= htmlspecialchars($diet['calories']) ?></td>
                        <td><?= htmlspecialchars($diet['date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No diet records yet.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Progress Records</h2>
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
