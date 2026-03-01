# DSGVO Cookie Banner

**DSGVO/GDPR-konformer Cookie-Banner für WordPress** mit automatischem Cookie-Scanner, Script-Blocking, Einbettungs-Platzhaltern für YouTube/Maps/Social Media und Einwilligungsprotokoll.

Version: **1.2.0** · Lizenz: GPL-2.0+ · PHP: 7.0+ · WordPress: 5.8+

---

## Features

| Feature | Beschreibung |
|---|---|
| 🍪 **Cookie-Banner** | DSGVO-konform, kein Pre-Ticking, gleichwertige Buttons |
| 🔍 **Cookie-Scanner** | Erkennt automatisch WordPress-Plugins, Theme und bekannte Dienste |
| 🔒 **Script-Blocking** | Scripts laden erst nach Einwilligung der Besucher |
| 🖼️ **Embed-Platzhalter** | YouTube, Vimeo, Google Maps, Instagram, X/Twitter, Facebook, OpenStreetMap |
| 📋 **Shortcodes** | Cookie-Liste, Einstellungs-Button, alle Einbettungs-Typen |
| 📊 **Einwilligungsprotokoll** | IP-Hash + Zeitstempel für DSGVO-Nachweispflicht |
| 🌍 **Zweisprachig** | Vollständig auf Deutsch und Englisch |
| 🎨 **Anpassbares Design** | Farben, Position, Layout, Kategorien-Namen, Platzhalter-Texte |

---

## Installation

1. ZIP herunterladen
2. In WordPress: **Plugins → Installieren → Plugin hochladen**
3. Aktivieren
4. **Cookie Banner → Cookie-Scanner** → Scan starten
5. `[dcb_cookie_list]` auf Datenschutzseite einfügen
6. Unter **Einstellungen** Datenschutz- und Impressumsseite verknüpfen

---

## Shortcodes

### Cookie-Liste (für Datenschutzseite)

```
[dcb_cookie_list]
[dcb_cookie_list category="statistics"]
[dcb_cookie_list category="marketing"]
```

### Einstellungs-Button (zum Widerruf der Einwilligung)

```
[dcb_privacy_settings]
[dcb_privacy_settings text="Cookie-Einstellungen ändern"]
```

---

## Einbettungs-Platzhalter

Externe Inhalte werden erst nach Einwilligung geladen. Der Besucher sieht einen Info-Platzhalter mit zwei Buttons: **Einmal laden** oder **Immer für [Dienst] erlauben**.

### Verfügbare Shortcodes

| Dienst | Shortcode | Parameter |
|---|---|---|
| **YouTube** | `[dcb_youtube]` | `id`, `width`, `height`, `thumbnail` |
| **Vimeo** | `[dcb_vimeo]` | `id`, `width`, `height` |
| **Google Maps** | `[dcb_googlemaps]` | `src`, `width`, `height` |
| **Google Maps (iFrame)** | `[dcb_googlemaps_iframe]` | `src`, `width`, `height` |
| **OpenStreetMap** | `[dcb_openstreetmap]` | `lat`, `lng`, `zoom`, `width`, `height` |
| **Instagram** | `[dcb_instagram]` | `url` |
| **X / Twitter** | `[dcb_twitter]` | `url` |
| **Facebook** | `[dcb_facebook]` | `url` |
| **Generisch** | `[dcb_embed]` | `type`, `id`, `src`, `url`, `width`, `height` |

### Beispiele

```
[dcb_youtube id="dQw4w9WgXcQ" width="100%" height="400"]

[dcb_googlemaps src="https://maps.google.com/maps?q=Berlin&output=embed" height="450"]

[dcb_openstreetmap lat="52.52" lng="13.40" zoom="14" height="400"]

[dcb_instagram url="https://www.instagram.com/p/ABC123/"]
```

### Eigene Einbettungs-Typen

Im Admin unter **Cookie Banner → Einbettungen → Neuen Typ hinzufügen** können beliebige Dienste (TikTok, Spotify, Twitch, ...) mit eigenem Icon, Farben und Texten (DE + EN) angelegt werden. Der Shortcode lautet dann automatisch `[dcb_TYPNAME]`.

---

## Script-Blocking

Externe Scripts blockieren bis zur Einwilligung:

```html
<!-- Vorher -->
<script src="https://www.googletagmanager.com/gtag/js?id=G-XXXX"></script>

<!-- Nachher: type + data-dcb-category ergänzen -->
<script
  type="text/plain"
  data-dcb-category="statistics"
  src="https://www.googletagmanager.com/gtag/js?id=G-XXXX">
</script>
```

Das Script wird automatisch aktiviert, wenn der Besucher der Kategorie zustimmt.

### Verfügbare Kategorien (Standard)

| Kategorie | `data-dcb-category` |
|---|---|
| Notwendig | `necessary` |
| Statistik | `statistics` |
| Marketing | `marketing` |
| Präferenzen | `preferences` |

> Kategorie-Schlüssel können unter **Einstellungen → Kategorien** angepasst werden.

---

## Kategorien anpassen

Unter **Einstellungen → Kategorien** können Sie für jede Kategorie einstellen:

- **Bezeichnung** – Angezeigter Name im Banner
- **Beschreibung** – Erklärungstext für Besucher
- **Shortcode-Schlüssel** – Für `[dcb_cookie_list category="…"]`
- **Blockierungs-Schlüssel** – Für `data-dcb-category="…"`

---

## JavaScript-Event

Nach jeder Einwilligung (auch beim Seitenaufruf mit gespeicherter Einwilligung) feuert das Plugin das Event `dcb:consent` auf `document`:

```javascript
document.addEventListener('dcb:consent', function(e) {
    var consent = e.detail;
    // consent.categories = { necessary: true, statistics: false, ... }

    if (consent.categories.statistics) {
        // Google Analytics initialisieren
        gtag('config', 'G-XXXXXXXX');
    }
    if (consent.categories.marketing) {
        // Facebook Pixel initialisieren
        fbq('init', 'XXXXXXXX');
    }
});
```

---

## Admin-Bereich

| Seite | Beschreibung |
|---|---|
| **Einstellungen** | Banner-Texte, Farben, Position, Kategorien, Datenschutz-/Impressumsseite |
| **Cookie-Scanner** | Automatischer Scan, manuelle Einträge, Inline-Bearbeitung |
| **Einbettungen** | Platzhalter-Typen verwalten, Texte/Farben anpassen, eigene Typen erstellen |
| **Einwilligungen** | Protokoll aller Einwilligungen mit IP-Hash und Zeitstempel |
| **Hilfe** | Vollständige Shortcode-Referenz, Script-Blocking-Anleitung, Compliance-Checkliste |

---

## DSGVO-Compliance

- ✅ Kein Pre-Ticking – nur „Notwendige" vorausgewählt
- ✅ Granulare Kategorien – kein reines „Alle akzeptieren"
- ✅ Einwilligungsprotokoll mit IP-Hash und Zeitstempel
- ✅ Widerruf jederzeit möglich
- ✅ Link zur Datenschutzerklärung im Banner
- ✅ Gleichwertige Buttons – kein Dark Pattern
- ✅ Script-Blocking verhindert vorzeitiges Laden
- ✅ Embed-Platzhalter für Drittanbieter-Inhalte
- ✅ YouTube nutzt `youtube-nocookie.com`
- ✅ Keine Daten an externe Dienste

> **Hinweis:** Das Plugin implementiert die technischen Anforderungen der DSGVO. Eine rechtliche Garantie kann kein Plugin geben – Prüfung durch einen Datenschutzbeauftragten empfohlen.

---

## Dateistruktur

```
dsgvo-cookie-banner/
├── dsgvo-cookie-banner.php          # Plugin-Hauptdatei
├── includes/
│   ├── class-cookie-manager.php     # Einstellungen, Cookies, Einwilligungen
│   ├── class-cookie-scanner.php     # Automatischer Cookie-Scanner
│   ├── class-shortcodes.php         # [dcb_cookie_list], [dcb_privacy_settings]
│   ├── class-embeds.php             # Einbettungs-Typen (CRUD)
│   ├── class-embed-shortcodes.php   # Embed-Shortcodes Frontend
│   └── class-i18n.php               # DE/EN Übersetzungen
├── admin/
│   ├── class-admin.php              # Admin-Menü, Einstellungen, AJAX
│   ├── admin.css / admin.js         # Admin-Assets
│   ├── embeds.css / embeds.js       # Embed-Admin-Assets
│   └── views/
│       ├── settings.php             # Einstellungsseite (alle Tabs)
│       ├── scanner.php              # Cookie-Scanner-Seite
│       ├── embeds.php               # Einbettungen-Seite
│       └── consents.php             # Einwilligungsprotokoll
└── public/
    ├── class-frontend.php           # Frontend-Assets, Banner-HTML
    ├── css/frontend.css             # Banner-Styles
    ├── css/embeds.css               # Embed-Platzhalter-Styles
    ├── js/frontend.js               # Banner-Logik, Consent-Management
    └── js/embeds.js                 # Embed-Placeholder-Interaktion
```

---

## Changelog

### 1.2.0
- NEU: Einbettungs-Platzhalter (YouTube, Vimeo, Google Maps, OpenStreetMap, Instagram, X, Facebook)
- NEU: Admin-Seite „Einbettungen" mit vollständigem CRUD
- NEU: Eigene Einbettungs-Typen erstellen
- NEU: Hilfe-Tab vollständig überarbeitet
- NEU: README.md und readme.txt aktualisiert
- FIX: PHP 7.0+ Kompatibilität (entfernt: typed properties, arrow functions, str_starts_with)
- FIX: AJAX nutzt `wp_verify_nonce` statt `check_ajax_referer` für JSON-sichere Fehlerantworten
- FIX: Output-Buffer leeren vor JSON (verhindert Fehler durch PHP-Notices)

### 1.1.0
- NEU: Vollständiges DE/EN-Sprachsystem
- NEU: Kategorien bearbeitbar (Label, Beschreibung, Schlüssel)
- NEU: Live-Vorschau der Kategorie-Schlüssel

### 1.0.0
- Erstveröffentlichung

---

## Lizenz

GPL-2.0+ · https://www.gnu.org/licenses/gpl-2.0.html
