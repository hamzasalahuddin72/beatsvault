import { downloadUserTracks, homeFeedUpdate } from "./exports.js";
import { popupMessage } from "./audio-handling.js";

$(document).ready(function () {

    var mt701 = document.querySelector("#mt701");

    $("#mt700").change(function () {
        var cover_upload = document.querySelector("#cover-upload");
        var file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.addEventListener('load', function () {
                cover_upload.style.backgroundImage = "url('" + this.result + "')";
            });
            reader.readAsDataURL(file);
            $(".upload-span").html("Replace");
        }
    });

    $("#audio-upload").change(function () {
        var upload_previous_btn = document.querySelector("#upload-previous-btn");
        var beat_name_var = document.querySelector("#beat-name-var");
        var file = this.files[0];
        $.ajax({
            url: 'db/upload/track-upload.php?action=verify',
            method: 'POST',
            data: new FormData(document.querySelector(".upload-form")),
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $(".lb-test").css("display", "flex");
            },
            success: function (response) {
                $(".lb-test").css("display", "none");
                if (response.status == 1) {
                    uploadSpanChange(response.name);
                    upload_previous_btn.innerHTML = "Replace - " + file.name;
                    beat_name_var.value = file.name.replace(/\.[^/.]+$/, "");
                    mt701.value = file.name.replace(/\.[^/.]+$/, "");
                    popupMessage(response.message);
                } else {
                    uploadSpanError(response.message);
                    popupMessage(response.message);
                }
            }
        });
    });

    //show selected file name with visual changes
    function uploadSpanChange(message) {
        $("#audio-upload-span").html(message);
        $("#audio-upload-span").css("border-color", "white");
        $("#audio-upload-span").css("color", "white");
        $("#upload-next-btn").css("display", "block");
        setTimeout(() => {
            $("#upload-next-btn").css("transform", "scale(1)");
        }, 20);
    };
    //show error messages
    function uploadSpanError(message) {
        $("#audio-upload-span").html(message);
        $("#audio-upload-span").css("border-color", "red");
        $("#audio-upload-span").css("color", "red");
        $("#upload-next-btn").css("transform", "scale(0)");
        resetFileInput("#audio-upload");
        setTimeout(() => {
            $("#upload-next-btn").css("display", "none");
        }, 20);
        setTimeout(() => {
            $("#audio-upload-span").html("Upload beat");
            $("#audio-upload-span").css("border-color", "var(--blue)");
            $("#audio-upload-span").css("color", "var(--blue)");
        }, 2000);
    }
    //revert the upload span to normal
    function uploadSpanNormal() {
        $("#audio-upload-span").html("Upload beat");
        $("#audio-upload-span").css("border-color", "var(--blue)");
        $("#audio-upload-span").css("color", "var(--blue)");
        $("#upload-next-btn").css("transform", "scale(0)");
        setTimeout(() => {
            $("#upload-next-btn").css("display", "none");
        }, 20);
    }

    function resetFileInput(input) {
        $(input).val("");
    }

    function cancelUpload() {
        $(".meta-upload").trigger("reset");
        $("#cover-preview").attr("src", "");
        $(".upload-span").html("Upload cover");
        resetFileInput("#audio-upload");
        uploadSpanNormal();
        setTimeout(() => {
            $(".meta-upload").css("transform", "scale(0)");
            $("#upload-previous-btn").css("display", "none");
        }, 20);
        setTimeout(() => {
            $(".audio-upload").css("display", "flex");
            $(".meta-upload").css("display", "none");
        }, 40);
        setTimeout(() => {
            $(".audio-upload").css("transform", "scale(1)");
        }, 60);
    }

    $("#upload-exit").click(function () {
        cancelUpload();
        exitUploadPortal()
    });

    $("#upload-button").click(function () {
        uploadPortalToggle();
    });

    function nextUploadPortal() {
        setTimeout(() => {
            $(".audio-upload").css("transform", "scale(0)");
            $("#upload-next-btn").css("transform", "scale(0)");
            $("#upload-previous-btn").css("transform", "scale(1)");
        }, 20);
        setTimeout(() => {
            $(".meta-upload").css("display", "flex");
            $(".audio-upload").css("display", "none");
            $("#upload-next-btn").css("display", "none");
            $("#upload-previous-btn").css("display", "block");
        }, 40);
        setTimeout(() => {
            $(".meta-upload").css("transform", "scale(1)");
        }, 60);
    }

    $("#upload-next-btn").on('click', function () {
        nextUploadPortal();
    });

    function previousUploadPortal() {
        setTimeout(() => {
            $(".meta-upload").css("transform", "scale(0)");
            $("#upload-next-btn").css("transform", "scale(1)");
            $("#upload-previous-btn").css("transform", "scale(0)");
        }, 20);
        setTimeout(() => {
            $(".audio-upload").css("display", "flex");
            $(".meta-upload").css("display", "none");
            $("#upload-next-btn").css("display", "block");
            $("#upload-previous-btn").css("display", "none");
        }, 40);
        setTimeout(() => {
            $(".audio-upload").css("transform", "scale(1)");
        }, 60);
    }

    $("#upload-previous-btn").on('click', function (e) {
        e.preventDefault();
        previousUploadPortal();
    });

    $("#upload-publish-btn").on('click', function (e) {
        e.preventDefault();
        
        for (let i = 700; i < 706; i++) {
            $('#mt' + i).removeClass("error-field");
        }
        $("#cover-upload").addClass("cover-upload-before");
        $("#cover-upload").removeClass("cover-upload-empty");
        $(".error-message").hide();
        if ($("#mt700").val() == "") {
            errorField("#mt700", "Please upload a cover");
            $("#cover-upload").removeClass("cover-upload-before");
            $("#cover-upload").addClass("cover-upload-empty");
        } else if ($("#mt701").val() == "") {
            errorField("#mt701", "Give your beat a title");
        } else if ($("#mt702").val() == "") {
            errorField("#mt702", "What genre fits your beat?");
        } else if ($("#mt703").val() == "") {
            errorField("#mt703", "Please provide the BPM");
        } else if ($("#mt704").val() == "") {
            errorField("#mt704", "Please provide the music key");
        } else if ($("#mt705").val() == "") {
            errorField("#mt705", "Description is required");
        } else {
            $.ajax({
                url: 'db/upload/track-upload.php?action=upload',
                method: 'POST',
                data: new FormData(document.querySelector(".upload-form")),
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function () {
                    $(".lb-test").css("display", "flex");
                },
                success: function (response) {
                    if (response.status == 1) {
                        $(".lb-test").css("display", "none");
                        setTimeout(() => {
                            homeFeedUpdate();
                            downloadUserTracks();
                        }, 500);
                        cancelUpload();
                        exitUploadPortal();
                        popupMessage(response.message);
                    }
                    console.log(response);
                }
            });
        }
    })

    function errorField(input, message) {
        $(input).addClass("error-field");
        $(".error-message").show();
        $(".error-message").html(message);
    }

    function uploadPortalToggle() {
        $(".upload-tracks").addClass("upload-portal-fx-after");
        $(".upload-tracks").removeClass("upload-portal-fx-before");
        $("#upload-button").css("display", "none");
        $(".upload-portal").css("display", "flex");
    }

    function exitUploadPortal() {
        $(".upload-tracks").removeClass("upload-portal-fx-after");
        $(".upload-tracks").addClass("upload-portal-fx-before");
        $("#upload-button").css("display", "flex");
        $(".upload-portal").css("display", "none");
    }
});