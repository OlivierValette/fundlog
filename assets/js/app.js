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


    // Modal for editing portfolio lines

    // Listening button with "modal-trigger" class
    $('.modal-trigger').click( (e) => {
        // retrieve ajax target url in "data-target" property
        const url = e.currentTarget.dataset.target;
        // retrieve transaction line
        const line = e.currentTarget.dataset.line;

        const modale = document.querySelector('.modal');

        // setting modal title
        $('.modal-card-title').text('Arbitrer la ligne #' + line);

        // ajax call of symfony controller retrieving template
        // TODO: separate actions
        $.get(url, (data) => {
            // html injection in modal followed by modal activation
            $.when( $('.modal-card-body').html(data) ).done( modale.classList.add('is-active'));
            // modal controls
            $('.delete').click( () => modale.classList.remove('is-active'));
            $('.button').click( () => modale.classList.remove('is-active'));
        });
    });


});
