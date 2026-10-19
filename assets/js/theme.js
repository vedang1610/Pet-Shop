// theme.js - site-wide light/dark mode (shop, login pages and admin panel).
// Load it as a normal (blocking) script near the top of the page so the theme is set before anything paints.
// Any element with [data-theme-toggle] switches the theme; the choice is saved in localStorage.
(function () {
    if (window.__petshopTheme) return;   // loaded twice on a page: keep one set of listeners
    window.__petshopTheme = true;
    var KEY = "petshop-theme";
    var root = document.documentElement;

    function saved() {
        try { return localStorage.getItem(KEY); } catch (e) { return null; }
    }
    function systemTheme() {
        return window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }

    function apply(theme) {
        var dark = theme === "dark";
        root.setAttribute("data-theme", theme);
        // AdminLTE's built-in dark styles hang off a .dark-mode ancestor
        root.classList.toggle("dark-mode", dark);

        // AdminLTE top bar: swap its light/dark navbar classes
        var navbars = document.querySelectorAll(".main-header.navbar");
        for (var i = 0; i < navbars.length; i++) {
            navbars[i].classList.toggle("navbar-white", !dark);
            navbars[i].classList.toggle("navbar-light", !dark);
            navbars[i].classList.toggle("navbar-dark", dark);
        }

        var toggles = document.querySelectorAll("[data-theme-toggle]");
        for (var j = 0; j < toggles.length; j++) {
            toggles[j].setAttribute("aria-label", dark ? "Switch to light mode" : "Switch to dark mode");
            toggles[j].setAttribute("title", dark ? "Light mode" : "Dark mode");
            toggles[j].setAttribute("aria-pressed", dark ? "true" : "false");
        }
    }

    // 1. Right away: set the theme on <html>
    apply(saved() || systemTheme());

    // 2. Once the page is parsed: update the navbar/toggles and wire up the buttons
    function ready() {
        apply(root.getAttribute("data-theme"));
        document.addEventListener("click", function (e) {
            var button = e.target.closest ? e.target.closest("[data-theme-toggle]") : null;
            if (!button) return;
            e.preventDefault();
            var next = root.getAttribute("data-theme") === "dark" ? "light" : "dark";
            apply(next);
            try { localStorage.setItem(KEY, next); } catch (err) {}
        });
    }
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", ready);
    } else {
        ready();
    }

    // Follow the device setting while the visitor hasn't picked a theme
    if (window.matchMedia) {
        var query = window.matchMedia("(prefers-color-scheme: dark)");
        var onChange = function (e) { if (!saved()) apply(e.matches ? "dark" : "light"); };
        if (query.addEventListener) query.addEventListener("change", onChange);
        else if (query.addListener) query.addListener(onChange);
    }
})();
