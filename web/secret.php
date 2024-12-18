<?php
if (!isset($_POST['name']) || !isset($_POST['password'])) {
    // Посетитель должен ввести имя и пароль
    echo '<h1>Please Log In</h1>';
    echo 'This page is secret.';
    ?>
    <form method="post" action="secret.php">
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
} elseif ($_POST['name'] === 'user' && $_POST['password'] === 'pass') {
    // Комбинация имени и пароля посетителя правильная
    echo '<h1>Here it is!</h1>';
    echo 'I bet you are glad you can see this secret page.';
} else {
    // Комбинация имени и пароля посетителя неправильная
    echo '<h1>Go Away!</h1>';
    echo 'You are not authorized to view this resource.';
}
?>