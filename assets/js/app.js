/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// jQuery
const $ = require('jquery');


$(document).ready(function() {

    // Check for click events on the navbar burger icon
    $(".navbar-burger").click(function() {

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");

    });
});

function CookiePopup(hideOrshow) {
    if (hideOrshow === 'hide') {
        document.getElementById('js-cookie-popup').style.display = "none";
    }
    else if (localStorage.getItem("popupWasShown") == null) {
        localStorage.setItem("popupWasShown", 1);
        document.getElementById('js-cookie-popup').removeAttribute('style');
    }
}
window.onload = function () {
    setTimeout(function () {
        CookiePopup('show');
    }, 0);
}


function hideNow(e) {
    if (e.target.id === 'js-cookie-popup') {
        document.getElementById('js-cookie-popup').style.display = 'none';
    }
}