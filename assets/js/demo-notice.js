// demo-notice.js - "this is a demo project, don't pay" popup shown when the website is opened.
// Shown once per browser session (a new tab/window visit shows it again).
(function () {
    var KEY = "petshop-demo-notice-seen";
    try {
        if (sessionStorage.getItem(KEY)) return;
    } catch (e) { /* storage blocked: just show it */ }

    function show() {
        var overlay = document.createElement("div");
        overlay.className = "demo-notice-overlay";
        overlay.innerHTML =
            '<div class="demo-notice" role="dialog" aria-modal="true" aria-labelledby="demoNoticeTitle" aria-describedby="demoNoticeText">' +
                '<div class="demo-notice-icon" aria-hidden="true">!</div>' +
                '<h2 id="demoNoticeTitle">This is a demo project</h2>' +
                '<div id="demoNoticeText">' +
                    '<p>This Pet Shop website was made only as a <b>student project</b>. It is not a real shop.</p>' +
                    '<p class="demo-notice-warn"><b>Please do not make any payment.</b> No products will be delivered, and no money should be paid here.</p>' +
                    '<p class="demo-notice-gu" lang="gu">આ વેબસાઇટ ફક્ત પ્રોજેક્ટ માટે છે. અહીં કોઈ પેમેન્ટ કરવું નહીં.</p>' +
                '</div>' +
                '<button type="button" class="demo-notice-btn">I understand</button>' +
            '</div>';
        document.body.appendChild(overlay);

        var button = overlay.querySelector(".demo-notice-btn");
        var lastFocus = document.activeElement;
        function close() {
            try { sessionStorage.setItem(KEY, "1"); } catch (e) {}
            overlay.classList.add("closing");
            document.removeEventListener("keydown", onKey);
            setTimeout(function () { overlay.remove(); }, 200);
            if (lastFocus && lastFocus.focus) lastFocus.focus();
        }
        function onKey(e) {
            if (e.key === "Escape") close();
            if (e.key === "Tab") { e.preventDefault(); button.focus(); }   // keep focus inside the popup
        }
        button.addEventListener("click", close);
        document.addEventListener("keydown", onKey);
        requestAnimationFrame(function () { overlay.classList.add("open"); button.focus(); });
    }

    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", show);
    else show();
})();
