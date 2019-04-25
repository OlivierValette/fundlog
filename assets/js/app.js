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

    // Cookie consent (stored on localStorage)
    setTimeout(function () {
        if (localStorage.getItem("cookieConsent") !== "OK") $("#cookieConsent").fadeIn(500);
    }, 5000);

    $("#cookieConsentOK").click(function() {
        localStorage.setItem("cookieConsent", "OK");
        $("#cookieConsent").fadeOut(500);
    });

    // Check for click events on the navbar burger icon
    $(".navbar-burger").click(function() {

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");

    });

    // Search in table
    $("#fundBaseSearch").keyup(function() {
        var value = this.value.toLowerCase().trim();

        $("table tr").each(function (index) {
            if (!index) return;
            $(this).find("td").each(function () {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                return not_found;
            });
        });
    });

    // // Add line in table
    // $("#addLineButton").click(function() {
    //     $("#addLineContent").removeClass("is-hidden");
    // });

});
