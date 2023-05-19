var metaTray = false;

window.onbeforeunload = function () {
    window.scrollTo(0, 0);
}

function showMetadata() {
    var metadataTray = document.querySelector(".metadata-tray");
    if (metaTray == false) {
        metaTray = true;
        metadataTray.style.display = 'flex';
        metadataTray.style.width = '900px';
        metadataTray.style.height = '700px';
        metadataTray.style.padding = '10px';
        metadataTray.style.border = 'solid var(--blue) 2px';
    } else {
        metaTray = false;
        metadataTray.style.display = 'flex';
        metadataTray.style.width = '0px';
        metadataTray.style.height = '0px';
        metadataTray.style.padding = '0px';
        metadataTray.style.margin = '0px';
        metadataTray.style.border = 'none';
    }
}

function mainBackground() {
    setInterval(() => {
        var randomnumber = Math.floor(Math.random() * (99 - 1)) + 0;
        var itemB = document.querySelector(".main-bg-item-" + randomnumber)
        itemB.style.color = 'var(--blue)';
        itemB.style.opacity = '0.3';
        setTimeout(() => {
            itemB.style.color = 'darkgrey';
            itemB.style.opacity = '0.1';
        }, 2000);
    }, 100);
}

setTimeout(() => { mainBackground(); }, 1000);