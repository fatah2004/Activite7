<?php
// Database configuration
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

// Establish a database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Create tasks table if not exists
$createTableQuery = "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$mysqli->query($createTableQuery);

// Handle form submission to add a new task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task_title"])) {
    $taskTitle = $_POST["task_title"];
    $insertTaskQuery = "INSERT INTO tasks (title) VALUES ('$taskTitle')";
    $mysqli->query($insertTaskQuery);
}

// Retrieve tasks from the database, ordered by date (most recent first)
$getTasksQuery = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = $mysqli->query($getTasksQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
</head>
<body>

<nav>
    <h1>TodoList</h1>
</nav>

<form method="post" action="">
    <label for="task_title">Task Title:</label>
    <input type="text" id="task_title" name="task_title" required>
    <input type="submit" value="Add Task">
</form>

<table >
    <thead>
        <tr>
            <th>Task Title</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['title']}</td>";
            echo "<td><button>Undo</button> <button>Delete</button></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
