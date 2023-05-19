var layouts = ["linear", "grid", "polaroid"]
var layoutActive = 0;
var ready = false;

export function switchLayout() {
    $(".layout-style").click(function () {
        if (layoutActive < layouts.length - 1) {
            setLayout(layoutActive + 1)
        } else {
            setLayout(0)
        }
    })
}

export function setLayout(layout) {
    setTimeout(() => {
        layoutActive = layout
    }, 100);
    $(".layout-style").attr("layout", layouts[layout])
    $(".layout-style").removeClass(layouts[layoutActive] + "-layout");
    $(".layout-style").addClass(layouts[layout] + "-layout");
    if (ready == true) {
        removeLayout(layouts[layoutActive])
    }
    addLayout(layouts[layout])
    ready = true
}

export function addLayout(x, y) {
    $(".switch-layout-tooltip").html(x.charAt(0).toUpperCase() + x.slice(1))
    $(".home-feed-content").addClass(x + "-home-feed-content");
    $(".feed-content-card").addClass(x + "-feed-content-card");
    $(".feed-album-cover").addClass(x + "-feed-album-cover");
    $(".feed-meta-card").addClass(x + "-feed-meta-card");
    $(".feed-options").addClass(x + "-feed-options");
    $(".sale-options").addClass(x + "-sale-options");
}

export function removeLayout(y) {
    $(".home-feed-content").removeClass(y + "-home-feed-content");
    $(".feed-content-card").removeClass(y + "-feed-content-card");
    $(".feed-album-cover").removeClass(y + "-feed-album-cover");
    $(".feed-meta-card").removeClass(y + "-feed-meta-card");
    $(".feed-options").removeClass(y + "-feed-options");
    $(".sale-options").removeClass(y + "-sale-options");
}