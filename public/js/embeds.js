/* global DCBConfig, DCBEmbeds */
(function () {
    'use strict';

    // ── Helpers ───────────────────────────────────────────────────────────────
    function setCookie(name, value, days) {
        var d = new Date();
        d.setTime(d.getTime() + days * 86400000);
        document.cookie = name + '=' + encodeURIComponent(value)
            + ';expires=' + d.toUTCString()
            + ';path=/;SameSite=Lax';
    }

    function getCookie(name) {
        var v = document.cookie.split('; ').filter(function(c){ return c.indexOf(name + '=') === 0; });
        return v.length ? decodeURIComponent(v[0].split('=')[1]) : null;
    }

    // Build consent categories map for block_key → allowed check
    function isCategoryAllowed(blockKey) {
        if (typeof DCBConfig === 'undefined') return false;
        try {
            var consent = JSON.parse(getCookie(DCBConfig.cookie_name) || 'null');
            if (!consent) return false;
            // Resolve block_key → internal key via categories config
            var cats = DCBConfig.settings.categories || {};
            var internalKey = blockKey; // default
            Object.keys(cats).forEach(function(k) {
                if ((cats[k].block_key || k) === blockKey) internalKey = k;
            });
            return !!consent.categories[internalKey];
        } catch(e) { return false; }
    }

    // ── Render a loaded iframe inside the container ───────────────────────────
    function renderIframe(wrap) {
        var type   = wrap.getAttribute('data-dcb-embed-type');
        var src    = wrap.getAttribute('data-dcb-embed-src');
        var width  = wrap.getAttribute('data-dcb-embed-width')  || '100%';
        var height = wrap.getAttribute('data-dcb-embed-height') || '315';
        var wCss   = /^\d+$/.test(width)  ? width  + 'px' : width;
        var hCss   = /^\d+$/.test(height) ? height + 'px' : height;

        var container = wrap.querySelector('.dcb-embed-frame-container');
        if (!container) return;

        wrap.classList.add('dcb-embed-loading');

        var html = '';

        // Instagram: blockquote + script
        if (src.indexOf('__instagram__') === 0) {
            var igUrl = src.replace('__instagram__', '');
            html = '<blockquote class="instagram-media" data-instgrm-permalink="' + igUrl + '" style="max-width:100%"></blockquote>';
            container.innerHTML = html;
            container.style.display = 'block';
            swapToIframe(wrap);
            // Load Instagram embed script if not already loaded
            if (!document.querySelector('script[src*="instagram.com/embed"]')) {
                var s = document.createElement('script');
                s.async = true; s.src = '//www.instagram.com/embed.js';
                document.body.appendChild(s);
            } else if (window.instgrm) {
                window.instgrm.Embeds.process();
            }
            return;
        }

        // Twitter/X: blockquote + script
        if (src.indexOf('__twitter__') === 0) {
            var twUrl = src.replace('__twitter__', '');
            html = '<blockquote class="twitter-tweet"><a href="' + twUrl + '"></a></blockquote>';
            container.innerHTML = html;
            container.style.display = 'block';
            swapToIframe(wrap);
            if (!document.querySelector('script[src*="platform.twitter.com"]')) {
                var ts = document.createElement('script');
                ts.async = true; ts.src = 'https://platform.twitter.com/widgets.js';
                ts.charset = 'utf-8';
                document.body.appendChild(ts);
            } else if (window.twttr) {
                window.twttr.widgets.load();
            }
            return;
        }

        // Standard iframe
        var iframe = document.createElement('iframe');
        iframe.src = src;
        iframe.width = width;
        iframe.height = height;
        iframe.style.cssText = 'width:' + wCss + ';height:' + hCss + ';border:0;display:block;';
        iframe.setAttribute('allowfullscreen', '');
        iframe.setAttribute('loading', 'lazy');
        iframe.setAttribute('title', type);
        iframe.className = 'dcb-embed-iframe dcb-embed-' + type;
        iframe.addEventListener('load', function() {
            wrap.classList.remove('dcb-embed-loading');
        });

        container.style.cssText = 'display:block;width:' + wCss + ';height:' + hCss + ';';
        container.innerHTML = '';
        container.appendChild(iframe);
        swapToIframe(wrap);
    }

    function swapToIframe(wrap) {
        var ph = wrap.querySelector('.dcb-embed-placeholder');
        var thumb = wrap.querySelector('.dcb-embed-thumb');
        var container = wrap.querySelector('.dcb-embed-frame-container');

        if (ph) {
            ph.style.transition = 'opacity 0.25s';
            ph.style.opacity = '0';
            setTimeout(function(){ ph.style.display = 'none'; }, 260);
        }
        if (thumb) thumb.style.display = 'none';
        if (container) container.style.display = 'block';

        wrap.classList.remove('dcb-embed-loading');
        wrap.classList.add('dcb-embed-consented');
    }

    // ── Auto-load: check if category consent already given ───────────────────
    function autoLoadConsented() {
        document.querySelectorAll('.dcb-embed-wrap[data-dcb-embed-src]').forEach(function(wrap) {
            var blockKey = wrap.getAttribute('data-dcb-category');
            var alwaysCookie = wrap.getAttribute('data-dcb-always-cookie');

            // Load if: global consent given OR "always allow" cookie set for this type
            if (isCategoryAllowed(blockKey) || getCookie(alwaysCookie)) {
                renderIframe(wrap);
            }
        });
    }

    // ── Button click handlers ─────────────────────────────────────────────────
    document.addEventListener('click', function(e) {

        // "Load once" button
        var onceBtn = e.target.closest('.dcb-embed-load-once');
        if (onceBtn) {
            var wrap = onceBtn.closest('.dcb-embed-wrap');
            if (wrap) renderIframe(wrap);
            return;
        }

        // "Always allow" button
        var alwaysBtn = e.target.closest('.dcb-embed-load-always');
        if (alwaysBtn) {
            var wrap2 = alwaysBtn.closest('.dcb-embed-wrap');
            if (wrap2) {
                var alwaysCookie = wrap2.getAttribute('data-dcb-always-cookie');
                if (alwaysCookie) {
                    // Set "always allow" cookie for 1 year
                    setCookie(alwaysCookie, '1', 365);
                }
                renderIframe(wrap2);
            }
            return;
        }
    });

    // ── Listen for global consent changes (via dcb:consent event) ────────────
    document.addEventListener('dcb:consent', function(e) {
        autoLoadConsented();
    });

    // ── Init on DOM ready ─────────────────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', autoLoadConsented);
    } else {
        autoLoadConsented();
    }

})();
