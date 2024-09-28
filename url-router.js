import { toggleDirectory } from "./js/beats.js";
import { homeFeedUpdate, downloadUserTracks, updateUserList } from "./js/exports.js";
import { allMsgs, updateAllMsgs } from "./js/messenger.js";
import { toggleSettings } from "./js/settings.js";
import { userProfile } from "./js/user-page.js";
import { switchLayout } from "./js/visual-functions.js";

window.onload = function () { setTimeout(function () { window.scrollTo(0, 1); }, 0); }

var topWrapper = document.querySelector("#top-wrapper")
var wrapper = document.querySelector("#wrapper")
var aa_wrapper = document.querySelector("#aa-wrapper")

var url;
var updateMsgs;

document.addEventListener("click", (e) => {
    const { target } = e;
    if (e.target.className != ("nav-link")
        && e.target.className != ("profile-href")) {
        return
    }
    url = e.target.href;
    e.preventDefault();
    urlRoute();
});

const urlPageTitle = "BEATSVAULT";

const urlRoutes = {
    404: {
        name: 404,
        template: "home.php",
        title: "404 | " + urlPageTitle,
        description: "Page not found",
    },
    "/": {
        name: "home",
        template: "home.php",
        title: "Welcome to " + urlPageTitle,
        description: "This is the home page",
    },
    "/beats": {
        name: "beats",
        template: "beats.php",
        title: "Beats - " + urlPageTitle,
        description: "This is the beats page",
    },
    "/user": {
        name: "user",
        template: "user.php",
        title: "User - " + urlPageTitle,
        description: "This is the user page",
    },
    "/settings": {
        name: "settings",
        template: "settings.php",
        title: "Account settings - " + urlPageTitle,
        description: "This is the settings page",
    },
    "/logout": {
        name: "logout",
        template: "logout.php",
        title: "Logging out - " + urlPageTitle,
        description: "This is the complete join page",
    },
};

// create a function that watches the url and calls the urlLocationHandler
export const urlRoute = (event) => {
    event = event || window.event; // get window.event if event argument not provided
    event.preventDefault();
    // window.history.pushState(state, unused, target link);
    window.history.pushState({}, "", event.target.href);
    urlLocationHandler();
};

// create a function that handles the url location
const urlLocationHandler = async () => {
    const location = window.location.pathname; // get the url path
    // if the path length is 0, set it to primary page route
    if (location.length == 0) {
        location = "/";
    }

    // get the route object from the urlRoutes object
    const route = urlRoutes[location] || urlRoutes["404"];
    // get the html from the template
    const html = await fetch(route.template).then((response) => response.text());
    // set the content of the content div to the html

    // set the title of the document to the title of the route
    document.title = route.title;
    // set the description of the document to the description of the route
    // document
    // 	.querySelector('meta[name="description"]')
    // 	.setAttribute("content", route.description);
    topWrapper.style.display = 'none'

    clearInterval(updateMsgs)

    if (route.name == 'home') {
        topWrapper.style.display = 'flex'
        aa_wrapper.innerHTML = ""
        wrapper.style.display = 'flex'
        // sub_wrapper.style.display = 'flex'
        wrapper.innerHTML = html
        downloadUserTracks()
        switchLayout()
        homeFeedUpdate()
        updateUserList()
        allMsgs()
        updateMsgs = setInterval(() => {
            updateAllMsgs()
        }, 3000);
    }
    else if (route.name == 'beats') {
        wrapper.style.display = 'none'
        aa_wrapper.innerHTML = html
        toggleDirectory()
    }
    else if (route.name == 'user') {
        wrapper.style.display = 'none'
        aa_wrapper.innerHTML = html
        userProfile(url.split('?')[1])
        switchLayout()
    }
    else if (route.name == 'settings') {
        wrapper.style.display = 'none'
        aa_wrapper.innerHTML = html
        toggleSettings();
    }
    else if (route.name == 'logout') {
        window.location.reload();
    }
};

// add an event listener to the window that watches for url changes
window.onpopstate = urlLocationHandler;
// call the urlLocationHandler function to handle the initial url
window.route = urlRoute;
// call the urlLocationHandler function to handle the initial url
urlLocationHandler();

window.onbeforeunload = function () {
    window.setTimeout(function () {
        window.location = '/';
    }, 0);
    window.onbeforeunload = null; // necessary to prevent infinite loop, that kills your browser 
}