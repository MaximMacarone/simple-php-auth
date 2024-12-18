<?php
// Устанавливаем соединение с базой данных
$host = 'db'; // хост базы данных
$dbname = 'phpDB'; // имя вашей базы данных
$username = 'root'; // имя пользователя базы данных
$password = 'rootpassword'; // пароль пользователя

try {
    // Создаем подключение с использованием PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Устанавливаем режим ошибок для PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Обработка запроса, если форма была отправлена
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query'])) {
    $sql = $_POST['query'];
    try {
        // Выполнение запроса
        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $result = "Ошибка выполнения запроса: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Query Executor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        textarea {
            width: 100%;
            height: 200px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            font-family: monospace;
        }
        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>SQL Query Executor</h1>
<form method="POST">
    <label for="query">Введите SQL-запрос:</label>
    <textarea id="query" name="query" placeholder="Например: SELECT * FROM users"></textarea>
    <input type="submit" value="Выполнить запрос">
</form>

<?php if (isset($result)): ?>
    <h2>Результат запроса:</h2>
    <?php
    if (is_array($result)) {
        echo "<table>";
        echo "<tr>";
        // Печать заголовков столбцов
        foreach (array_keys($result[0]) as $column) {
            echo "<th>" . htmlspecialchars($column) . "</th>";
        }
        echo "</tr>";

        // Печать данных
        foreach ($result as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>" . htmlspecialchars($result) . "</p>";
    }
    ?>
<?php endif; ?>
</body>
</html>