import { popupMessage } from './audio-handling.js'

export function toggleSettings() {
    var user_genres = [];
    var user_daws = [];
    var user_instruments = [];
    $(".head-btn").on('click', (e) => {
        console.log($(e.target).attr('panel'));
        $(".head-btn").removeClass("active-head");
        $("[panel=" + $(e.target).attr('panel') + "]").addClass("active-head");
        if ($(window).width() > 980) {
            $(".settings-body").css("margin-left", -800 * $(e.target).attr('panel') + "px");
        } else if ($(window).width() <= 980) {
            $(".settings-body").css("margin-left", -100 * $(e.target).attr('panel') + "vw");
        }
    })
    $.ajax({
        url: 'db/download/get-user-details.php',
        method: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data[0].public == 1) {
                $(".panel-switch").prop('checked', true);
                $(".panel-switch").prop('value', 0);
            }
            $("[value=username]").text(data[0].username)
            $("[value=fname]").text(data[0].firstname)
            $("[value=lname]").text(data[0].lastname)
            $("[value=email]").text(data[0].email)
            $("[value=acc-type]").text(data[0].acc_type)

            if (data[0].acc_type == 'Artist') {
                $(".user-daws").remove();
            }

            user_genres.push(data[0].genres.split(","))
            user_daws.push(data[0].daws.split(","))
            user_instruments.push(data[0].instruments.split(","))

            fetch("js/json/countries.json")
                .then(res => res.text())
                .then(json => {
                    var countries = JSON.parse(json);
                    try {
                        for (let i = 0; i < countries.length; i++) {
                            if (data[0].country == countries[i].code) {
                                $("[value=country]").append(`
                                `+ countries[i].name + `
                                <div class="flag-icon flag-icon-`+ countries[i].code + `" id="sel-country"></div>
                                `)
                            }
                        }
                    } catch {
                        throw Error(json);
                    }
                })
                .catch(console.error);
        }
    })
    setTimeout(() => {
        $.ajax({
            url: 'db/download/get-main-data.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                for (let i = 0; i < data[0].length; i++) {
                    if (user_genres[0].includes(data[0][i].id) == true) {
                        $(".ug-list").append(`
                        <div class="genre-item"><a href="#" class="profile-href">`+ data[0][i].genre + `</a></div>
                    `)
                    }
                }
                for (let i = 0; i < data[1].length; i++) {
                    if (user_daws[0].includes(data[1][i].id) == true) {
                        $(".ud-list").append(`
                    <div class="daw-item">
                    <img src="`+ data[1][i].logo_url.substring(1) + `">
                    </div>
                    `)
                    }
                }
                for (let i = 0; i < data[2].length; i++) {
                    if (user_instruments[0].includes(data[2][i].id) == true) {
                        $(".ui-list").append(`
                    <div class="instrument-item">
                    <img src="`+ data[2][i].logo_url.substring(1) + `">
                    <span>`+ data[2][i].instrument + `</span>
                    </div>
                    `)
                    }
                }
                $(".lb-settings").css("display", "none")
            }
        })
    }, 1000);


    $(".panel-switch").on('change', (e) => {
        $.ajax({
            url: 'db/user-actions.php?action=public-mode&public=' + e.target.value,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status == 1) {
                    e.target.value = 0
                } else if (response.status == 2) {
                    e.target.value = 1
                }
                popupMessage(response.message);
            }
        })
    })
}