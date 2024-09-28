import { popupMessage } from './audio-handling.js';

export var userDateISO = new Date().toISOString().slice(0, 10);
export var status;
var to_user_active;
var chat_active = false;
var keyPressed = false;
var chatUpdater;
var current_user;
var timer;
var last_message_id;
var screenwidth = $(window).width();

export function allMsgs() {
    $.ajax({
        url: 'db/download/get-to-users.php',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            current_user = data[1];
            if (data[0].length > 0) {
                // data[0].reverse();
                    // data[0][i][0]["chats"].reverse();
                for (let i = 0; i < data[0].length; i++) {
                    // data[0][i][0]["chats"].reverse();
                    setTimeout(() => {
                        $(".message-list").append(`
                    <div class="container-row msg-conversation-card" data="` + data[0][i][0]["user_id"] + `">
                    <img class="msg-user-profile-pic msg-user-profile-pic-`+ data[0][i][0]["user_id"] + `" src="db/` + data[0][i][0]["chats"][0].profile_pic_url.substring(3) + `" alt="">
                    <div class="sub-container msg-conversation-overview">
                    <div class="msg-sub-overview">
                    <span class="container-ln msg-to-from-user">
                    <a href="/user?user=` + data[0][i][0]["chats"][0].username + `" class="profile-href">` + data[0][i][0]["chats"][0].username + `</a>
                    </span>
                    <span class="msg-last-span msg-last-span-`+ data[0][i][0]["user_id"] + `">` + data[0][i][0]["chats"][0].message + `</span>
                    </div>
                    <span class="msg-time-received msg-date-`+ i +`"></span>
                    </div>
                    </div>
                    `)
                        if (data[0][i][0]["chats"][0].date_time.slice(0, 10) == userDateISO) {
                            $(".msg-date-" + i).html("Today, " + data[0][i][0]["chats"][0].date_time.split(" ").pop().slice(0, -3));
                        } else {
                            $(".msg-date-" + i).html(data[0][i][0]["chats"][0].date_time.slice(0, 10)
                                + ", " + (data[0][i][0]["chats"][0].date_time.split(" ").pop()).slice(0, -3));
                        }
                        if (data[0][i][0]["chats"][0].to_user == current_user && data[0][i][0]["chats"][0].seen == 0) {
                            $(".msg-last-span-" + data[0][i][0].user_id).addClass("msg-unseen");
                        }
                    }, 1000);
                }
                setTimeout(() => {
                    $("#no-msgs-banner").hide();
                }, 500);
                console.log(data);
            } else {
                setTimeout(() => {
                    $("#no-msgs-banner").show();
                }, 500);
            }
        },
        complete: function () {
            setTimeout(() => {
                $(".msg-conversation-card").click(function (e) {
                    if (e.target.className != "profile-href") {
                        openMsgView($(this).attr("data"));
                    }
                })
                $(".message-send-btn").click(() => {
                    sendMsg(to_user_active, $(".message-input").val(), "full");
                });
                $(".message-input").on('input', () => {
                    onMessage();
                });
                $(".message-input").on('keydown', function (e) {
                    const keyCode = e.which || e.keyCode;
                    if (keyCode === 13 && !e.shiftKey) {
                        e.preventDefault();
                        sendMsg(to_user_active, $(".message-input").val(), "full");
                    }
                });
            }, 1000);
        }
    })
}

export function updateAllMsgs() {
    $.ajax({
        url: 'db/download/get-to-users.php',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            for (let i = 0; i < data[0].length; i++) {
                data[0][i][0]["chats"].reverse();
                if (data[0][i][0]["chats"][0].message != null) {
                    $(".msg-last-span-" + data[0][i][0].user_id).text(data[0][i][0]["chats"][0].message)
                    if (data[0][i][0]["chats"][0].to_user == current_user && data[0][i][0]["chats"][0].seen == 0) {
                        $(".msg-last-span-" + data[0][i][0].user_id).addClass("msg-unseen");
                    }
                    if (data[0][i][0]["chats"][0].date_time.slice(0, 10) == userDateISO) {
                        $(".msg-date-" + i).html("Today, " + data[0][i][0]["chats"][0].date_time.split(" ").pop().slice(0, -3));
                    } else {
                        $(".msg-date-" + i).html(data[0][i][0]["chats"][0].date_time.slice(0, 10)
                            + ", " + (data[0][i][0]["chats"][0].date_time.split(" ").pop()).slice(0, -3));
                    }
                }
            }
        }
    })
}

export function sendMsg(id, message, type) {
    if (message == "") {
        popupMessage("message cannot be empty");
    } else {
        $(".message-input").val("");
        $.ajax({
            url: 'db/user-actions.php?action=msgSend&&message=' + message + '&&to_user=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status = 1) {
                    status = data.status;
                    popupMessage(data.message);
                } else {
                    status = data.status;
                    if (type == "full") {
                        $(".message-send-btn").css("flex", "0");
                    }
                    popupMessage(data.message);
                }
            }
        })
    }
}

function onMessage() {
    if ($(".message-input").val() == "") {
        $(".message-send-btn").css("flex", "0");
    } else {
        $(".message-send-btn").css("flex", "1");
    }
}

function fetchChat(to_user) {
    $.ajax({
        url: 'db/download/get-chat.php?to_user=' + to_user,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data[0].reverse();
            // console.log(data)
            $(".chat-container-body").empty();
            if (data[0][0].seen != 9) {
                last_message_id = data[0][0].id;
            }
            for (let i = 0; i < data[0].length; i++) {
                if (data[0][i].seen != 9) {
                    if (data[0][i].from_user == to_user) {
                        $(".chat-container-body").append(`
                        <div class="chat-piece to-chat-piece">
                        `+ data[0][i].message + `
                        </div>
                        `)
                    } else {
                        $(".chat-container-body").append(`
                        <div class="chat-piece from-chat-piece">
                        `+ data[0][i].message + `
                        </div>
                        `)
                    }
                    if (data[0][i].from_user == current_user) {
                        if (data[0][0].seen != 9) {
                            if (last_message_id == data[0][i].id) {
                                if (data[0][i].seen == 0) {
                                    last_message_id = data[0][i].id;
                                    $(".from-chat-piece").addClass("last-msg-sent");
                                } else {
                                    $(".from-chat-piece").addClass("last-msg-sent");
                                    $(".last-msg-sent").addClass("msg-seen");
                                }
                            } else {
                                last_message_id = data[0][i].id;
                            }
                        }
                    }
                } else {
                    if (data[0][i].from_user != data[1]) {
                        $(".typing-indicator").remove();
                        $(".chat-container-body").append(`
                        <div class="typing-indicator">typing...</div>
                        `)
                    }
                }
            }
        }
    })
}

export function openMsgView(to_user) {
    if (to_user != null) {
        msgSeen(to_user);
        clearInterval(chatUpdater);
        if (to_user_active == to_user && chat_active == true) {
            $(".chat-container").css("width", "0px")
            $(".chat-container").css("height", "0px")
            $(".chat-container").css("opacity", "0")
            $(".user-list").css("display", "flex")
            $("[data=" + to_user + "]").removeClass("chat-active")
            $(".side-bar").css("margin-left", "0vw")
            $(".audio-player").css("z-index", "5")
            $(".options-popup").css("bottom", "62px")
            chat_active = false;
        } else {
            $(window).scrollTop($(window).height())
            to_user_active = to_user;
            chat_active = true;
            if ($(window).width() <= 540) {
                $(".chat-container").css("width", "100vw")
                $(".chat-container").css("height", "calc(100vh - 47px)")
                $(".side-bar").css("margin-left", "-100vw")
                $(".user-list").css("display", "none")
                $(".audio-player").css("z-index", "0")
                $(".options-popup").css("bottom", "0px")
            }
            else {
                $(".chat-container").css("width", "400px")
                $(".chat-container").css("height", "494px")
            }
            $(".chat-container").css("opacity", "1")
            setTimeout(() => {
                $("[data=" + to_user + "]").addClass("chat-active")
            }, 100);
            $(".msg-conversation-card").each(function () {
                $(this).removeClass("chat-active")
            })
            fetchChat(to_user);
            chatUpdater = setInterval(() => {
                fetchChat(to_user);
                checkMsgSeen(to_user);
            }, 1000)
        }
        $(".message-input").on('input', () => {
            clearTimeout(timer);
            if (keyPressed == false) {
                if ($(window).width() <= 540) {
                    $(".options-popup").css("right", "-200px")
                }
                $.ajax({
                    url: 'db/user-actions.php?action=isTyping&to_user=' + to_user,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 1) {
                        }
                    }
                })
                keyPressed = true;
            }
            timer = setTimeout(() => {
                if ($(window).width() <= 540) {
                    $(".options-popup").css("right", "0px")
                }
                $.ajax({
                    url: 'db/user-actions.php?action=notTyping&to_user=' + to_user,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                    }
                })
                keyPressed = false;
            }, 1000)
        });
    }
}

export function msgSeen(to_user) {
    $.ajax({
        url: 'db/user-actions.php?action=msgSeen&to_user=' + to_user,
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            if (response.status == 1) {
                $(".msg-last-span-" + to_user).removeClass("msg-unseen");
            }
        }
    })
}

export function checkMsgSeen(to_user) {
    if ($(".chat-container").css("width") > "0px") {
        msgSeen(to_user)
    }
}

setTimeout(() => {
    $(".popup-message-btn").on('click', () => {
        if ($(".popup-message-btn").hasClass("active-head")) {
            $(".popup-message-btn").removeClass("active-head");
            $(".linear-home-feed-content").css("display", "flex")
            $(".grid-home-feed-content").css("display", "grid")
            $(".polaroid-home-feed-content").css("display", "flex")
            $(".sub-wrapper-sticky").css("display", "none")
            $("#top-wrapper").css("margin-top", "0px")
            openMsgView(to_user_active)
        } else {
            $(".popup-message-btn").addClass("active-head");
            $(".linear-home-feed-content").css("display", "none")
            $(".grid-home-feed-content").css("display", "none")
            $(".polaroid-home-feed-content").css("display", "none")
            $(".sub-wrapper-sticky").css("display", "flex")
            $("#top-wrapper").css("margin-top", "-220px")
        }
    })
}, 1000);

$(window).on("resize", function () {
    if (screenwidth !== $(window).width()) {
        if ($(window).width() > 540) {
            $(".sub-wrapper-sticky").css("display", "flex")
            $(".options-popup").css("right", "-200px")
        } else {
            $(".options-popup").css("right", "0px")
            $(".sub-wrapper-sticky").css("display", "none")
        }
        $("#top-wrapper").css("margin-top", "0px")
        $(".linear-home-feed-content").css("display", "flex")
        $(".grid-home-feed-content").css("display", "grid")
        $(".polaroid-home-feed-content").css("display", "flex")
        $(".popup-message-btn").removeClass("active-head");
        openMsgView(to_user_active)
    }
})