import { audio, durationSpan, getCurrentTrackDetails, loopTrack, navigateTrack, navigateTrackDown, pauseTrack, playTrack, popupMessage, stopTrackForward } from "./audio-handling.js";
import { setLayout } from "./visual-functions.js";

export var trackList = [];
export var publicMode;

export function homeFeedUpdate() {
    var array = [
        'username',
        'title',
        'genre',
        'beat_desc',
        'file_url',
        'cover_url',
        'profile_cover_url',
        'profile_pic_url'];
    $.ajax({
        url: 'db/download/home-feed-update.php?array=' + array.join(","),
        method: 'POST',
        dataType: 'json',
        beforeSend: function () {
            $(".lb-home-feed").css("display", "flex");
        },
        success: function (data) {
            if (data.length > 0) {
                appendFeed(data, "newest", ".home-feed-content");
            }

            if (audio.src == "") {
                userTrackIndex()
            }
            $(".lb-home-feed").css("display", "none");
            $(".sub-wrapper").css("display", "flex");
        },
        error: function (error) {
            console.log(error);
        }
    })
}

export function appendFeed(data, sort, div) {
    $(div).empty();

    if (sort == "newest") {
        data.reverse();
        // data.sort((a, b) => b.id.localeCompare(a.id))
    } else if (sort == "oldest") {
        data.reverse()
    } else if (sort == "asc") {
        data.sort((a, b) => a.title.localeCompare(b.title))
    } else if (sort == "desc") {
        data.sort((a, b) => b.title.localeCompare(a.title))
    }

    trackList = data;

    for (let i = 0; i < data.length; i++) {
        $(div).append(` 
<div class="feed-content-card-wrapper">
<div class="feed-content-user-span">
<img class="user-profile-pic-small" src="db/`+ data[i].profile_pic_url.substring(3) + `"
    draggable="false" alt="">
<span class="feed-user-tag"><a href="/beatsvault/user?user=`+ data[i].username + `" class="profile-href">` +
            data[i].username + `</a>&nbsp;uploaded</span>
</div>
<div class="feed-content-card">
<div class="feed-album-cover" style="background-image: url('db/` + data[i].cover_url.substring(3) + `');">
<div class="play-popup --play-popup`+ i + `">
    <div class="icon-mask play-btn --play-btn`+ i + `" track="` + i + `" draggable="false"></div>
    <div class="icon-mask pause-btn --pause-btn`+ i + `" draggable="false"></div>
</div>
</div>
<div class="feed-sub-card">
<div class="feed-meta-card">
    <div class="track-metadata">
        <div class="tm track-title">`+ data[i].title + `</div>
        <div class="tm track-genre">
            <a class="tm link-btns track-genre-text" href="#">`+ data[i].genre + `</a>
        </div>
        <div class="tm track-desc">`+ data[i].beat_desc + `</div>
    </div>
</div>
<div class="user-options">
    <div class="feed-options">
        <div class="btn">
            <div class="icon-mask" id="like-btn"></div>
            <span class="tooltip">Like</span>
        </div>
        <div class="btn">
            <div class="icon-mask" id="comment-btn"></div>
            <span class="tooltip">Comment</span>
        </div>
    </div>
    <div class="sale-options">
        <div class="btn">
            <div class="icon-mask" id="buy-beat" draggable="false"></div>
            <span class="tooltip">Buy</span>
        </div>
        <div class="btn">
            <div class="icon-mask" id="add-cart" draggable="false"></div>
            <span class="tooltip">Add to cart</span>
        </div>
        <div class="btn">
            <div class="icon-mask" id="get-midi" draggable="false"></div>
            <span class="tooltip">Get MIDI</span>
        </div>
    </div>
</div>
</div>
</div>
</div>`)
    }

    $(".play-btn").click((e) => {
        playTrack($(e.target).attr('track'))
    });
    $(".pause-btn").click((e) => {
        pauseTrack($(e.target).attr('track'));
    });

    $(".master-previous-btn").click((e) => {
        navigateTrack(-1);
    });
    $(".master-previous-btn").mouseup((e) => {
        stopTrackForward();
    });
    $(".master-previous-btn").mousedown((e) => {
        navigateTrackDown(-1)
    });

    $(".master-next-btn").click((e) => {
        navigateTrack(1);
    });
    $(".master-next-btn").mouseup((e) => {
        stopTrackForward();
    });
    $(".master-next-btn").mousedown((e) => {
        navigateTrackDown(1)
    });

    $(".master-loop-btn").click((e) => {
        loopTrack();
    });

    setLayout(2)

}

export function userTrackIndex() {
    //initialize a song when user opens the website
    if (trackList.length > 0) {
        audio.src = "db/" + trackList[0].file_url.substring(3);
        audio.addEventListener('loadedmetadata', function () {
            var minutes = "0" + Math.floor((audio.duration - audio.currentTime) / 60);
            var seconds = "0" + Math.floor((audio.duration - audio.currentTime) - minutes * 60);
            var mmss = minutes.substr(-2) + ":" + seconds.substr(-2);
            // durationSpan.style.display = "flex";
            durationSpan.innerHTML = mmss;
        })
        $(".master-play-btn").click(() => {
            // playTrack(0);
            if (audio.paused) {
                playTrack(0);
            } else {
                pauseTrack();
            }
        })
        getCurrentTrackDetails(0)
        popupMessage("welcome to BEATSVAULT")
    } else {
        // userTracksEmpty = true;
    }
}

export function downloadUserTracks() {
    $(".upload-tracks").css("display", "none");
    $(".recent-tracks").empty();
    var array = [
        'cover_url',
        'title',
        'genre',
        'profile_cover_url',
        'profile_pic_url'
    ]
    $.ajax({
        url: 'db/download/get-user-tracks.php?array=' + array.join(","),
        method: 'GET',
        dataType: 'json',
        beforeSend: function () {
            $(".lb-recent-tracks").css("display", "flex");
        },
        success: function (data) {
            if (data.length == 0) {
                console.log("No user tracks found")
                $(".tracklist-options").css("display", "none");
                $(".recent-tracks").append(`
                <div class="message-banner">
                    <span>No beats in your vault</span>
                </div>
                `);
            } else {
                data.reverse();
                for (var i = 0; i < data.length; i++) {
                    $(".recent-tracks").append(` <div class="album-cover">
        <div class="album-cover-img" id="album-cover`+ data[i].id + `"
            style="background-image: url('db/` + data[i].cover_url.substring(3) + `')"></div>
        <div class="play-popup">
            <div class="icon-mask play-btn --play-btn`+ i + `" track="` + i + `" draggable="false"></div>
            <div class="icon-mask pause-btn --pause-btn`+ i + `" draggable="false"></div>
            <span onclick="showMetadata()">`+ data[i].title + `</span
        </div>
        </div>
        `);
                }
                if (data.length >= 10) {
                    $(".recent-tracks").append(`
        <div class="album-cover more-tracks">
            <a class="album-cover-href" href="beats.html">All beats</a>
        </div>`
                    )
                }
            }
        },
        complete: function () {
            $(".upload-tracks").css("display", "flex");
            $(".lb-recent-tracks").css("display", "none");
            $("[tracklist]").each(function () {
                $(this).click(function () {
                    console.log($(this).attr("tracklist"))
                })
            })
        }
    });
}

$(".sort-feed").change(function (e) {
    appendFeed(data, e.target.val(), ".user-profile-feed");
})

export function updateUserList() {
    $.ajax({
        url: 'db/user-get.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.length == 0) {
                $(".user-list").remove();
            } else {
                for (var i = 0; i < data.length; i++) {
                    $(".user-list-body").append(`
                <div class="container-row">
                <a class="profile-href" href="/beatsvault/user?user=`+ data[i].username + `"></a>
                <img class="user-profile-pic" src="db/`+ data[i].profile_pic_url.substring(3) + `" alt="">
                <div class="sub-container">
                    <div class="user-overview">
                        <a class="container-ln user-username">`+ data[i].username + `</a>
                        <a class="sel-type sel-`+ data[i].acc_type + `">` + data[i].acc_type + `</a>
                    </div>
                </div>
                </div>
                `)
                }
            }
        }
    })
}