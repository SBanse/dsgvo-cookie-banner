=== DSGVO Cookie Banner ===
Contributors: sbanse
Tags: dsgvo, gdpr, cookie, cookie banner, datenschutz, consent, cookie-scanner, script-blocking, youtube-placeholder, google-maps
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.0
Stable tag: 1.2.0
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

DSGVO/GDPR-konformer Cookie-Banner mit automatischem Scanner, Script-Blocking, Einbettungs-Platzhaltern für YouTube/Maps/Social und Einwilligungsprotokoll.

== Beschreibung ==

**DSGVO Cookie Banner** bietet alles für einen rechtskonformen Cookie-Hinweis – ohne externe Dienste, keine Tracking-Kosten, vollständig in WordPress integriert.

= Kernfunktionen =

* ✅ DSGVO/GDPR-konformer Banner mit granularer Einwilligung
* ✅ Kein Pre-Ticking – nur „Notwendige" sind vorausgewählt
* ✅ Gleichwertige Buttons (kein Dark Pattern)
* ✅ Automatischer Cookie-Scanner (Plugins, Theme, bekannte Dienste)
* ✅ Manuelle Cookie-Einträge ergänzbar und inline bearbeitbar
* ✅ Script-Blocking – Scripts laden erst nach Einwilligung
* ✅ Einwilligungsprotokoll mit IP-Hash (DSGVO-Nachweispflicht)
* ✅ Shortcodes für Datenschutzseite und Einstellungs-Button
* ✅ Vollständig anpassbares Design (Farben, Position, Layout)
* ✅ Zweisprachig: Deutsch und Englisch

= Einbettungs-Platzhalter =

Datenschutzkonforme Platzhalter für externe Inhalte – Besucher sehen einen Info-Block und laden den Inhalt erst nach Einwilligung:

* **YouTube** – `[dcb_youtube id="VIDEO_ID"]`
* **Vimeo** – `[dcb_vimeo id="VIDEO_ID"]`
* **Google Maps** – `[dcb_googlemaps src="EMBED_URL"]`
* **OpenStreetMap** – `[dcb_openstreetmap lat="52.52" lng="13.40"]`
* **Instagram** – `[dcb_instagram url="POST_URL"]`
* **X / Twitter** – `[dcb_twitter url="TWEET_URL"]`
* **Facebook** – `[dcb_facebook url="POST_URL"]`
* **Generisch** – `[dcb_embed type="youtube" id="VIDEO_ID"]`

Platzhalter-Texte, Farben und Icons sind im Admin unter **Einbettungen** vollständig bearbeitbar. Eigene Typen können erstellt werden.

= Script-Blocking =

Externe Scripts erst nach Einwilligung laden:

    <!-- Vorher -->
    <script src="https://example.com/analytics.js"></script>

    <!-- Nachher -->
    <script type="text/plain" data-dcb-category="statistics"
            src="https://example.com/analytics.js"></script>

Das Script wird automatisch aktiviert, sobald der Besucher der Kategorie zustimmt.

= Shortcodes =

* `[dcb_cookie_list]` – Cookie-Tabelle für Datenschutzseite
* `[dcb_cookie_list category="statistics"]` – Nur eine Kategorie
* `[dcb_privacy_settings]` – Button zum Öffnen der Cookie-Einstellungen
* `[dcb_privacy_settings text="Anpassen"]` – Mit eigenem Text

= Kategorien =

Vier voreingestellte Kategorien, alle frei anpassbar (Label, Beschreibung, Schlüssel):

* **Notwendig** – Immer aktiv, nicht deaktivierbar
* **Statistik** – z. B. Google Analytics, Matomo
* **Marketing** – z. B. Facebook Pixel, Google Ads
* **Präferenzen** – z. B. Sprachwahl, Theme

== Installation ==

1. Plugin-ZIP unter **Plugins → Installieren → Plugin hochladen** installieren
2. Plugin aktivieren
3. Unter **Cookie Banner → Cookie-Scanner** einen Scan durchführen
4. `[dcb_cookie_list]` in Ihre Datenschutzseite einfügen
5. Unter **Einstellungen** Datenschutz- und Impressumsseite verknüpfen
6. Design und Texte nach Wunsch anpassen

== Shortcodes ==

= Cookie-Liste =
    [dcb_cookie_list]
    [dcb_cookie_list category="statistics"]
    [dcb_cookie_list category="marketing"]

= Einstellungs-Button =
    [dcb_privacy_settings]
    [dcb_privacy_settings text="Cookie-Einstellungen öffnen"]

= Einbettungen =
    [dcb_youtube id="dQw4w9WgXcQ" width="100%" height="400"]
    [dcb_vimeo id="123456789"]
    [dcb_googlemaps src="https://maps.google.com/maps?q=Berlin&output=embed" height="450"]
    [dcb_openstreetmap lat="52.52" lng="13.40" zoom="14" height="400"]
    [dcb_instagram url="https://www.instagram.com/p/ABC123/"]
    [dcb_twitter url="https://twitter.com/user/status/123"]
    [dcb_facebook url="https://www.facebook.com/user/posts/123"]

== Script-Blocking ==

Ändern Sie `type="text/javascript"` zu `type="text/plain"` und fügen Sie `data-dcb-category` hinzu:

    <script type="text/plain" data-dcb-category="statistics"
            src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXX"></script>

Verfügbare Kategorien: `necessary`, `statistics`, `marketing`, `preferences`
(oder Ihr eigener `block_key` aus den Kategorien-Einstellungen)

== JavaScript-Event ==

Nach jeder Einwilligung feuert das Plugin das Event `dcb:consent`:

    document.addEventListener('dcb:consent', function(e) {
        var consent = e.detail;
        if (consent.categories.statistics) {
            // Analytics initialisieren
        }
    });

== Häufig gestellte Fragen ==

= Ist das Plugin wirklich DSGVO-konform? =
Das Plugin implementiert die technischen Anforderungen der DSGVO (kein Pre-Ticking, granulare Kategorien, Widerrufsmöglichkeit, Protokollierung). Eine rechtliche Garantie kann kein Plugin geben – wir empfehlen die Prüfung durch einen Datenschutzbeauftragten.

= Welche PHP-Version wird benötigt? =
PHP 7.0 oder höher. Empfohlen: PHP 8.0+.

= Kann ich eigene Einbettungs-Typen erstellen? =
Ja. Unter **Cookie Banner → Einbettungen → Neuen Typ hinzufügen** können beliebige Dienste (z. B. TikTok, Spotify, Twitch) mit eigenem Icon, Farben und Texten angelegt werden.

= Wie wird das Einwilligungsprotokoll gespeichert? =
In der WordPress-Datenbank (wp_options). IP-Adressen werden als SHA-256-Hash gespeichert. Das Protokoll kann unter **Cookie Banner → Einwilligungen** eingesehen werden.

= Kann ich die Kategorien umbenennen? =
Ja. Unter **Einstellungen → Kategorien** können Label, Beschreibung, Shortcode-Schlüssel und Blockierungs-Schlüssel jeder Kategorie angepasst werden.

== Screenshots ==

1. Cookie-Banner im Frontend (Standard-Layout)
2. Erweiterte Einstellungen mit Kategorien-Auswahl
3. Admin-Oberfläche: Cookie-Scanner mit Inline-Bearbeitung
4. Admin-Oberfläche: Einbettungs-Platzhalter verwalten
5. Einbettungs-Platzhalter im Frontend (YouTube-Beispiel)
6. Admin-Oberfläche: Hilfe-Tab mit vollständiger Dokumentation

== Changelog ==

= 1.2.0 =
* NEU: Einbettungs-Platzhalter für YouTube, Vimeo, Google Maps, OpenStreetMap, Instagram, X/Twitter, Facebook
* NEU: Admin-Seite „Einbettungen" zum Verwalten und Bearbeiten aller Typen
* NEU: Eigene Einbettungs-Typen erstellen
* NEU: „Immer erlauben"-Button setzt dauerhaftes Cookie für einzelne Dienste
* NEU: Shortcodes [dcb_youtube], [dcb_vimeo], [dcb_googlemaps], [dcb_openstreetmap], [dcb_instagram], [dcb_twitter], [dcb_facebook], [dcb_embed]
* NEU: Hilfe-Tab vollständig überarbeitet mit Shortcode-Referenz, Script-Blocking-Anleitung, Kategorien-Tabelle, JS-Event-Dokumentation und Compliance-Checkliste
* VERBESSERT: PHP 7.0+ Kompatibilität sichergestellt
* VERBESSERT: AJAX-Handler nutzen wp_verify_nonce statt check_ajax_referer für JSON-sichere Fehlerantworten
* VERBESSERT: Output-Buffer werden vor JSON-Antwort geleert (verhindert Fehler durch PHP-Notices anderer Plugins)
* VERBESSERT: JavaScript-Fehlerbehandlung robuster – JSON-Extraktion aus korrumpierten Antworten

= 1.1.0 =
* NEU: Vollständiges Deutsch/Englisch-Sprachsystem (DCB_I18n)
* NEU: Kategorien bearbeitbar (Label, Beschreibung, Shortcode-Schlüssel, Blockierungs-Schlüssel)
* NEU: Live-Vorschau der Kategorie-Schlüssel im Admin
* VERBESSERT: Shortcode [dcb_cookie_list] unterstützt benutzerdefinierte Schlüssel

= 1.0.0 =
* Erstveröffentlichung
* DSGVO-konformer Banner mit Einwilligungsverwaltung
* Automatischer Cookie-Scanner
* Script-Blocking
* Einwilligungsprotokoll
* Shortcodes [dcb_cookie_list] und [dcb_privacy_settings]
