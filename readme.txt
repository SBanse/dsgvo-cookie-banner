=== DSGVO Cookie Banner ===
Contributors: sbanse
Tags: dsgvo, gdpr, cookie, cookie banner, datenschutz, consent, cookie-scanner, script-blocking, youtube-placeholder, google-maps
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.0
Stable tag: 2.0.0
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

DSGVO/GDPR-konformer Cookie-Banner mit zweiphasigem Browser-Scanner, Script-Blocking, Einbettungs-Platzhaltern und Einwilligungsprotokoll.

== Beschreibung ==

**DSGVO Cookie Banner** bietet alles für einen rechtskonformen Cookie-Hinweis ohne externe Dienste, ohne Tracking-Kosten, vollständig in WordPress integriert.

= Kernfunktionen =

* DSGVO/GDPR-konformer Banner mit granularer Einwilligung
* Kein Pre-Ticking - nur "Notwendige" sind vorausgewaehlt
* Gleichwertige Buttons (kein Dark Pattern)
* Zweiphasiger Cookie-Scanner: Server-Scan + Browser-Scan
* Scanner prueft alle veroeffentlichten Seiten und Beitraege
* Keine Falsch-Positiven - nur tatsaechlich eingebundene Cookies
* Unvollstaendige Eintraege werden als Fehler markiert
* Manuelle Cookie-Eintraege ergaenzbar und inline bearbeitbar
* Reset-Button fuer automatisch erkannte Eintraege
* Script-Blocking - Scripts laden erst nach Einwilligung
* Einwilligungsprotokoll mit IP-Hash (DSGVO-Nachweispflicht)
* Shortcodes fuer Datenschutzseite und Einstellungs-Button
* Vollstaendig anpassbares Design (Farben, Position, Layout)
* Zweisprachig: Deutsch und Englisch

= Cookie-Scanner =

Der Scanner arbeitet in zwei Phasen:

Phase 1 - Server-Scan: Alle veroeffentlichten Seiten und Beitraege werden per HTTP abgerufen. Set-Cookie-Header, Script-URLs, Inline-Scripts und Embeds werden gegen eine Datenbank von ueber 100 bekannten Drittanbieter-Cookies abgeglichen.

Phase 2 - Browser-Scan: Ein unsichtbares iframe laedt jede oeffentliche Seite im Browser des Admins. Nach 5 Sekunden werden alle tatsaechlich gesetzten document.cookie-Werte gemeldet und abgeglichen. So werden auch Cookies erkannt, die serverseitig unsichtbar sind (z. B. durch GTM, Analytics, Pixel).

Eintraege mit Warnsymbol haben unvollstaendige Angaben (Anbieter, Zweck oder Laufzeit fehlen) und muessen vor der Veroeffentlichung ergaenzt werden.

= Einbettungs-Platzhalter =

Datenschutzkonforme Platzhalter fuer externe Inhalte:

* YouTube - [dcb_youtube id="VIDEO_ID"]
* Vimeo - [dcb_vimeo id="VIDEO_ID"]
* Google Maps - [dcb_googlemaps src="EMBED_URL"]
* OpenStreetMap - [dcb_openstreetmap lat="52.52" lng="13.40"]
* Instagram - [dcb_instagram url="POST_URL"]
* X / Twitter - [dcb_twitter url="TWEET_URL"]
* Facebook - [dcb_facebook url="POST_URL"]
* Generisch - [dcb_embed type="youtube" id="VIDEO_ID"]

= Script-Blocking =

    <!-- Vorher -->
    <script src="https://example.com/analytics.js"></script>

    <!-- Nachher -->
    <script type="text/plain" data-dcb-category="statistics"
            src="https://example.com/analytics.js"></script>

= Shortcodes =

* [dcb_cookie_list] - Cookie-Tabelle fuer Datenschutzseite
* [dcb_cookie_list category="statistics"] - Nur eine Kategorie
* [dcb_privacy_settings] - Button zum Oeffnen der Cookie-Einstellungen
* [dcb_privacy_settings text="Anpassen"] - Mit eigenem Text

== Installation ==

1. Plugin-ZIP unter Plugins -> Installieren -> Plugin hochladen installieren
2. Plugin aktivieren
3. Unter Cookie Banner -> Cookie-Scanner einen Scan durchfuehren
4. Alle markierten Eintraege mit Anbieter, Zweck und Laufzeit ergaenzen
5. [dcb_cookie_list] in Ihre Datenschutzseite einfuegen
6. Unter Einstellungen Datenschutz- und Impressumsseite verknuepfen

== Haeufig gestellte Fragen ==

= Ist das Plugin wirklich DSGVO-konform? =
Das Plugin implementiert die technischen Anforderungen der DSGVO. Eine rechtliche Garantie kann kein Plugin geben.

= Welche PHP-Version wird benoetigt? =
PHP 7.0 oder hoeher. Empfohlen: PHP 8.0+.

= Warum dauert der Scan laenger als frueher? =
Ab Version 2.0.0 scannt das Plugin alle veroeffentlichten Seiten und Beitraege (bis zu 20), nicht mehr nur die Startseite. Der Browser-Scan laedt jede Seite fuer 5 Sekunden. Das ist beabsichtigt, damit seitenspezifische Scripts korrekt erkannt werden.

= Was bedeutet das Warnsymbol in der Cookie-Liste? =
Ein Eintrag ist unvollstaendig - Anbieter, Zweck oder Laufzeit fehlen. Kein Sie auf Bearbeiten um die fehlenden Angaben zu ergaenzen.

= Kann ich eigene Einbettungs-Typen erstellen? =
Ja. Unter Cookie Banner -> Einbettungen -> Neuen Typ hinzufuegen koennen beliebige Dienste angelegt werden.

== Changelog ==

= 2.0.0 =
* NEU: Zweiphasiger Cookie-Scanner (Server-Scan + Browser-Scan via iframe/postMessage)
* NEU: Scanner prueft alle veroeffentlichten Seiten und Beitraege (bis zu 20)
* NEU: Browser-Scan erkennt Drittanbieter-Cookies nach Script-Ausfuehrung
* NEU: Unvollstaendige Cookie-Eintraege werden orange markiert
* NEU: Zaehler unvollstaendiger Eintraege im Tabellen-Header
* NEU: Markierung verschwindet dynamisch nach Ausfuellen aller Pflichtfelder
* NEU: Reset-Button loescht automatisch erkannte Eintraege
* NEU: Loeschen einzelner Cookies repariert
* NEU: Hilfe-Tab um Scanner-Dokumentation erweitert
* VERBESSERT: Falsch-Positive eliminiert - Plugin-Slug-Datenbank entfernt
* VERBESSERT: Matching-Bug behoben (_gat matcht nicht mehr auf _gat_UA-*)
* FIX: Delete-Handler gibt keine HTTP 403 mehr zurueck

= 1.3.0 =
* NEU: Elementor-Integration mit 9 Widgets

= 1.2.0 =
* NEU: Einbettungs-Platzhalter (YouTube, Vimeo, Google Maps, Instagram, X, Facebook)
* NEU: Admin-Seite Einbettungen mit CRUD
* NEU: Eigene Einbettungs-Typen erstellen
* VERBESSERT: PHP 7.0+ Kompatibilitaet

= 1.1.0 =
* NEU: Vollstaendiges DE/EN-Sprachsystem
* NEU: Kategorien bearbeitbar

= 1.0.0 =
* Erstveroeffentlichung
