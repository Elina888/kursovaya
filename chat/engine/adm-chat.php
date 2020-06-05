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

$message = isset($_GET['message']) ? $_GET['message'] : null; //Если от js пришло сообщение, записываем в переменную
$user_id = isset($_GET['id']) ? $_GET['id'] : null;

$result = array();

if(!empty($message) && !empty($user_id)) { // Если на вход пришло сообщение, добавляем его в бд
    $sql = 'INSERT INTO chat (message, user_id, isadmin) VALUES ("'.$message.'", "'.$user_id.'", 1)';
    $result['send_status'] = $db->query($sql);
} else {
 //Если сообщение не поступило, значит поступил запрос на взятие данных из бд
    $items = $db->query("SELECT * FROM `chat` WHERE `user_id` = ".$user_id); // Получаем сообщения, отпавленные и получаение пользователем
    // с id = $user_id

    if(!empty($items)){
        while($row = $items->fetch_assoc()){
            $result['items'][] = $row; //Добавляем их к ответу сервера
        }
    }
}

$db->close(); //закрываем соединение с бд
echo json_encode($result); // Отправяем ответ сервера