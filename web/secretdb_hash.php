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

// Регистрация
if (isset($_POST['register'])) {
    $name = $_POST['reg_name'];
    $password = $_POST['reg_password'];

    if (!empty($name) && strlen($password) >= 6) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO hashed_users (username, password) VALUES (:username, :password)');
        $stmt->bindParam(':username', $name, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo 'Registration successful! You can now log in.';
        } else {
            echo 'Error during registration. Please try again.';
        }
    } else {
        echo 'Please fill in all fields and make sure the password is at least 6 characters long.';
    }
}

// Логин
if (!isset($_POST['login_name']) || !isset($_POST['login_password'])) {
    echo '<h1>Please Log In</h1>';
    ?>
    <form method="post" action="secretdb_hash.php">
        <table border="1">
            <tr>
                <th>Username</th>
                <td><input type="text" name="login_name"></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="login_password"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Log In">
                </td>
            </tr>
        </table>
    </form>
    <?php
    echo '<h1>New User? Register here:</h1>';
    ?>
    <form method="post" action="secretdb_hash.php">
        <table border="1">
            <tr>
                <th>Username</th>
                <td><input type="text" name="reg_name" required></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="reg_password" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="register" value="Register">
                </td>
            </tr>
        </table>
    </form>
    <?php
} else {
    $name = $_POST['login_name'];
    $password = $_POST['login_password'];

    $stmt = $pdo->prepare('SELECT * FROM hashed_users WHERE username = :username');
    $stmt->bindParam(':username', $name, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo '<h1>Here it is!</h1>';
        echo 'I bet you are glad you can see this secret page.';
    } else {
        echo '<h1>Go Away!</h1>';
        echo 'You are not authorized to view this resource.';
    }
}
?>