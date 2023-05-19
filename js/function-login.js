var loginForm = document.querySelector(".login-form");
var signupForm = document.querySelector(".signup-form");
var loginContainer = document.querySelector("#login-container");
var loginBtn = document.querySelector("#login-btn");
var joinBtn = document.querySelector("#join-btn");
var loginStatus = document.querySelector(".login-status");

joinBtn.addEventListener("click", ()=>{
    loginForm.setAttribute("style", "display:none;");
    signupForm.setAttribute("style", "display:flex;");
});

loginBtn.addEventListener("click", ()=>{
    signupForm.setAttribute("style", "display:none;");
    loginForm.setAttribute("style", "display:flex;");
})