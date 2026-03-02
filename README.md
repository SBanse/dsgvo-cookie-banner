# DSGVO Cookie Banner

**DSGVO/GDPR-konformer Cookie-Banner für WordPress** mit zweiphasigem Cookie-Scanner, Script-Blocking, Einbettungs-Platzhaltern für YouTube/Maps/Social Media und Einwilligungsprotokoll.

Version: **2.0.0** · Lizenz: GPL-2.0+ · PHP: 7.0+ · WordPress: 5.8+

---

## Features

| Feature | Beschreibung |
|---|---|
| 🍪 **Cookie-Banner** | DSGVO-konform, kein Pre-Ticking, gleichwertige Buttons |
| 🔍 **Cookie-Scanner** | Zweiphasig: Server-Scan + Browser-Scan aller öffentlichen Seiten |
| ⚠ **Vollständigkeitsprüfung** | Unvollständige Einträge werden orange markiert bis Anbieter/Zweck/Laufzeit ergänzt sind |
| 🔒 **Script-Blocking** | Scripts laden erst nach Einwilligung der Besucher |
| 🖼️ **Embed-Platzhalter** | YouTube, Vimeo, Google Maps, Instagram, X/Twitter, Facebook, OpenStreetMap |
| 📋 **Shortcodes** | Cookie-Liste, Einstellungs-Button, alle Einbettungs-Typen |
| 📊 **Einwilligungsprotokoll** | IP-Hash + Zeitstempel für DSGVO-Nachweispflicht |
| 🌍 **Zweisprachig** | Vollständig auf Deutsch und Englisch |
| 🎨 **Anpassbares Design** | Farben, Position, Layout, Kategorien-Namen, Platzhalter-Texte |
| 🧩 **Elementor** | 9 Widgets in eigener Kategorie |

---

## Cookie-Scanner

Der Scanner arbeitet in zwei Phasen und liefert nur Cookies, die tatsächlich auf Ihrer Website eingebunden sind.

### Phase 1 – Server-Scan

Alle veröffentlichten Seiten und Beiträge (bis zu 20, nach letzter Änderung sortiert) werden per HTTP abgerufen:

- **Set-Cookie-Header** → serverseitig gesetzte Cookies (WooCommerce, Cache-Plugins, Wordfence …)
- **Script-URLs im HTML** → externe Tracking-Dienste erkennen
- **Inline-Script-Blöcke** → GTM-Container-IDs, Pixel-Initialisierung …
- **iframe/img-URLs** → Pixel-Tracking, eingebettete Karten …

### Phase 2 – Browser-Scan

Ein unsichtbares `<iframe>` lädt jede öffentliche Seite im Browser des Admins. Nach 5 Sekunden – genug Zeit für GTM, Analytics, Facebook Pixel und alle anderen Drittanbieter-Scripts – werden alle `document.cookie`-Werte gemeldet:

- Erkennt Cookies, die **erst nach Script-Ausführung** gesetzt werden
- Kein Falsch-Positiv durch bloße Plugin-Installation
- Scannt seitenspezifisch: Shop-Tracking auf Produktseiten, Embeds auf Blog-Artikeln …

### Unvollständige Einträge

Einträge bei denen Anbieter, Zweck oder Laufzeit fehlen oder einen Platzhalter-Wert enthalten, werden **orange markiert**:

- ⚠-Badge vor dem Cookie-Namen
- Orangefarbener linker Rand der Tabellenzeile
- Zähler im Tabellen-Header: „⚠ 3 unvollständig"

Die Markierung **verschwindet automatisch** sobald alle drei Felder im Inline-Editor ausgefüllt und gespeichert wurden.

### Scan starten

```
Admin → Cookie Banner → Cookie-Scanner → „Scan starten"
```

Der Fortschrittsbalken zeigt vier Phasen:
1. Server-Scan (HTTP, Dateien)
2. Browser-Scan (Seiten im iframe laden)
3. Wartezeit pro Seite (5s für Drittanbieter-Scripts)
4. Cookies auswerten und speichern

### Reset

Der Button **„Auto-Liste zurücksetzen"** entfernt alle automatisch erkannten Einträge. Manuell hinzugefügte Cookies bleiben erhalten. Danach kann ein neuer Scan gestartet werden.

---

## Installation

1. ZIP herunterladen
2. In WordPress: **Plugins → Installieren → Plugin hochladen**
3. Aktivieren
4. **Cookie Banner → Cookie-Scanner** → Scan starten
5. Alle ⚠-markierten Einträge mit Anbieter, Zweck und Laufzeit ergänzen
6. `[dcb_cookie_list]` auf Datenschutzseite einfügen
7. Unter **Einstellungen** Datenschutz- und Impressumsseite verknüpfen

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

Im Admin unter **Cookie Banner → Einbettungen → Neuen Typ hinzufügen** können beliebige Dienste (TikTok, Spotify, Twitch …) mit eigenem Icon, Farben und Texten (DE + EN) angelegt werden. Der Shortcode lautet dann automatisch `[dcb_TYPNAME]`.

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
| **Cookie-Scanner** | Zweiphasiger Scan, manuelle Einträge, Inline-Bearbeitung, Reset |
| **Einbettungen** | Platzhalter-Typen verwalten, Texte/Farben anpassen, eigene Typen erstellen |
| **Einwilligungen** | Protokoll aller Einwilligungen mit IP-Hash und Zeitstempel |
| **Hilfe** | Scanner-Dokumentation, Shortcode-Referenz, Script-Blocking-Anleitung, Compliance-Checkliste |

---

## DSGVO-Compliance-Checkliste

- ✅ Kein Pre-Ticking – nur „Notwendige" vorausgewählt
- ✅ Granulare Kategorien – kein reines „Alle akzeptieren"
- ✅ Einwilligungsprotokoll mit IP-Hash und Zeitstempel
- ✅ Widerruf jederzeit möglich
- ✅ Link zur Datenschutzerklärung im Banner
- ✅ Gleichwertige Buttons – kein Dark Pattern
- ✅ Script-Blocking verhindert vorzeitiges Laden
- ✅ Embed-Platzhalter für Drittanbieter-Inhalte
- ✅ Alle Cookie-Einträge vollständig (Anbieter, Zweck, Laufzeit ausgefüllt)

> **Hinweis:** Das Plugin implementiert die technischen Anforderungen der DSGVO. Eine rechtliche Garantie kann kein Plugin geben – Prüfung durch einen Datenschutzbeauftragten empfohlen.

---

## Dateistruktur

```
dsgvo-cookie-banner/
├── dsgvo-cookie-banner.php          # Plugin-Hauptdatei
├── includes/
│   ├── class-cookie-manager.php     # Einstellungen, Cookies, Einwilligungen
│   ├── class-cookie-scanner.php     # Zweiphasiger Cookie-Scanner
│   ├── class-shortcodes.php         # [dcb_cookie_list], [dcb_privacy_settings]
│   ├── class-embeds.php             # Einbettungs-Typen (CRUD)
│   ├── class-embed-shortcodes.php   # Embed-Shortcodes Frontend
│   └── class-i18n.php               # DE/EN Übersetzungen
├── admin/
│   ├── class-admin.php              # Admin-Menü, Einstellungen, AJAX-Handler
│   ├── admin.css                    # Admin-Styles inkl. Incomplete-Markierung
│   ├── admin.js                     # Admin-Logik, zweiphasiger Scan, Live-Updates
│   ├── embeds.css / embeds.js       # Embed-Admin-Assets
│   └── views/
│       ├── settings.php             # Einstellungsseite (alle Tabs inkl. Hilfe)
│       ├── scanner.php              # Cookie-Scanner-Seite
│       ├── embeds.php               # Einbettungen-Seite
│       └── consents.php             # Einwilligungsprotokoll
├── public/
│   ├── class-frontend.php           # Frontend + Browser-Scan-Reporter-Endpoint
│   ├── css/frontend.css             # Banner-Styles
│   ├── css/embeds.css               # Embed-Platzhalter-Styles
│   ├── js/frontend.js               # Banner-Logik, Consent-Management
│   └── js/embeds.js                 # Embed-Placeholder-Interaktion
└── elementor/
    ├── class-elementor-integration.php  # Elementor-Loader
    ├── widget-embed-base.php            # Abstrakte Basis-Klasse
    └── widgets/                         # 9 Elementor-Widgets
```

---

## Changelog

### 2.0.0
- NEU: Zweiphasiger Cookie-Scanner (Server-Scan + Browser-Scan via iframe/postMessage)
- NEU: Scanner prüft alle veröffentlichten Seiten und Beiträge (bis zu 20)
- NEU: Browser-Scan erkennt Drittanbieter-Cookies nach Script-Ausführung
- NEU: Unvollständige Cookie-Einträge werden orange markiert (⚠-Badge, oranger Rand)
- NEU: Zähler unvollständiger Einträge im Tabellen-Header
- NEU: Markierung verschwindet dynamisch nach Ausfüllen aller Pflichtfelder
- NEU: Reset-Button löscht automatisch erkannte Einträge
- NEU: Löschen einzelner Cookies zuverlässig repariert
- NEU: Hilfe-Tab um Scanner-Dokumentation erweitert
- NEU: DSGVO-Checkliste um Punkt 9 (vollständige Einträge) erweitert
- VERBESSERT: Falsch-Positive eliminiert – Plugin-Slug-Datenbank und Options-Scan entfernt
- VERBESSERT: Matching-Bug behoben: _gat matcht nicht mehr auf _gat_UA-*
- VERBESSERT: Keyword-Map bereinigt (keine zu weit gefassten Strings mehr)
- FIX: Delete-Handler HTTP 403 entfernt (verhinderte jQuery .fail()-Trigger)
- FIX: delete_cookie_entry ist idempotent

### 1.3.0
- NEU: Elementor-Integration mit 9 Widgets in eigener Kategorie

### 1.2.0
- NEU: Einbettungs-Platzhalter (YouTube, Vimeo, Google Maps, OpenStreetMap, Instagram, X, Facebook)
- NEU: Admin-Seite „Einbettungen" mit CRUD
- NEU: Eigene Einbettungs-Typen erstellen
- VERBESSERT: PHP 7.0+ Kompatibilität

### 1.1.0
- NEU: Vollständiges DE/EN-Sprachsystem
- NEU: Kategorien bearbeitbar

### 1.0.0
- Erstveröffentlichung

---

## Lizenz

GPL-2.0+ · https://www.gnu.org/licenses/gpl-2.0.html
