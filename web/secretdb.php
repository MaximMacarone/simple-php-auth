<?php
// Настройки для подключения к базе данных
$host = 'db';  // или IP-адрес, если база данных находится на другом сервере
$db = 'phpDB'; // название базы данных
$user = 'root';        // имя пользователя для подключения к базе данных
$pass = 'rootpassword';            // пароль для подключения к базе данных
$charset = 'utf8mb4';

// Подключение к базе данных с использованием PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Обработка формы
if (!isset($_POST['name']) || !isset($_POST['password'])) {
    // Форма логина
    echo '<h1>Please Log In</h1>';
    echo 'This page is secret.';
    ?>
    <form method="post" action="secretdb.php">
        <table border="1">
            <tr>
                <th>Username</th>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Log In">
                </td>
            </tr>
        </table>
    </form>
    <?php
} else {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Получаем данные пользователя из базы данных
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $name, PDO::PARAM_STR);
// Выполнение запроса
    $stmt->execute();
// Получение результатов
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Если пользователь найден
    if ($user && $password==$user['password']) {
        // Если пароль верный
        echo '<h1>Here it is!</h1>';
        echo 'I bet you are glad you can see this secret page.';
    } else {
        // Если имя пользователя или пароль неверные
        echo '<h1>Go Away!</h1>';
        echo 'You are not authorized to view this resource.';
    }
}
?>