<?php
//Для отправки Post запроса из js
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
//

// Подключение к бд, введете свои данные
$db = new mysqli("localhost", "admin", "admin", "chat");
if($db->connection_error) {
    die("Connection to chat database failed: " . $db->connection_error);
}

$ids = $db->query("SELECT DISTINCT `user_id` FROM `chat`"); //Получаем уникальные user_id, для создания списка чатов с пользователями.
$result = array();

while($row = $ids->fetch_assoc()){
     $result[] = $row; //Добавляем их к ответу сервера
}

$db->close();

echo json_encode($result);