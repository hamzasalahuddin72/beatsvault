var dirClose = false;

export function toggleDirectory() {
    var vaultDir = document.querySelector(".vault-directory");
    var mainView = document.querySelector("#main-view");
    var dirCloseBtn = document.querySelector("#dir-toggle-close");
    var dirOpenBtn = document.querySelector("#dir-toggle-open");
    var directories = document.querySelectorAll(".directory");
    var labels = document.querySelectorAll("label");
    const btns = document.querySelectorAll(".dir-toggle-btns");

    btns.forEach((e) => {
        e.addEventListener('click', function () {
            if (dirClose) {
                openVaultDir();
            } else {
                closeVaultDir();
            }
        })
    })


    function closeVaultDir() {

        new ResizeObserver(function (e) {
            if (e[0].contentBoxSize[0].inlineSize < 575.2) {
                vaultDir.style.width = '30px';
                mainView.style.marginLeft = '30px';
                dirCloseBtn.style.display = 'none';
                dirOpenBtn.style.display = 'block';
                directories.forEach(function (e) {
                    e.style.display = 'none';
                })
                labels.forEach(function (e) {
                    e.style.display = 'none';
                })
            } else {
                vaultDir.style.width = '40px';
                mainView.style.marginLeft = '40px';
                dirCloseBtn.style.display = 'none';
                dirOpenBtn.style.display = 'block';
                directories.forEach(function (e) {
                    e.style.display = 'none';
                })
                labels.forEach(function (e) {
                    e.style.display = 'none';
                })
            }
        }).observe(document.body)
        dirClose = true;
    }

    function openVaultDir() {
        dirCloseBtn.style.display = 'block';
        dirOpenBtn.style.display = 'none';
        vaultDir.style.width = '150px';
        mainView.style.marginLeft = '150px';
        directories.forEach(function (e) {
            e.style.display = 'block';
        })
        labels.forEach(function (e) {
            e.style.display = 'block';
        })

        new ResizeObserver(function (e) {
            if (e[0].contentBoxSize[0].inlineSize < 576) {
                vaultDir.style.width = '120px';
                mainView.style.marginLeft = '120px';
            }
        }).observe(document.body)
        dirClose = false;
    }
}