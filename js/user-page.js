import { appendFeed } from "./exports.js";
import { popupMessage } from "./audio-handling.js";
import { sendMsg, status } from "./messenger.js";
import { switchLayout } from "./visual-functions.js";

var messagePopup = false;
var this_user;

export function userProfile(url) {
    var aa_wrapper = $("#aa-wrapper")
    aa_wrapper.empty();
    $.ajax({
        url: 'db/download/user.php?' + url,
        method: 'GET',
        dataType: 'json',
        beforeSend: function () {
            $(".lb-main").css("display", "flex");
        },
        success: function (data) {
            console.log(data)
            document.title = data[0][0].username + " - BEATSVAULT";
            this_user = data[0][0].username
            $(".lb-user-page").css("display", "flex");
            aa_wrapper.load(`user.php`)
            setTimeout(() => {
                $(".sel-type").html(data[0][0].acc_type)
                $(".sel-type").addClass("sel-" + data[0][0].acc_type)
                $("#sel-username").html(data[0][0].username)
                $("#sel-country").addClass("flag-icon-" + data[0][0].country)
                $("#profile-wrapper").css("background-image", "url('db/" + data[0][0].profile_cover_url.substring(3) + "')")

                getUserGenres(data[0][0].genres)

                if (data[0][1] == "current_user") {

                    $("#pp-update-label").append(`
                    <div id="profile-pic-frame">
                    <input class="radio-input" type="file" accept="image/*" name="update-profile-pic" id="pp-update"
                    style="display:none;">
                    </div>
                    <span class="tooltip">Update profile picture</span>
                    `)

                    $("#profile-pic-frame").css("background-image", "url('db/" + data[0][0].profile_pic_url.substring(3) + "')")

                    $("#pp-bg-update-label").append(`
                    Update profile cover
                    <input class="radio-input" type="file" accept="image/*" name="update-profile-cover" id="pp-bg-update"
                        style="display:none;">
                    `)

                    $(".link-btns").remove();

                    $("#pp-update").change(function () {
                        var file = this.files[0];
                        if (file) {
                            $.ajax({
                                url: 'db/update/profile-pic-update.php',
                                method: 'POST',
                                data: new FormData(document.getElementById("profile-wrapper")),
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                cache: false,
                                beforeSend: function () {
                                },
                                success: function (data) {
                                    popupMessage(data.message)
                                }
                            })
                            const reader = new FileReader();
                            reader.addEventListener('load', function () {
                                $("#profile-pic-frame").css("background-image", "url('" + this.result + "')")
                            });
                            reader.readAsDataURL(file);
                        }
                    });

                    $("#pp-bg-update").change(function () {
                        var file = this.files[0];
                        if (file) {
                            $.ajax({
                                url: 'db/update/profile-cover-update.php',
                                method: 'POST',
                                data: new FormData(document.getElementById("profile-wrapper")),
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                cache: false,
                                beforeSend: function () {
                                },
                                success: function (data) {
                                    popupMessage(data.message)
                                }
                            })
                            const reader = new FileReader();
                            reader.addEventListener('load', function () {
                                $("#profile-wrapper").css("background-image", "url('" + this.result + "')")
                            });
                            reader.readAsDataURL(file);
                        }
                    });

                    appendFeed(data[1], "newest", ".user-profile-feed")

                } else {
                    $("#pp-update-label").append(`
                    <div id="profile-pic-frame">
                    </div>
                    `)
                    $("#profile-pic-frame").css("background-image", "url('db/" + data[0][0].profile_pic_url.substring(3) + "')")

                    $(".user-message-btn").click(function () {
                        if (messagePopup == false) {
                            messagePopup = true;
                            $(".user-message-btn").css("background-color", "white")
                            $(".user-message-btn").css("color", "var(--blue)")
                            $(".chat-container-popup").css("display", "flex")
                        } else {
                            messagePopup = false;
                            $(".short-message-input").val() == ""
                            $(".user-message-btn").css("background-color", "var(--blue)")
                            $(".user-message-btn").css("color", "white")
                            $(".chat-container-popup").css("display", "none")
                        }

                        $(".short-message-send-btn").click(function (e) {
                            sendMsg(data[0][0].id, $(".short-message-input").val(), "short")
                            setTimeout(() => {
                                if (status == 1) {
                                    messagePopup = false;
                                    $(".user-message-btn").css("background-color", "var(--blue)")
                                    $(".user-message-btn").css("color", "white")
                                    $(".chat-container-popup").css("display", "none")
                                }
                            }, 500);
                            e.preventDefault();
                        });
                        $(".short-message-input").on('input', () => {
                            if ($(".short-message-input").val() == "") {
                                $(".short-message-send-btn").css("flex", "0");
                            } else {
                                $(".short-message-send-btn").css("flex", "1");
                            }
                        });
                    })

                    $(".user-follow-btn").click(function () {
                        $.ajax({
                            url: 'db/user-actions.php?action=' + $(this).attr("action") + '&to_user=' + data[0][0].id,
                            method: 'POST',
                            dataType: 'json',
                            success: function (data) {
                                console.log(data)
                                if (data.status == 1) {
                                    popupMessage("You are now following " + this_user)
                                    $(".user-follow-btn").addClass("is-following")
                                    $(".user-follow-btn").html("Following")
                                    $(".user-follow-btn").attr("action", "unfollow")
                                } else if (data.status == 2) {
                                    popupMessage("You have unfollowed " + this_user)
                                    $(".user-follow-btn").removeClass("is-following")
                                    $(".user-follow-btn").html("Follow")
                                    $(".user-follow-btn").attr("action", "follow")
                                } else {
                                    popupMessage(data.message)
                                }
                            }
                        })
                        return false;
                    });

                    if (data[0][1] == "is_followed") { // is a follower
                        $(".user-follow-btn").addClass("is-following")
                        $(".user-follow-btn").html("Following")
                        $(".user-follow-btn").attr("action", "unfollow")

                        if (data[1].length == 0) {
                            $(".tracklist-options").css("display", "none")
                            $(".user-profile-feed").append("The vault is empty")
                        } else {
                            appendFeed(data[1], "newest", ".user-profile-feed")
                        }
                    } else {
                        if (data[0][0].public == 0) { // private account
                            $(".tracklist-options").css("display", "none")
                            $(".user-profile-feed").append("The vault is locked 🔒")
                        } else { // public account
                            if (data[1].length == 0) {
                                $(".tracklist-options").css("display", "none")
                                $(".user-profile-feed").append("The vault is empty")
                            } else {
                                appendFeed(data[1], "newest", ".user-profile-feed")
                            }
                        }
                    }
                }

                $(".lb-user-page").css("display", "none");

                switchLayout()

                setTimeout(() => {
                    $(".sort-feed").change(function () {
                        appendFeed(data[1], $(this).val(), ".user-profile-feed")
                    })
                }, 1000);
            }, 1000);
        }
    });
}

export function getUserGenres(genres) {
    $(".genre-tray").empty();
    $.ajax({
        url: 'db/download/get-main-data.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var gen = [];
            gen.push(genres.split(","))

            for (let i = 0; i < data[0].length; i++) {
                if (gen[0].includes(data[0][i].id) == true) {
                    $(".genre-tray").append(`
                        <div class="genre-item"><a href="#" class="profile-href">`+ data[0][i].genre + `</a></div>
                        `)
                }
            }
        }
    })
}