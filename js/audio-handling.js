import { trackList } from "./exports.js";

let songIndex = 0;
export let audio = new Audio();
let pausedAt = 0;
let valueHover = 0;
var popupTimer;
var interval;
var audioPopupTimer;
var mousedown = false;
var masterBtnDown;
var step;

//initialize variables
var completionBar = document.querySelector("#completion-bar");
var mediaDuration = document.querySelector("#media-duration");
export var durationSpan = document.querySelector("#duration-span");
var audioSeekSlider = document.querySelector("#audio-seek-slider");
var currentTrackDetails = document.querySelector("#current-track-details");
var globalPopupMsg = document.querySelector('.global-popup-message')

export function playTrack(id) {
    clearInterval(interval);
    if (songIndex != id) {
        songIndex = id;
        // audio.currentTime = 0;
        audio.src = "db/" + trackList[id].file_url.substring(3)
    }
    else {
        if (pausedAt == audio.currentTime) {
            audio.currentTime = pausedAt;
        }
    }
    audio.play()
    allPlays()
    interval = setInterval(() => {
        $(".--play-btn" + id).hide()
        $(".--pause-btn" + id).show()
        $(".--play-popup" + id).addClass("popup-playing")
    }, 100);
    $(".master-control").removeClass("master-play-btn")
    $(".master-control").addClass("master-pause-btn")
    getCurrentTrackDetails(id)
    popupMessage("Now playing" + " " + trackList[id].title)
}

export function pauseTrack() {
    clearInterval(interval);
    pausedAt = audio.currentTime;
    audio.pause();
    allPlays()
    $(".master-control").addClass("master-play-btn")
    $(".master-control").removeClass("master-pause-btn")
    popupMessage("Paused")
}

function allPlays() {
    $(".play-btn").each(function () {
        $(this).show()
    })
    $(".pause-btn").each(function () {
        $(this).hide()
    })
    $(".play-popup").each(function () {
        $(this).removeClass("popup-playing")
    })
}

export function getCurrentTrackDetails(id) {
    currentTrackDetails.innerHTML = `
                    <img id="current-track-cover-small" src="db/`+ trackList[id].cover_url.substring(3) + `" draggable="false">
                    <span class="media-player-text">` + trackList[id].title + `</span>`;
    setTimeout(() => {
        if (audio.paused) {
            var mediaPlayerText = document.querySelector(".media-player-text");
            mediaPlayerText.classList.remove("scrolling-media-text")
        } else {
            var mediaPlayerText = document.querySelector(".media-player-text");
            mediaPlayerText.classList.add("scrolling-media-text")
        }
    }, 1000);
}

export function popupMessage(msg) {
    clearTimeout(popupTimer)
    globalPopupMsg.innerHTML = msg;
    globalPopupMsg.style.top = "10px"
    popupTimer = setTimeout(() => {
        globalPopupMsg.style.top = "-47px"
    }, 2000);
}

export function navigateTrack(direction) {
    if (mousedown == false) {
        if (direction == "1") {
            if (songIndex < trackList.length - 1) {
                playTrack(songIndex + 1)
            } else {
                playTrack(0)
            }
        } else if (direction == "-1") {
            if (audio.currentTime > 2) {
                audio.currentTime = 0;
            } else {
                if (songIndex > 0) {
                    playTrack(songIndex - 1)
                } else {
                    playTrack(trackList.length - 1)
                }
            }
        }
    }
}

export function loopTrack() {
    if (audio.loop == true) {
        audio.loop = false;
        $(".master-loop-btn").addClass("loop-all")
        $(".master-loop-btn").removeClass("loop-one")
    } else {
        audio.loop = true;
        $(".master-loop-btn").addClass("loop-one")
        $(".master-loop-btn").removeClass("loop-all")
    }
    popupMessage("Loop " + (audio.loop ? "on" : "off"))
}

function calcSliderPos(e) {
    return (e.offsetX / e.target.clientWidth) * parseInt(e.target.getAttribute('max'), 10);
}

//attach to slider and fire on mousemove
function audioSeekSliderHover(e) {
    valueHover = calcSliderPos(e).toFixed(2);
    mediaDuration.style.background = "linear-gradient(to right, var(--lightblue) " + valueHover + "%, lightgray 0%, lightgray 40%)";
}

function audioSeekSliderMouseOut() {
    mediaDuration.style.background = "linear-gradient(to right, var(--lightblue) 0%, lightgray 0%, lightgray 100%)";
}

function audioSeekSliderChange(e) {
    var valueSeeked = e.target.value;
    audio.currentTime = Math.floor((valueSeeked / 100) * audio.duration);
    console.log(valueSeeked)
}

audioSeekSlider.addEventListener('mousemove', function (e) {
    audioSeekSliderHover(e);
});

audioSeekSlider.addEventListener('mouseout', function () {
    audioSeekSliderMouseOut();
});

audioSeekSlider.addEventListener('change', function (e) {
    audioSeekSliderChange(e);
});

document.addEventListener('keydown', (e) => {
    if (e.key === " "
        && !$('input:focus').length
        && !$('textarea:focus').length) {
        if (audio.paused) {
            playTrack(songIndex)
        } else {
            pauseTrack()
        }
    }
});

window.onkeydown = function (e) {
    return !(e.key == " " && e.target == document.body);
};

navigator.mediaSession.setActionHandler('play', () => {
    playTrack(songIndex)
});

navigator.mediaSession.setActionHandler('pause', () => {
    playTrack(songIndex)
});

export function navigateTrackDown(direction) {
    if (direction == 1) { // if forward track
        step = +1 / 2;
    } else if (direction == -1) { // if previous track
        step = -1 / 2;
    }
    masterBtnDown = setInterval(() => {
        audio.currentTime = audio.currentTime + step;
    }, 200);
    setTimeout(() => {
        mousedown = true
    }, 200);
}

export function stopTrackForward() {
    clearInterval(masterBtnDown)
    setTimeout(() => {
        mousedown = false
    }, 200);
}

audio.addEventListener('timeupdate', function () {
    var progress = (audio.currentTime / audio.duration) * 100;
    completionBar.style.width = progress + "%";

    //convert time format to mm:ss
    var minutes = "0" + Math.floor((audio.duration - audio.currentTime) / 60);
    var seconds = "0" + Math.floor((audio.duration - audio.currentTime) - minutes * 60);
    var mmss = minutes.substr(-2) + ":" + seconds.substr(-2);
    durationSpan.innerHTML = mmss;

    //if current track ends reset the track
    if (audio.currentTime === audio.duration) {
        pauseTrack(songIndex)
        audio.currentTime = 0;
        clearInterval(audioPopupTimer)
    }
})