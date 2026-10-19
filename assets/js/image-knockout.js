// image-knockout.js - removes the plain white background around product photos so they sit on the
// page colour in both light and dark mode. Only white that touches the photo's edges is removed
// (flood fill from the border), so white parts of the product itself - labels, lids - stay white.
(function () {
    var SELECTOR = [
        ".img-block img",                 // product cards (home, shop)
        ".zoompro",                       // product page main photo
        ".product-dec-slider-2 img",      // product page thumbnails
        ".product-thumbnail img",         // cart
        ".img-thumb img",                 // category circles
        ".pd-main img",                   // product page main photo
        ".pd-thumb img",                  // product page thumbnails
        ".pd-card-img img",               // product page related products
        ".ad-pick-img img"                // customer dashboard "Picked for your pet"
    ].join(",");
    var HARD = 238;   // every channel at least this bright: background
    var SOFT = 215;   // between SOFT and HARD next to the background: partly transparent edge
    var cache = {};   // original src -> processed data URL (or false when it can't be processed)

    function knockout(img) {
        var w = img.naturalWidth, h = img.naturalHeight;
        if (!w || !h || w * h > 4000000) return null;
        var canvas = document.createElement("canvas");
        canvas.width = w; canvas.height = h;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);
        var data;
        try { data = ctx.getImageData(0, 0, w, h); } catch (e) { return null; }
        var px = data.data;

        function minChannel(i) { return Math.min(px[i], px[i + 1], px[i + 2]); }

        // Skip photos that don't have a white frame (e.g. full-bleed pictures)
        var whiteEdge = 0, edgeCount = 0;
        for (var x = 0; x < w; x += 4) {
            edgeCount += 2;
            if (minChannel(x * 4) >= HARD) whiteEdge++;
            if (minChannel(((h - 1) * w + x) * 4) >= HARD) whiteEdge++;
        }
        if (whiteEdge / edgeCount < 0.6) return null;

        // Flood fill from every border pixel through near-white pixels
        var seen = new Uint8Array(w * h);
        var stack = [];
        function push(p) {
            if (!seen[p] && minChannel(p * 4) >= HARD) { seen[p] = 1; stack.push(p); }
        }
        for (var bx = 0; bx < w; bx++) { push(bx); push((h - 1) * w + bx); }
        for (var by = 0; by < h; by++) { push(by * w); push(by * w + w - 1); }
        while (stack.length) {
            var p = stack.pop(), px0 = p % w;
            px[p * 4 + 3] = 0;
            if (px0 > 0) push(p - 1);
            if (px0 < w - 1) push(p + 1);
            if (p >= w) push(p - w);
            if (p < w * (h - 1)) push(p + w);
        }

        // Soften the edge: light pixels touching the removed area become partly transparent
        for (var q = 0; q < w * h; q++) {
            if (seen[q]) continue;
            var qx = q % w;
            var touches = (qx > 0 && seen[q - 1]) || (qx < w - 1 && seen[q + 1]) ||
                          (q >= w && seen[q - w]) || (q < w * (h - 1) && seen[q + w]);
            if (!touches) continue;
            var m = minChannel(q * 4);
            if (m > SOFT) px[q * 4 + 3] = Math.round(255 * (HARD - m) / (HARD - SOFT));
        }

        ctx.putImageData(data, 0, 0);
        return canvas.toDataURL("image/png");
    }

    function process(img) {
        if (img.dataset.knockout) return;
        var src = img.currentSrc || img.src;
        if (!src || src.indexOf("data:") === 0) return;
        if (cache[src] === false) return;
        if (cache[src]) { apply(img, cache[src]); return; }
        if (!img.complete || !img.naturalWidth) {
            img.addEventListener("load", function () { process(img); }, { once: true });
            return;
        }
        var result = knockout(img);
        cache[src] = result || false;
        if (result) apply(img, result);
    }

    function apply(img, url) {
        img.dataset.knockout = "1";
        img.dataset.originalSrc = img.src;
        img.src = url;
        img.classList.add("is-knocked-out");
    }

    function run() {
        var imgs = document.querySelectorAll(SELECTOR);
        for (var i = 0; i < imgs.length; i++) process(imgs[i]);
    }

    // Run after the page (and the template's sliders, which clone images) has loaded
    if (document.readyState === "complete") run();
    else window.addEventListener("load", run);
    // Catch images that sliders add later
    if (window.MutationObserver) {
        new MutationObserver(function () { run(); }).observe(document.documentElement, { childList: true, subtree: true });
    }
})();
