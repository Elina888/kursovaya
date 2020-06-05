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


$message = isset($_POST['message']) ? $_POST['message'] : null; //Если от js пришло сообщение, записываем в переменную


$false = false;
if($false){
//Тут проверяете, является ли пользователь зарегистрированным. Если да, то переменной $user_id присваиваете его id
}
else
{
   if(isset($_COOKIE['id'])){
        $user_id = $_COOKIE['id'];
   } else {
       $unique_id = $db->query('FROM chat SELECT min(user_id)') - 1; //Если пользователь не зарегистрирован
       //и данных по нему в cookie нет, создадим их
       //Для присвоения уникального id - возьмём минимальный id пользователя в бд и вычтем едицу. Очевидно, данный
       //пользователь станет уникальным
       setcookie("id", $unique_id, time() + 3600 * 12, "/","",0); //Устанавливаем куки на 12 часов, либо до закрытия браузера.
   }
}

$result = array(); //Тут хранится ответ от сервера
//Проверяем, почему, собственно был запущен php скрипт
if(!empty($message)) { // Если на вход пришло сообщение, добавляем его в бд
    $sql = 'INSERT INTO chat (message, user_id) VALUES ("'.$message.'", "'.$user_id.'")';
    $result['send_status'] = $db->query($sql);
}
else{ //Если сообщение не поступило, значит поступил запрос на взятие данных из бд
    $items = $db->query("SELECT * FROM `chat` WHERE `user_id` = ".$user_id); // Получаем сообщения, отпавленные и полученные пользователем
    // с id = $user_id

    if(!empty($items)){
        while($row = $items->fetch_assoc()){
            $result['items'][] = $row; //Добавляем их к ответу сервера
        }
    }
}
$db->close(); //закрываем соединение с бд
echo json_encode($result); // Отправяем ответ сервера
