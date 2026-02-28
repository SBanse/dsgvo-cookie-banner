# 🍪 DSGVO Cookie Banner

> Datenschutzkonformer Cookie-Banner für WordPress – vollständig DSGVO/GDPR-konform, mit automatischem Cookie-Scanner und Shortcode-Integration für Datenschutzerklärung und Impressum.

![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple?logo=php)
![Lizenz](https://img.shields.io/badge/Lizenz-GPL--2.0-green)
![Version](https://img.shields.io/badge/Version-1.0.0-orange)

---

## 📋 Inhaltsverzeichnis

- [Features](#-features)
- [Screenshots](#-screenshots)
- [Installation](#-installation)
- [Shortcodes](#-shortcodes)
- [Script-Blocking](#-script-blocking)
- [DSGVO-Compliance](#-dsgvo-compliance)
- [Anforderungen](#-anforderungen)
- [Changelog](#-changelog)
- [Lizenz](#-lizenz)

---

## ✨ Features

### Banner
- 🎨 Vollständig anpassbares Design (Farben, Position, Layout)
- 📍 3 Positionen: Unten, Oben, Mitte (Modal)
- 🧩 2 Layouts: Leiste oder Box
- 🌐 Texte vollständig editierbar im Backend

### DSGVO-Konformität
- ✅ **Kein Pre-Ticking** – nur "Notwendige" Cookies sind vorausgewählt
- ✅ **Gleichwertige Buttons** – kein Dark Pattern (Ablehnen ist genauso einfach wie Akzeptieren)
- ✅ **Granulare Kategorien** – Nutzer wählt einzelne Kategorien
- ✅ **Widerruf jederzeit** möglich via Shortcode-Button
- ✅ **Einwilligungsprotokoll** mit Zeitstempel und IP-Hash (Nachweispflicht)
- ✅ **Link zur Datenschutzerklärung** direkt im Banner

### Cookie-Scanner
- 🔍 Automatische Erkennung aktiver Plugins & Themes
- 📚 Datenbank mit 30+ bekannten Cookies (Google, Facebook, YouTube, Stripe u.v.m.)
- ✏️ Cookies manuell ergänzen oder löschen
- 📊 Übersicht nach Kategorien (Notwendig, Statistik, Marketing, Präferenzen)

### Shortcodes
- `[dcb_cookie_list]` – Cookie-Tabelle für Datenschutz-/Impressumseite
- `[dcb_privacy_settings]` – Button zum Wiederöffnen der Einstellungen

---

## 🖼 Screenshots

### Cookie-Banner (Frontend)
Der Banner erscheint beim ersten Besuch am unteren Seitenrand. Über „Einstellungen" öffnet sich ein Modal zur granularen Auswahl.

### Cookie-Scanner (Backend)
Unter **WordPress Admin → Cookie Banner → Cookie-Scanner** können Scans gestartet und Cookies manuell verwaltet werden.

### Cookie-Liste (Datenschutzseite)
Der Shortcode `[dcb_cookie_list]` gibt eine formatierte Tabelle aller erkannten Cookies aus:

| Name | Anbieter | Zweck | Laufzeit |
|------|----------|-------|----------|
| `_ga` | Google Analytics | Unterscheidet Nutzer und Sitzungen | 2 Jahre |
| `_fbp` | Facebook / Meta | Facebook-Tracking-Pixel | 3 Monate |
| `dcb_consent` | Diese Website | Speichert Ihre Cookie-Einwilligung | 1 Jahr |

---

## 🚀 Installation

### Methode 1: ZIP-Upload (empfohlen)

1. [Neueste Version herunterladen](../../releases/latest)
2. WordPress Admin → **Plugins → Installieren → Plugin hochladen**
3. ZIP-Datei auswählen und installieren
4. Plugin aktivieren

### Methode 2: Manuell via FTP

```bash
# Repository klonen
git clone https://github.com/sbanse/dsgvo-cookie-banner.git

# In den WordPress-Plugin-Ordner kopieren
cp -r dsgvo-cookie-banner /var/www/html/wp-content/plugins/
```

Danach Plugin im WordPress-Backend aktivieren.

### Ersteinrichtung

1. **Cookie Banner → Cookie-Scanner** → Scan starten
2. Erkannte Cookies prüfen, ggf. manuell ergänzen
3. **Cookie Banner → Einstellungen** → Texte und Design anpassen
4. `[dcb_cookie_list]` in die Datenschutzerklärung einfügen
5. `[dcb_privacy_settings]` optional in Datenschutz-Footer einfügen

---

## 📌 Shortcodes

### `[dcb_cookie_list]`

Gibt alle erkannten Cookies als formatierte Tabelle aus (nach Kategorien gruppiert).

```
[dcb_cookie_list]
```

Nur eine bestimmte Kategorie anzeigen:

```
[dcb_cookie_list category="statistics"]
[dcb_cookie_list category="marketing"]
[dcb_cookie_list category="necessary"]
[dcb_cookie_list category="preferences"]
```

### `[dcb_privacy_settings]`

Fügt einen Button ein, mit dem Nutzer ihre Cookie-Einstellungen jederzeit ändern können (Pflicht laut DSGVO).

```
[dcb_privacy_settings]
[dcb_privacy_settings text="Cookie-Einstellungen anpassen"]
```

---

## 🔒 Script-Blocking

Um Drittanbieter-Scripts erst nach Einwilligung zu laden, ändern Sie den `type`-Attribut und fügen Sie `data-dcb-category` hinzu:

**Vorher:**
```html
<script src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
```

**Nachher:**
```html
<script type="text/plain" data-dcb-category="statistics" src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
```

Das Script wird automatisch aktiviert, sobald der Nutzer die entsprechende Kategorie akzeptiert.

**Verfügbare Kategorien:**

| Wert | Beschreibung |
|------|-------------|
| `necessary` | Immer aktiv, keine Einwilligung nötig |
| `statistics` | z. B. Google Analytics, Matomo, Hotjar |
| `marketing` | z. B. Facebook Pixel, Google Ads |
| `preferences` | z. B. Live-Chat, Spracheinstellungen |

---

## 🛡 DSGVO-Compliance

Dieses Plugin wurde nach den Anforderungen der **DSGVO (EU) 2016/679** und den Leitlinien des **Europäischen Datenschutzausschusses (EDPB)** entwickelt.

### Checkliste

| Anforderung | Status |
|-------------|--------|
| Einwilligung vor Setzen nicht-notwendiger Cookies | ✅ |
| Ablehnen genauso einfach wie Akzeptieren | ✅ |
| Kein Pre-Ticking (außer notwendige Cookies) | ✅ |
| Granulare Kategorienauswahl | ✅ |
| Nachweis der Einwilligung (Protokoll) | ✅ |
| Widerruf der Einwilligung jederzeit möglich | ✅ |
| Link zur Datenschutzerklärung im Banner | ✅ |
| IP-Adressen werden nur als SHA-256-Hash gespeichert | ✅ |

> **Hinweis:** Dieses Plugin ist ein technisches Hilfsmittel. Eine vollständige Rechtsberatung durch einen Datenschutzbeauftragten wird empfohlen.

---

## 📦 Bekannte Cookies in der Scanner-Datenbank

<details>
<summary>Vollständige Liste anzeigen</summary>

| Cookie | Anbieter | Kategorie |
|--------|----------|-----------|
| `_ga`, `_gid`, `_gat` | Google Analytics | Statistik |
| `_gac_*` | Google Ads | Marketing |
| `_pk_id.*`, `_pk_ses.*` | Matomo | Statistik |
| `_fbp`, `fr` | Facebook / Meta | Marketing |
| `_gcl_au` | Google Tag Manager | Marketing |
| `_hj*` | Hotjar | Statistik |
| `YSC`, `VISITOR_INFO1_LIVE` | YouTube (Google) | Marketing |
| `li_gc`, `lidc` | LinkedIn | Marketing |
| `guest_id`, `_twitter_sess` | Twitter / X | Marketing |
| `__stripe_mid`, `__stripe_sid` | Stripe | Notwendig |
| `__cfduid`, `__cf_bm` | Cloudflare | Notwendig |
| `woocommerce_*`, `wp_woocommerce_*` | WooCommerce | Notwendig |
| `wordpress_logged_in_*`, `wp-settings-*` | WordPress | Notwendig |
| `PHPSESSID` | PHP | Notwendig |
| `dcb_consent` | Diese Website | Notwendig |

</details>

---

## ⚙️ Anforderungen

- **WordPress:** 5.8 oder höher
- **PHP:** 7.4 oder höher
- **MySQL:** 5.6 oder höher
- **Browser:** Alle modernen Browser (Chrome, Firefox, Safari, Edge)

---

## 📝 Changelog

### 1.0.0 – Erstveröffentlichung
- Cookie-Banner mit 4 Kategorien
- Automatischer Cookie-Scanner
- Einwilligungsprotokoll
- Script-Blocking
- Shortcodes `[dcb_cookie_list]` und `[dcb_privacy_settings]`
- Admin-UI mit Einstellungen, Scanner und Protokoll-Ansicht

---

## 🤝 Beitragen

Pull Requests sind willkommen! Für größere Änderungen bitte zuerst ein Issue öffnen.

```bash
git clone https://github.com/sbanse/dsgvo-cookie-banner.git
cd dsgvo-cookie-banner
# Änderungen vornehmen
git checkout -b feature/meine-funktion
git commit -m "feat: meine neue Funktion"
git push origin feature/meine-funktion
```

---

## 📄 Lizenz

Dieses Plugin ist unter der [GPL-2.0 Lizenz](https://www.gnu.org/licenses/gpl-2.0.html) veröffentlicht.

---

<p align="center">Mit ❤️ für die DSGVO-Compliance entwickelt</p>
