/* global DCBConfig */
(function () {
  'use strict';

  const C = DCBConfig;
  const cfg = C.settings;

  // ─── Apply CSS variables ───────────────────────────────────────────────────
  document.documentElement.style.setProperty('--dcb-primary',  cfg.primary_color);
  document.documentElement.style.setProperty('--dcb-text',     cfg.text_color);
  document.documentElement.style.setProperty('--dcb-bg',       cfg.bg_color);

  // ─── Cookie helpers ────────────────────────────────────────────────────────
  function getCookie(name) {
    return document.cookie.split('; ').reduce((acc, c) => {
      const [k, v] = c.split('=');
      return k === name ? decodeURIComponent(v) : acc;
    }, null);
  }

  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + days * 864e5);
    document.cookie = `${name}=${encodeURIComponent(value)};expires=${d.toUTCString()};path=/;SameSite=Lax`;
  }

  function getConsent() {
    try { return JSON.parse(getCookie(C.cookie_name) || 'null'); } catch { return null; }
  }

  function saveConsent(cats) {
    const consent = {
      version: '1.0',
      timestamp: new Date().toISOString(),
      categories: cats,
    };
    setCookie(C.cookie_name, JSON.stringify(consent), C.lifetime);

    // AJAX log
    const fd = new FormData();
    fd.append('action', 'dcb_save_consent');
    fd.append('nonce', C.nonce);
    fd.append('consent', JSON.stringify(consent));
    fetch(C.ajax_url, { method: 'POST', body: fd }).catch(() => {});

    applyConsent(consent);
    return consent;
  }

  // ─── Script unblocking ─────────────────────────────────────────────────────
  const categoryScriptMap = {
    statistics: ['google-analytics', 'analytics', 'gtag', 'matomo', 'hotjar', '_ga', '_pk'],
    marketing:  ['facebook', 'fbevents', 'fbq', 'linkedin', 'twitter', 'doubleclick', 'adwords'],
    preferences:['preference', 'livechat', 'intercom'],
  };

  function applyConsent(consent) {
    document.querySelectorAll('script[type="text/plain"][data-dcb-category]').forEach(el => {
      const cat = el.dataset.dcbCategory;
      if (consent.categories[cat]) {
        const s = document.createElement('script');
        [...el.attributes].forEach(a => { if (a.name !== 'type') s.setAttribute(a.name, a.value); });
        s.removeAttribute('type');
        s.textContent = el.textContent;
        if (el.src) s.src = el.src;
        el.parentNode.replaceChild(s, el);
      }
    });
    // Fire custom event for third-party integrations
    document.dispatchEvent(new CustomEvent('dcb:consent', { detail: consent }));
  }

  // ─── Render banner ─────────────────────────────────────────────────────────
  function buildBanner() {
    const root = document.getElementById('dcb-banner-root');
    root.innerHTML = `
      <div id="dcb-banner" class="dcb-pos-${cfg.position} dcb-layout-${cfg.layout}" role="dialog" aria-modal="true" aria-label="Cookie-Einstellungen">
        <div class="dcb-banner-inner">
          <div>
            <p class="dcb-banner-title">${esc(cfg.title)}</p>
            <p class="dcb-banner-text">${esc(cfg.text)}</p>
          </div>
          <div class="dcb-banner-btns">
            <button class="dcb-btn dcb-btn-primary"   id="dcb-accept-all">${esc(cfg.accept_all)}</button>
            <button class="dcb-btn dcb-btn-secondary" id="dcb-accept-necessary">${esc(cfg.accept_necessary)}</button>
            <button class="dcb-btn dcb-btn-text"      id="dcb-customize">${esc(cfg.customize)}</button>
          </div>
          <div class="dcb-links">
            ${C.privacy_url && C.privacy_url !== '#' ? `<a href="${C.privacy_url}">Datenschutz</a>` : ''}
            ${C.imprint_url && C.imprint_url !== '#' ? ` &middot; <a href="${C.imprint_url}">Impressum</a>` : ''}
          </div>
        </div>
      </div>`;

    document.getElementById('dcb-accept-all').onclick       = () => acceptAll();
    document.getElementById('dcb-accept-necessary').onclick = () => acceptNecessary();
    document.getElementById('dcb-customize').onclick        = () => openModal();
  }

  function hideBanner() {
    const b = document.getElementById('dcb-banner');
    if (b) { b.style.opacity = '0'; setTimeout(() => b.remove(), 300); }
    const o = document.getElementById('dcb-overlay-wrap');
    if (o) o.remove();
  }

  function acceptAll() {
    const cats = {};
    Object.keys(cfg.categories).forEach(k => cats[k] = true);
    saveConsent(cats);
    hideBanner();
  }

  function acceptNecessary() {
    const cats = {};
    Object.keys(cfg.categories).forEach(k => cats[k] = !!cfg.categories[k].required);
    saveConsent(cats);
    hideBanner();
  }

  // ─── Detail modal ──────────────────────────────────────────────────────────
  function openModal() {
    const existing = getConsent();
    let rows = '';
    Object.entries(cfg.categories).forEach(([key, cat]) => {
      const checked  = existing ? !!existing.categories[key] : !!cat.required;
      const disabled = cat.required ? 'disabled checked' : (checked ? 'checked' : '');
      rows += `
        <div class="dcb-category">
          <div class="dcb-category-header">
            <label class="dcb-toggle">
              <input type="checkbox" data-cat="${key}" ${disabled}>
              <span class="dcb-slider"></span>
            </label>
            <label>${esc(cat.label)}${cat.required ? ' <small>(Immer aktiv)</small>' : ''}</label>
          </div>
          <div class="dcb-category-desc">${esc(cat.description)}</div>
        </div>`;
    });

    const overlay = document.createElement('div');
    overlay.id = 'dcb-overlay-wrap';
    overlay.innerHTML = `
      <div id="dcb-overlay">
        <div id="dcb-modal" role="dialog" aria-modal="true">
          <button class="dcb-modal-close" id="dcb-modal-close" aria-label="Schließen">&times;</button>
          <h2>${esc(cfg.title)}</h2>
          <p>${esc(cfg.text)}</p>
          ${rows}
          <div class="dcb-modal-btns">
            <button class="dcb-btn dcb-btn-primary"   id="dcb-modal-save">${esc(cfg.save_settings)}</button>
            <button class="dcb-btn dcb-btn-secondary" id="dcb-modal-all">Alle akzeptieren</button>
            <button class="dcb-btn dcb-btn-text"      id="dcb-modal-necessary">Nur notwendige</button>
          </div>
        </div>
      </div>`;
    document.body.appendChild(overlay);

    document.getElementById('dcb-modal-close').onclick     = () => overlay.remove();
    document.getElementById('dcb-overlay').onclick         = e => { if (e.target.id === 'dcb-overlay') overlay.remove(); };
    document.getElementById('dcb-modal-all').onclick       = () => { acceptAll(); overlay.remove(); };
    document.getElementById('dcb-modal-necessary').onclick = () => { acceptNecessary(); overlay.remove(); };
    document.getElementById('dcb-modal-save').onclick      = () => {
      const cats = {};
      document.querySelectorAll('#dcb-modal [data-cat]').forEach(cb => {
        cats[cb.dataset.cat] = cb.checked;
      });
      saveConsent(cats);
      hideBanner();
      overlay.remove();
    };
  }

  function esc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  // ─── Public API ────────────────────────────────────────────────────────────
  window.DCB = {
    openBanner: () => { buildBanner(); },
  };

  // ─── Init ──────────────────────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    const consent = getConsent();
    if (!consent) {
      buildBanner();
    } else {
      applyConsent(consent);
    }
  });
})();
