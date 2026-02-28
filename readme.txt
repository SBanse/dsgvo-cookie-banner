=== DSGVO Cookie Banner ===
Contributors: IhrName
Tags: dsgvo, gdpr, cookie, cookie banner, datenschutz, consent
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0+

Datenschutzkonformer Cookie-Banner nach DSGVO/GDPR mit automatischem Cookie-Scanner und Shortcode für die Datenschutzerklärung.

== Beschreibung ==

DSGVO Cookie Banner bietet alles, was Sie für einen rechtskonformen Cookie-Hinweis benötigen:

**Features:**
* ✅ DSGVO/GDPR-konformer Banner mit granularer Einwilligung
* ✅ Kein Pre-Ticking – nur "Notwendige" sind vorausgewählt
* ✅ Gleichwertige Buttons (kein "Dark Pattern")
* ✅ Automatischer Cookie-Scanner (WordPress-Plugins, Themes, bekannte Dienste)
* ✅ Manuelle Cookie-Einträge ergänzbar
* ✅ Shortcode [dcb_cookie_list] für Datenschutzseite / Impressum
* ✅ Einwilligungsprotokoll (DSGVO-Nachweispflicht, IP als Hash)
* ✅ Script-Blocking bis zur Einwilligung
* ✅ Vollständig anpassbares Design (Farben, Position, Layout)
* ✅ Wiedereröffnen der Einstellungen via [dcb_privacy_settings]

== Installation ==

1. Plugin-Ordner in /wp-content/plugins/ hochladen
2. Plugin im WordPress-Backend aktivieren
3. Unter "Cookie Banner" → "Cookie-Scanner" einen Scan durchführen
4. [dcb_cookie_list] in Ihre Datenschutzerklärung einfügen
5. Einstellungen nach Wunsch anpassen

== Shortcodes ==

* [dcb_cookie_list]                      – Alle Cookies (nach Kategorie gruppiert)
* [dcb_cookie_list category="statistics"] – Nur eine Kategorie
* [dcb_privacy_settings]                 – Button zum Öffnen der Einstellungen
* [dcb_privacy_settings text="Anpassen"] – Button mit eigenem Text

== Script-Blocking ==

Um Drittanbieter-Scripts zu blockieren, ändern Sie den script-Tag:

Vorher:  <script src="https://...analytics.js"></script>
Nachher: <script type="text/plain" data-dcb-category="statistics" src="https://...analytics.js"></script>

Verfügbare Kategorien: necessary, statistics, marketing, preferences

== Changelog ==

= 1.0.0 =
* Erstveröffentlichung
