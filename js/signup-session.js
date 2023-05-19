var globalProgressBar = document.querySelector('#global-progress-bar')
var statusBarWrapper = document.querySelector('#status-bar-wrapper')
var lbMain = document.querySelector('.lb-main')
var lbTags = document.querySelector('.lb-tags')
var nextSlide4 = document.querySelector('#next-slide-4')
var nextSlide5 = document.querySelector('#next-slide-5')
var nextSlide6 = document.querySelector('#next-slide-6')
var nextSlide7 = document.querySelector('#next-slide-7')
var bpmRangeInput = document.querySelector('#bpm-range-input')
var globalRangeTooltip = document.querySelector('.global-range-tooltip')
var slideWrapper = document.querySelectorAll('.slide-wrapper')
var accTypeSlide = document.querySelector('.acc-type-slide')
var accGenderSlide = document.querySelector('.acc-gender-slide')
var accCountrySlide = document.querySelector('.acc-country-slide')
var accProducerPrefSlides = document.querySelectorAll('.acc-producer-pref')
var accArtistPrefSlides = document.querySelectorAll('.acc-artist-pref')
var searchEngine = document.querySelector('#search-engine')
var slider = document.querySelector('#slider')

var accType = null
var accTypeIndex = null
var genderIndex = null
var countryIndex = null
var genresSelected = []
var dawsSelected = []
var tagsSelected = []
var selectedTagIndex = []
var instrumentsSelected = []
var value;
var inc = 0;

var completeArr = [];

const onConfirmRefresh = function (event) {
    event.preventDefault();
    return event.returnValue = "Are you sure you want to leave the page?";
}

// window.addEventListener("beforeunload", onConfirmRefresh, { capture: true });

document.onkeydown = function (evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27) {
        viewChange(0)
    }
};

function continueSignup(index, value) {

    var selectAccTypeInput = document.querySelector(
        "input[name='user-account-type']:checked"
    )
    var selectGenderInput = document.querySelector(
        "input[name='user-gender']:checked"
    )
    var selectCountryInput = document.querySelector(
        "input[name='user-selected-country']:checked"
    )
    var fileInput = document.querySelector("#user-profile-pic");

    if (index == 1) {
        // if acc type selected
        accType = value
        console.log(accType)
        changeCheckedState(index, value)
        if (accType == 'Producer') {
            setPreferencesForm(accProducerPrefSlides, accArtistPrefSlides)
        } else if (accType == 'Artist') {
            dawsSelected = ["0"]
            setPreferencesForm(accArtistPrefSlides, accProducerPrefSlides)
        }
        viewChange(index)
    }


    else if (index == 2) {
        // if gender selected
        console.log(selectGenderInput.value)
        changeCheckedState(index, value)
        viewChange(index)
    }


    else if (index == 3) {
        var pp_upload = document.querySelector("#profile-pic-upload");
        var upload_icon = document.querySelector("#profile-pic-preview");
        var file = fileInput.files[0];
        var allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        if (file) {
            if (allowedExtensions.indexOf(file.name.split('.').pop().toLowerCase()) == -1) {
                pp_upload.classList.add("select-card-btn")
                pp_upload.classList.remove("select-card-btn-pp")
                pp_upload.style.backgroundImage = "";
                upload_icon.style.display = "block";
                nextSlide4.style.display = 'none';
                $(".pp-upload-span").html("Invalid file type");
                $(".pp-upload-span").css("border-color", "red");
                $(".pp-upload-span").css("color", "red");
                setTimeout(() => {
                    $(".pp-upload-span").html("Upload a picture");
                    $(".pp-upload-span").css("border-color", "var(--blue)");
                    $(".pp-upload-span").css("color", "var(--blue)");
                }, 2000);
                return false;
            } else {
                const reader = new FileReader();
                reader.addEventListener('load', function () {
                    pp_upload.classList.remove("select-card-btn")
                    pp_upload.classList.add("select-card-btn-pp")
                    pp_upload.style.backgroundImage = "url('" + this.result + "')";
                    upload_icon.style.display = "none";
                    viewChange(index)
                    nextSlide4.style.display = 'block';
                    console.log(file)
                });
                reader.readAsDataURL(file);
                $(".pp-upload-span").html("Replace");
            }
        }
    }


    else if (index == 3.5) {
        viewChange(3)
    }


    else if (index == 4) {
        // if country selected
        console.log(selectCountryInput.value)
        fetchCountry(value)
        viewChange(index)
    }


    else if (index == 5) {
        // if genre selected
        var checkboxgroup = document.querySelectorAll(
            'input[name="user-selected-genre"]'
        )
        var chkChecked = document.querySelectorAll(
            'input[name="user-selected-genre"]:checked'
        )
        var chkCheckedNot = document.querySelectorAll(
            'input[name="user-selected-genre"]:not(:checked)'
        )

        genresSelected = [];
        var total = 0;
        var limit = 3;

        for (var i = 0; i < checkboxgroup.length; i++) {
            if (checkboxgroup[i].checked == true) {
                total = total + 1
            }
            if (total > limit) {
                checkboxgroup[i].checked = false;
                return false
            } else if (total == limit) {
                chkCheckedNot.forEach(e => {
                    e.disabled = true
                    return false
                })
                for (let i = 0; i < chkChecked.length; i++) {
                    genresSelected.push(chkChecked[i].value)
                }
                console.log(genresSelected);
                statusMessage('-40px', '')
                viewChange(index)
                return false;
            } else if (total < limit) {
                chkCheckedNot.forEach(e => {
                    e.disabled = false;
                    statusMessage('0px', 'Select ' + limit + ' genres please')
                    return false;
                })
            }
        }
    }


    else if (index == 6) {
        // tags selection
        var checkboxgroup
        var chkChecked
        var chkCheckedNot

        tagsSelected = []
        selectedTagIndex = []

        var total = 0;
        var limit = 5;

        checkboxgroup = document.querySelectorAll(
            'input[name="user-selected-tag"]'
        )
        chkChecked = document.querySelectorAll(
            'input[name="user-selected-tag"]:checked'
        )
        chkCheckedNot = document.querySelectorAll(
            'input[name="user-selected-tag"]:not(:checked)'
        )
        label = document.querySelectorAll('.chk-pill')

        // append the selected tags
        var list = document.querySelector('.tags-list')
        var selectedTagsList = document.querySelector('.selected-tags-list')
        var fragment = document.createDocumentFragment()

        for (var i = 0; i < checkboxgroup.length; i++) {
            if (checkboxgroup[i].checked == true) {
                total = total + 1
                selectedTagIndex.push(checkboxgroup[i].value)
                fragment.appendChild(checkboxgroup[i].parentElement)
                selectedTagsList.append(checkboxgroup[i].parentElement)
            }
            else {
                fragment.appendChild(checkboxgroup[i].parentElement)
                list.append(checkboxgroup[i].parentElement)
            }

            if (total > limit) {
                checkboxgroup[i].checked = false
                return false
            }
            else if (total == limit) {
                chkCheckedNot.forEach(e => {
                    e.disabled = true;
                    return false;
                })

                for (let i = 0; i < chkChecked.length; i++) {
                    tagsSelected.push(chkChecked[i].value)
                }
                console.log(tagsSelected)
                searchEngine.disabled = true;
                statusMessage('-40px', '')
                viewChange(index)
                return false
            }
            else if (total < limit) {
                chkCheckedNot.forEach(e => {
                    e.disabled = false;
                    return false;
                })
                searchEngine.disabled = false;
                statusMessage('0px', 'Select ' + limit + ' tags please')
            }
        }
    }


    else if (index == 7) {
        // BPM selection
        console.log(bpmRangeInput.value)
    }


    else if (index == 7.5) {
        viewChange(7)
    }


    else if (index == 8) {
        // instruments selection
        var checkboxgroup = document.querySelectorAll(
            'input[name="user-selected-instrument"]'
        )
        var chkChecked = document.querySelectorAll(
            'input[name="user-selected-instrument"]:checked'
        )
        var chkCheckedNot = document.querySelectorAll(
            'input[name="user-selected-instrument"]:not(:checked)'
        )

        instrumentsSelected = []

        var total = 0;
        var limit = 3;

        for (var i = 0; i < checkboxgroup.length; i++) {

            if (checkboxgroup[i].checked == true) {
                total = total + 1
            }
            if (total < limit) {
                nextSlide6.style.display = 'none';
                statusMessage('0px', 'Select at least ' + limit)
            }
            else if (total == limit) {
                nextSlide6.style.display = 'block';

                for (let i = 0; i < chkChecked.length; i++) {
                    instrumentsSelected.push(chkChecked[i].value)
                }
                console.log(instrumentsSelected)
                statusMessage('-40px', '')
                return false
            }
        }
    }


    else if (index == 8.5) {
        viewChange(8)
        if (accType == 'Artist') {
            displayReviewData()
        }
    }


    else if (index == 9) {
        // daws selection
        var checkboxgroup
        var chkChecked
        var chkCheckedNot

        dawsSelected = []

        var total = 0;
        var limit = 1;

        checkboxgroup = document.querySelectorAll(
            'input[name="user-selected-daw"]'
        )
        chkChecked = document.querySelectorAll(
            'input[name="user-selected-daw"]:checked'
        )
        chkCheckedNot = document.querySelectorAll(
            'input[name="user-selected-daw"]:not(:checked)'
        )

        for (var i = 0; i < checkboxgroup.length; i++) {

            if (checkboxgroup[i].checked == true) {
                total = total + 1
            }
            if (total < limit) {
                nextSlide7.style.display = 'none';
                statusMessage('0px', 'Select at least ' + limit + ' daw please')
            }
            else if (total == limit) {
                nextSlide7.style.display = 'block';
                for (let i = 0; i < chkChecked.length; i++) {
                    dawsSelected.push(chkChecked[i].value)
                }
                console.log(dawsSelected)
                statusMessage('-40px', '')
                return false
            }
        }
    }


    else if (index == 9.5) {
        viewChange(9)
        displayReviewData()
    }


    else if (index == 10) {
        $.ajax({
            url: 'db/upload/signup-complete.php',
            method: 'POST',
            data: {
                accType: accType,
                gender: selectGenderInput.value,
                country: selectCountryInput.value,
                genres: genresSelected,
                tags: tagsSelected,
                bpm: bpmRangeInput.value,
                instruments: instrumentsSelected,
                daws: dawsSelected
            },
            dataType: 'json',
            beforeSend: function () {
                lbMain.style.display = 'flex';
            },
            success: function (response) {
                if (response.status == 1) {
                    $.ajax({
                        url: 'db/upload/profile-pic-upload.php',
                        method: 'POST',
                        data: new FormData(document.querySelector(".user-profile-pic-form")),
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        beforeSend: function () {
                        },
                        success: function (response) {
                            if (response.status == 1) {
                                setTimeout(() => {
                                    window.location.href = "/";
                                }, 2000);
                            } else {
                                alert(response.message)
                            }
                            console.log(response);
                        }
                    });
                    console.log(response);
                } else {
                    alert(response.message)
                }
            }
        });
    }


    function displayReviewData() {
        var table = document.querySelector("#review-table");

        table.innerHTML = ``;
        completeArr = []

        var countryName;
        var genresConv = [];
        var instrumentsConv = [];
        var dawsConv = [];

        var qArr = [
            'Role',
            'Gender',
            'Country',
            'Genres selected',
            'Tags selected',
            'Preferred BPM range',
            'Instruments selected'
        ];

        var aArr = [];

        if (accType == 'Producer') {
            qArr[7] = 'DAWS selected';
        }

        aArr[0] = selectAccTypeInput.value
        aArr[1] = selectGenderInput.value
        aArr[4] = tagsSelected.join("<br>")
        aArr[5] = bpmRangeInput.value

        fetch('js/json/countries.json')
            .then(response => response.json())
            .then(data => {
                // country code to country name
                for (let i = 0; i < data.length; i++) {
                    if (data[i].code == selectCountryInput.value) {
                        countryName = data[i].name;
                    }
                    aArr[2] = countryName;
                }
            })

        $.ajax({
            url: 'db/download/get-main-data.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {

                console.log(data)

                // genre id to genre name
                for (let i = 0; i < genresSelected.length; i++) {
                    for (let j = 0; j < data[0].length; j++) {
                        if (data[0][j].id == genresSelected[i]) {
                            genresConv.push(data[0][j].genre)
                        }
                        aArr[3] = genresConv.join("<br>");
                    }
                }

                // instrument id to instrument name
                for (let i = 0; i < instrumentsSelected.length; i++) {
                    for (let j = 0; j < data[2].length; j++) {
                        if (data[2][j].id == instrumentsSelected[i]) {
                            instrumentsConv.push(data[2][j].instrument)
                        }
                        aArr[6] = instrumentsConv.join("<br>");
                    }
                }

                if (accType == 'Producer') {
                    // daw id to daw name
                    for (let i = 0; i < dawsSelected.length; i++) {
                        for (let j = 0; j < data[1].length; j++) {
                            if (data[1][j].id == dawsSelected[i]) {
                                dawsConv.push(data[1][j].daw_name)
                            }
                            aArr[7] = dawsConv.join("<br>");
                        }
                    }
                }

                // array generated with all user details for final review
                for (let i = 0; i < qArr.length; i++) {
                    completeArr.push(
                        {
                            a: aArr[i], q: qArr[i]
                        }
                    )
                }

                // array printed to review table in HTML
                for (let i = 0; i < completeArr.length; i++) {
                    var table = document.querySelector("#review-table");
                    table.innerHTML +=
                        `
                <tr>
                  <th class="th-q">`+ completeArr[i].q + `</th>
                  <th>`+ completeArr[i].a + `</th>
                </tr>
              `;
                }
            }
        })
    }
}

function previousSlide(index) {
    if (index == -9 && accType == 'Artist') {
        index = -8;
    }
    slider.style.marginLeft = (((index) * 100) + 100) + "vw"
    globalProgressBar.style.width = Math.abs((index / slideWrapper.length * 100) + (1 / slideWrapper.length * 100)) + "%"
}

bpmRangeInput.addEventListener('input', () => {
    value = bpmRangeInput.value;
    var percentage = (value / bpmRangeInput.max) * 100;
    globalRangeTooltip.innerHTML = value;
    globalRangeTooltip.style.opacity = '1';
    globalRangeTooltip.style.marginLeft = 'calc(' + percentage + '% - 24px)';
    if (bpmRangeInput.value < 40) {
        nextSlide5.style.display = 'none';
        statusMessage('0px', 'BPM should range between 40 to 200')
    } else {
        nextSlide5.style.display = 'block';
        statusMessage('-40px', '')
    }
})

function viewChange(index) {
    slider.style.marginLeft = (-1 * (index) * 100) + "vw"
    globalProgressBar.style.width = Math.abs(index / slideWrapper.length * 100) + "%"
}

function generateCountryList() {

    fetch('js/json/countries.json')
        .then(response => response.json())
        .then(json => {
            var list = document.querySelector('.all-countries-list')
            for (let i = 1; i < json.length; i++) {
                list.innerHTML +=
                    `
                    <div class="flag-row row-` +
                    json[i].code +
                    `"><input class="radio-input radio-` +
                    json[i].code +
                    `" class="select-country-input" onclick="continueSignup(4, '` +
                    json[i].code +
                    `')" name="user-selected-country" value="` +
                    json[i].code +
                    `" type="radio"><div class="flag-icon flag-icon-` +
                    json[i].code +
                    `"></div>` +
                    json[i].name +
                    `</div>
              `
            }
        })
}

function fetchCountry(code) {
    var previousFlagRow = document.querySelector('.row-' + countryIndex)
    var flagRow = document.querySelector('.row-' + code)

    countryCode = code
    if (countryIndex == null) {
        flagRow.style.backgroundColor = 'var(--blue)'
        countryIndex = code
    } else if (countryIndex != code) {
        flagRow.style.backgroundColor = 'var(--blue)'
        previousFlagRow.style.backgroundColor = 'black'
        countryIndex = code
    }
}

function changeCheckedState(index, value) {
    if (index == 1) {
        var checkedCard = document.querySelector('#checked-' + value)
        var previousCheckedCard = document.querySelector('#checked-' + accTypeIndex)
        if (accTypeIndex == null) {
            checkedCard.style.backgroundColor = 'rgba(69, 111, 167, 0.2)'
            accTypeIndex = value
        }
        else if (accTypeIndex == value) {
            return
        }
        else {
            checkedCard.style.backgroundColor = 'rgba(69, 111, 167, 0.2)'
            previousCheckedCard.style.backgroundColor = 'transparent'
            accTypeIndex = value
        }
    } else if (index == 2) {
        var checkedGenderCard = document.querySelector('#checked-' + value)
        var previousCheckedGenderCard = document.querySelector(
            '#checked-' + genderIndex
        )
        if (genderIndex == null) {
            checkedGenderCard.style.border = 'solid var(--blue) 1px'
            genderIndex = value
        }
        else if (genderIndex == value) {
            return
        }
        else {
            checkedGenderCard.style.border = 'solid var(--blue) 1px'
            previousCheckedGenderCard.style.border = 'none'
            genderIndex = value
        }
    }
}

function setPreferencesForm(open, close) {
    open.forEach(e => {
        e.classList.add('slide-wrapper')
        e.style.display = 'flex';
    })
    close.forEach(e => {
        e.classList.remove('slide-wrapper')
        e.style.display = 'none';
    })
}

function generateGenreList() {

    $.ajax({
        url: 'db/download/get-main-data.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {

            console.log(data)
            var list = document.querySelector('.genres-list')

            for (let i = 0; i < data[0].length; i++) {
                var genreNoSpaces = data[0][i].genre.replace(/ /g, '_')
                list.innerHTML +=
                    `
            <input id="genre-chk-` +
                    i +
                    `" type="checkbox"
            name="user-selected-genre" onclick="continueSignup(5)"
            value="` +
                    data[0][i].id +
                    `">
            <label class="chk-pill genre-pill-` +
                    i +
                    `" for="genre-chk-` +
                    i +
                    `">` +
                    data[0][i].genre +
                    `</label>
            `
            }
        },
        error: function (error) {
            console.log(error);
            lbMain.style.display = 'flex';
        }
    })
}

function generateDawList() {

    $.ajax({
        url: 'db/download/get-main-data.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {

            var list = document.querySelector('.daws-list')

            for (let i = 0; i < data[1].length; i++) {
                list.innerHTML +=
                    `
          <input id="daw-radio-` +
                    i +
                    `" class="" type="checkbox"
          name="user-selected-daw" onclick="continueSignup(9, ` +
                    i +
                    `)"
          value="` +
                    data[1][i].id +
                    `">
          <label class="logo-frame" for="daw-radio-` +
                    i +
                    `">
          <img class="daw-logo" src="` +
                    data[1][i].logo_url.substring(1) +
                    `" draggable=false>
          <span class="element-label">` +
                    data[1][i].daw_name +
                    `</span>
          </label>
      `
            }
        },
        error: function (error) {
            console.log(error);
            lbMain.style.display = 'flex';
        }
    })
}

function generateInstrumentsList() {

    $.ajax({
        url: 'db/download/get-main-data.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {

            var list = document.querySelector('.instruments-list')

            for (let i = 0; i < data[2].length; i++) {
                list.innerHTML += `
        <div class="user-instrument-btn ">
        <div class="user-instrument-bg instrument-bg" style="background-image: url('`+ data[2][i].logo_url.substring(1) + `')">
        <input id="instrument-chk-`+ i + `" onclick="continueSignup(8, ` + i + `)" type="checkbox"
        name="user-selected-instrument" value="`+ data[2][i].id + `">
        <label for="instrument-chk-`+ i + `" class="global-popup-session">` + data[2][i].instrument + `</label>
        </div>
        </div>
        `
            }
            lbMain.style.display = 'none';
        },
        error: function (error) {
            console.log(error);
            lbMain.style.display = 'flex';
        }
    })
}

var page = 1;
var method;
var url = "https://ws.audioscrobbler.com/2.0/?";
var apiKey = "1c1af99fd7a8453c6385b6150e8dfc15";

var timers = [];

function stopTimer() {
    timers.forEach(function (timer) {
        clearTimeout(timer);
    });
}

$(document).ready(getData(1));

function getData(query) {
    var list = document.querySelector(".tags-list");
    var tagsCount = document.querySelector("#tags-count");
    var message = ''

    stopTimer();
    list.innerHTML = '';
    tagsCount.style.display = 'none'
    lbTags.style.display = 'flex'


    if (query == 1) {
        method = "artist.search&artist=type beat";
    } else if (query == 2) {
        method = "artist.search&artist=" + searchEngine.value.trim() + " type beat";
    }

    var exclude = [
        "type", "beat", "Beats", "FREE", "Free", "free",
        "*", "SOLD", "sold", "Sold",
        "Instrumental", "/", "'", "|", "x",
        "X", "2", "(", ")", "+", ",", "\"", "&", "CHORUS",
        "HOOK", "chorus", "hook", "Chorus", "Hook", "ft",
        "Ft", "FT", "how", "How", "HOW", "–", "[", "]",
        "{", "}", "compilation", "COMPILATION", "`", "~",
        "¦"
    ];

    var filtered = [];
    var dump = [];

    var excludeFromViewList = dump.concat(selectedTagIndex)

    timers.push(
        setTimeout(() => {

            $.getJSON(url +
                "method=" + method +
                "&api_key=" + apiKey +
                "&format=json&page=" + page,

                function (json) {
                    var jsonDir = '';
                    if (query == 1) {
                        jsonDir = json.results.artistmatches;
                    } else if (query == 2) {
                        jsonDir = json.results.artistmatches;
                    }

                    for (let i = 0; i < jsonDir.artist.length; i++) { // loop through all results
                        for (let j = 0; j < exclude.length; j++) { // check if the results contain excluded items
                            if (jsonDir.artist[i].name.includes(exclude[j])) {
                                excludeFromViewList.push(jsonDir.artist[i].name)
                            }
                        }
                        filtered.push(jsonDir.artist[i].name);
                    }

                    filtered = filtered.filter(function (item) {
                        return excludeFromViewList.indexOf(item) < 0;
                    });

                    for (let i = 0; i < filtered.length; i++) {
                        timers.push(
                            setTimeout(() => {
                                list.innerHTML +=
                                    `
              <div class="tag-row">
              <input id="tag-chk-` +
                                    i + inc +
                                    `" class="tag-chk" type="checkbox"
              name="user-selected-tag" onclick="continueSignup(6)"
              value="` +
                                    filtered[i] +
                                    `">
              <label class="chk-pill tag-pill-` +
                                    i + inc +
                                    `" for="tag-chk-` +
                                    i + inc +
                                    `">` +
                                    filtered[i] +
                                    `</label>
              </div>
              `
                                if (filtered.length == 1) {
                                    message = "{ 1 tag found }"
                                } else {
                                    message = "{ " + (i + 1) + " tags found }"
                                }
                                tagsCount.innerHTML = message
                            }, i * 150)
                        )
                    }

                    if (filtered.length == 0) {
                        message = "{ Sorry no tags found }";
                    }
                    tagsCount.innerHTML = message
                }
            )
                .done(function () {
                    tagsCount.style.display = 'block'
                    lbTags.style.display = 'none'
                })

        }, 1000)
    )
}

searchEngine.addEventListener('input', () => {
    inc++
    if (searchEngine.value == '') {
        getData(1);
    } else {
        getData(2);
    }
})

function statusMessage(marginBottom, message) {
    var statusBar = document.querySelector('#status-bar')
    var formResponse = document.querySelector('#form-response')
    statusBar.style.marginBottom = marginBottom;
    formResponse.innerHTML = message;
}

function loadFormData() {

    var functions = new Array();
    // Adding the functions in the order
    // in queue(array)
    functions[0] = generateCountryList;
    functions[1] = generateGenreList;
    functions[2] = generateDawList;
    functions[3] = generateInstrumentsList;
    for (var i = 0; i < functions.length; i++) {
        // Executing them in order.
        functions[i].call();
    }

}

loadFormData()