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

// Handle task deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_task"])) {
    $taskId = $_POST["delete_task"];
    $deleteTaskQuery = "DELETE FROM tasks WHERE id = $taskId";
    $mysqli->query($deleteTaskQuery);
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <span class="navbar-brand mb-0 h1">TodoList</span>
</nav>

<div class="container mt-4">
    <form method="post" action="" class="mb-3">
        <div class="form-group">
            <label for="task_title">Task Title:</label>
            <input type="text" class="form-control" id="task_title" name="task_title" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Task</button>
    </form>

    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Task Title</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['title']}</td>";
                echo "<td>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='delete_task' value='{$row['id']}'>";
                echo "<button type='submit' class='btn btn-danger'>Delete</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
