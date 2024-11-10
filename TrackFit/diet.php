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
    $meal_type = $_POST['meal_type'];
    $description = $_POST['description'];
    $calories = $_POST['calories'];
    $date = $_POST['date'];

   
    $stmt = $conn->prepare("INSERT INTO diet_plans (user_id, meal_type, description, calories, date) VALUES (:user_id, :meal_type, :description, :calories, :date)");
    $stmt->execute([
        'user_id' => $user_id,
        'meal_type' => $meal_type,
        'description' => $description,
        'calories' => $calories,
        'date' => $date,
    ]);

    header("Location: diet.php");
    exit();
}


$dietQuery = $conn->prepare("SELECT * FROM diet_plans WHERE user_id = :user_id ORDER BY date DESC");
$dietQuery->execute(['user_id' => $user_id]);
$diets = $dietQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Diet</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <h1>Log Your Diet</h1>
        <nav>
            <a href="index.php">Dashboard</a> |
            <a href="exercise.php">Track Exercise</a> |
            <a href="progress.php">Record Progress</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section>
        <h2>Add New Meal</h2>
        <form method="POST" action="diet.php">
            <label for="meal_type">Meal Type:</label>
            <input type="text" id="meal_type" name="meal_type" required>
            
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
            
            <label for="calories">Calories:</label>
            <input type="number" id="calories" name="calories" required>
            
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            
            <button type="submit">Add Meal</button>
        </form>
    </section>

    <section>
        <h2>Your Diet Records</h2>
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
</body>
</html>
