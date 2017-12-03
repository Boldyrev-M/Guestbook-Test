<?php
/**
 * Created by PhpStorm.
 * Date: 03.12.2017
 * Time: 18:45
 */

// таблица comments: commId, name, message, added
//  commId	int(11) unsigned Автоматическое приращение
//  name	tinytext
//  message	text
//  added	timestamp [CURRENT_TIMESTAMP]
//

date_default_timezone_set('Europe/Moscow');

if (isset($_POST["name"]) and isset($_POST["msg"])) {
    try {
        $mydb = new PDO("mysql:host=127.0.0.1:8889;dbname=guestbook;charset=UTF8", "root", "root");
    } catch (PDOException $e) {
        echo 'Подключение не удалось: ' . $e->getMessage();
    }
    $name = htmlspecialchars($_POST["name"]);
    if (!$name) $name = "Guest";
    $msg = htmlspecialchars($_POST["msg"]);
    if ($msg) {
        $nowdate = date("Y-m-d H:i:s");
        $sqlque = $mydb->prepare("INSERT into comments (name, message, added) VALUES(?,?,?)");
        try {
            $sqlque->execute(array($name, $msg, $nowdate));
        } catch (PDOException $e) {
            echo 'Не работает: ' . $e->getMessage();
        }
    }  // если сообщение не пустое
    unset($_POST["name"]);
    unset($_POST["msg"]);
} // добавление нового сообщения в книгу
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Guest book</title>
    </head>
    <body>
    <h1>Гостевая книга тест</h1>
    <form name="NewMessage" method="post">
        <h2>Ваше сообщение:</h2>
        Ваше имя:<input name="name" placeholder="Введите свое имя"><br>
        <textarea name="msg" placeholder="Введите свое сообщение"></textarea>
        <input type="submit" value="Отправить">
    </form>

<?php
// постим всю гостевую книгу
$sql = "SELECT commId, name, message, added FROM comments ORDER BY added DESC";
echo '<table border = "0" align = "left">
        <caption>Гостевая книга</caption>
        <tr><th>Автор</th><th>Сообщение</th><th>Когда добавлено</th></tr>';

foreach ($mydb->query($sql) as $row) {
    echo "<tr>" . "<td>" . $row['name'] . "</td>
    <td>" . $row['message'] . "</td>
    <td>" . $row['added'] . "</td>
</tr>";
}
echo '</table>';
