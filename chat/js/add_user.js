var url = '/engine/user-chat.php';
dataLoad();
var interval = setInterval(dataLoad, 3000);
$(document).ready(function () {
    $('#chatroom').submit( function (e) {
        e.preventDefault();
        $.ajax({
            url: url,
            type: "POST",
            data: $('#message').serialize(),
            success: function() {
                dataLoad();
                $('#message').val('');
            },
            error: function () {
                dataLoad();
                $('#message').val('');
            }
        });
    });
});

function dataLoad(){
    $.ajax({
        url: url,
        type: "POST",
        data: "hello",
        success: function (r) {
            if(r.items) {
                var result="";
                r.items.forEach(item => {
                    result += renderMessage(item);
                })
                $('#chat-history').html(result);
            }
        }
    });
}

function renderMessage(item) {
    var sender="";
    if(item.isadmin === '1'){
        sender = "Администратор: "
    } else {
        sender = "Вы: "
    }
    var short_time = item.time.substr(11, 5)

    return `<div class="chat-message clearfix">
                    <div class="chat-message-content clearfix">
                        <span class="chat-time">${short_time}</span>
                        <h5>${sender}</h5>
                        <p>${item.message}</p>
                    </div> 
                </div>`;
}