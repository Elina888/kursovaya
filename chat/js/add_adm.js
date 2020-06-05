var url_adm_chat = '/engine/adm-chat.php';
var url_get_ids = '/engine/adm-get-ids.php';
chatUsersLoad();
var interval1 = setInterval(dataLoad, 3000);
var interval2 = setInterval(chatUsersLoad, 3000);
var cur_id = null;

$(document).ready(function () {
    $('#adm-submit').submit( function (e) {
        var adm_msg = $('#adm-msg');
        e.preventDefault();
        $.get(
            url_adm_chat,
            {
                id: cur_id,
                message: adm_msg.val()
            },
            dataLoad
        );
        adm_msg.val('');
    });
});

function dataLoad(id) {
    if(typeof id == "number"){
        cur_id = id;
    }
    if(cur_id) {
        $('#head').html(`ID пользователя: ${cur_id}`);
    }

    $.get(
        url_adm_chat,
        {
            id: cur_id
        },
        onSucsess
    );
}

function onSucsess(r) {
    var result = "";
    if(r.items) {
        r.items.forEach(item => {
            result += renderMessage(item);
        })
    }

    $('#adm-messages').html(result);
}


function chatUsersLoad() {
    if(cur_id) {
        $('#head').html(`<h1>ID пользователя: ${cur_id}</h1>`);
    }
    $.ajax({
        url: url_get_ids,
        type: "POST",
        data: "hello",
        success: function (r) {
            var result = "";
            r.forEach(id => {
                result += renderBtn(id.user_id);
            })
            $('#chat-select').html(result);
        }
    })
}

function renderBtn(id) {
    var user = "";
    if(parseInt(id) < 0){
        user = "Аноним #" + (parseInt(id)*(-1)).toString();
    } else {
        user = "Пользователь " + id;
    }

    return `<button type="submit" class="chat-icon" onclick="dataLoad(${id})">
                <h1>${user}</h1>
            </button>`;
}

function renderMessage(item) {
    var sender = "";
    if(item.isadmin === '1'){
        sender = "Вы:";
    } else {
        sender="Пользователь:"
    }

    return `<div class="chat-message clearfix">
                    <div class="chat-message-content clearfix">
                        <span class="chat-time">${item.time}</span>
                        <h5>${sender}</h5>
                        <p>${item.message}</p>
                    </div> 
                </div>`;
}