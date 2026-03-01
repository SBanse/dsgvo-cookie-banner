/* global DCBConfig */
(function () {
  'use strict';

  const C   = DCBConfig;
  const cfg = C.settings;

  // ─── i18n helper ─────────────────────────────────────────────────────────
  const i18n = C.i18n || {};
  function __(key, fallback) {
    return i18n[key] || fallback || key;
  }
  document.documentElement.style.setProperty('--dcb-primary', cfg.primary_color);
  document.documentElement.style.setProperty('--dcb-text',    cfg.text_color);
  document.documentElement.style.setProperty('--dcb-bg',      cfg.bg_color);

  // ─── Singleton-Flags: stellen sicher, dass Banner & Modal je nur 1x existieren
  let bannerVisible = false;
  let modalVisible  = false;

  // ─── Cookie-Helfer ────────────────────────────────────────────────────────
  function getCookie(name) {
    return document.cookie.split('; ').reduce((acc, c) => {
      const [k, ...rest] = c.split('=');
      return k === name ? decodeURIComponent(rest.join('=')) : acc;
    }, null);
  }

  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + days * 864e5);
    document.cookie =
      name + '=' + encodeURIComponent(value) +
      ';expires=' + d.toUTCString() +
      ';path=/;SameSite=Lax';
  }

  function getConsent() {
    try { return JSON.parse(getCookie(C.cookie_name) || 'null'); } catch { return null; }
  }

  function saveConsent(cats) {
    const consent = {
      version:    '1.0',
      timestamp:  new Date().toISOString(),
      categories: cats,
    };
    setCookie(C.cookie_name, JSON.stringify(consent), C.lifetime);

    // AJAX-Log (fire-and-forget)
    const fd = new FormData();
    fd.append('action',  'dcb_save_consent');
    fd.append('nonce',   C.nonce);
    fd.append('consent', JSON.stringify(consent));
    fetch(C.ajax_url, { method: 'POST', body: fd }).catch(() => {});

    applyConsent(consent);
    return consent;
  }

  // ─── Script-Freigabe ──────────────────────────────────────────────────────
  // Build lookup: block_key -> internal_key (from categories config)
  function buildBlockKeyMap() {
    const map = {};
    const cats = cfg.categories || {};
    Object.entries(cats).forEach(function([internalKey, cat]) {
      const bk = cat.block_key || internalKey;
      map[bk]          = internalKey; // block_key → internal
      map[internalKey] = internalKey; // also accept internal key directly (backwards compat)
    });
    return map;
  }

  function applyConsent(consent) {
    const blockMap = buildBlockKeyMap();
    document.querySelectorAll('script[type="text/plain"][data-dcb-category]').forEach(el => {
      const rawKey     = el.dataset.dcbCategory;
      const internalKey = blockMap[rawKey] || rawKey; // resolve block_key → internal
      if (consent.categories[internalKey]) {
        const s = document.createElement('script');
        [...el.attributes].forEach(a => { if (a.name !== 'type') s.setAttribute(a.name, a.value); });
        s.removeAttribute('type');
        s.textContent = el.textContent;
        el.parentNode.replaceChild(s, el);
      }
    });
    document.dispatchEvent(new CustomEvent('dcb:consent', { detail: consent }));
  }

  // ─── Banner ───────────────────────────────────────────────────────────────
  function buildBanner() {
    // SINGLETON: Wenn Banner bereits sichtbar ist, nichts tun
    if (bannerVisible) return;
    // Sicherstellen, dass kein verwaistes Banner im DOM ist
    const existing = document.getElementById('dcb-banner');
    if (existing) existing.remove();

    bannerVisible = true;

    const root = document.getElementById('dcb-banner-root');
    if (!root) return;

    root.innerHTML =
      '<div id="dcb-banner" class="dcb-pos-' + cfg.position + ' dcb-layout-' + cfg.layout + '"' +
      ' role="dialog" aria-modal="true" aria-label="Cookie-Einstellungen">' +
      '<div class="dcb-banner-inner">' +
      '<div><p class="dcb-banner-title">' + esc(cfg.title) + '</p>' +
      '<p class="dcb-banner-text">' + esc(cfg.text) + '</p></div>' +
      '<div class="dcb-banner-btns">' +
      '<button class="dcb-btn dcb-btn-primary"   id="dcb-accept-all">'       + esc(cfg.accept_all)       + '</button>' +
      '<button class="dcb-btn dcb-btn-secondary" id="dcb-accept-necessary">' + esc(cfg.accept_necessary) + '</button>' +
      '<button class="dcb-btn dcb-btn-text"      id="dcb-customize">'        + esc(cfg.customize)        + '</button>' +
      '</div>' +
      '<div class="dcb-links">' +
      (C.privacy_url && C.privacy_url !== '#' ? '<a href="' + C.privacy_url + '">' + esc(__('privacy_link','Datenschutz')) + '</a>' : '') +
      (C.imprint_url && C.imprint_url !== '#' ? ' &middot; <a href="' + C.imprint_url + '">' + esc(__('imprint_link','Impressum')) + '</a>' : '') +
      '</div></div></div>';

    document.getElementById('dcb-accept-all').onclick       = function () { acceptAll(); };
    document.getElementById('dcb-accept-necessary').onclick = function () { acceptNecessary(); };
    document.getElementById('dcb-customize').onclick        = function () { openModal(); };
  }

  function hideBanner() {
    const b = document.getElementById('dcb-banner');
    if (b) {
      b.style.opacity = '0';
      b.style.transition = 'opacity .3s';
      setTimeout(function () { if (b.parentNode) b.parentNode.removeChild(b); }, 320);
    }
    bannerVisible = false;
  }

  function acceptAll() {
    const cats = {};
    Object.keys(cfg.categories).forEach(function (k) { cats[k] = true; });
    saveConsent(cats);
    closeAll();
  }

  function acceptNecessary() {
    const cats = {};
    Object.keys(cfg.categories).forEach(function (k) { cats[k] = !!cfg.categories[k].required; });
    saveConsent(cats);
    closeAll();
  }

  // Schließt Banner + Modal in einem Schritt
  function closeAll() {
    hideBanner();
    closeModal();
  }

  // ─── Modal ────────────────────────────────────────────────────────────────
  function openModal() {
    // SINGLETON: Wenn Modal bereits offen ist, nur fokussieren
    if (modalVisible) {
      const m = document.getElementById('dcb-modal');
      if (m) m.focus();
      return;
    }
    modalVisible = true;

    const existing = getConsent();
    let rows = '';
    Object.entries(cfg.categories).forEach(function ([key, cat]) {
      const checked  = existing ? !!existing.categories[key] : !!cat.required;
      const isReq    = !!cat.required;
      rows +=
        '<div class="dcb-category">' +
        '<div class="dcb-category-header">' +
        '<label class="dcb-toggle">' +
        '<input type="checkbox" data-cat="' + esc(key) + '"' +
        (isReq ? ' disabled checked' : (checked ? ' checked' : '')) + '>' +
        '<span class="dcb-slider"></span>' +
        '</label>' +
        '<label>' + esc(cat.label) + (isReq ? ' <small>(' + esc(__('always_active','Immer aktiv')) + ')</small>' : '') + '</label>' +
        '</div>' +
        '<div class="dcb-category-desc">' + esc(cat.description) + '</div>' +
        '</div>';
    });

    const overlay = document.createElement('div');
    overlay.id = 'dcb-overlay-wrap';
    overlay.innerHTML =
      '<div id="dcb-overlay">' +
      '<div id="dcb-modal" role="dialog" aria-modal="true" tabindex="-1">' +
      '<button class="dcb-modal-close" id="dcb-modal-close" aria-label="Schließen">&times;</button>' +
      '<h2>' + esc(cfg.title) + '</h2>' +
      '<p>' + esc(cfg.text) + '</p>' +
      rows +
      '<div class="dcb-modal-btns">' +
      '<button class="dcb-btn dcb-btn-primary"   id="dcb-modal-save">'      + esc(cfg.save_settings) + '</button>' +
      '<button class="dcb-btn dcb-btn-secondary" id="dcb-modal-all">'       + esc(__('accept_all', 'Alle akzeptieren')) + '</button>' +
      '<button class="dcb-btn dcb-btn-text"      id="dcb-modal-necessary">' + esc(__('necessary_only', 'Nur notwendige')) + '</button>' +
      '</div></div></div>';

    document.body.appendChild(overlay);
    // Fokus setzen für Barrierefreiheit
    setTimeout(function () {
      const m = document.getElementById('dcb-modal');
      if (m) m.focus();
    }, 50);

    document.getElementById('dcb-modal-close').onclick = function () { closeModal(); };

    // Overlay-Hintergrund schließt Modal nur wenn Banner nicht mehr sichtbar
    document.getElementById('dcb-overlay').onclick = function (e) {
      if (e.target.id === 'dcb-overlay') closeModal();
    };

    document.getElementById('dcb-modal-all').onclick = function () {
      acceptAll();
    };
    document.getElementById('dcb-modal-necessary').onclick = function () {
      acceptNecessary();
    };
    document.getElementById('dcb-modal-save').onclick = function () {
      const cats = {};
      document.querySelectorAll('#dcb-modal [data-cat]').forEach(function (cb) {
        cats[cb.dataset.cat] = cb.checked;
      });
      saveConsent(cats);
      closeAll();
    };

    // ESC-Taste schließt Modal
    document._dcbEscHandler = function (e) {
      if (e.key === 'Escape') closeModal();
    };
    document.addEventListener('keydown', document._dcbEscHandler);
  }

  function closeModal() {
    const wrap = document.getElementById('dcb-overlay-wrap');
    if (wrap) wrap.parentNode.removeChild(wrap);
    modalVisible = false;
    if (document._dcbEscHandler) {
      document.removeEventListener('keydown', document._dcbEscHandler);
      delete document._dcbEscHandler;
    }
  }

  // ─── HTML-Escape ─────────────────────────────────────────────────────────
  function esc(str) {
    return String(str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // ─── Öffentliche API ──────────────────────────────────────────────────────
  window.DCB = {
    // Banner öffnen (z. B. via Shortcode-Button) – nur wenn noch kein Banner sichtbar
    openBanner: function () {
      if (!bannerVisible && !modalVisible) {
        buildBanner();
      } else if (!modalVisible) {
        openModal(); // Banner läuft bereits → direkt Einstellungen öffnen
      }
    },
    // Direkt Einstellungs-Modal öffnen
    openSettings: function () {
      if (!modalVisible) openModal();
    },
    // Aktuellen Einwilligungsstatus abfragen
    getConsent: getConsent,
  };

  // ─── Initialisierung ─────────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    const consent = getConsent();
    if (!consent) {
      buildBanner();
    } else {
      applyConsent(consent);
    }
  });

})();
