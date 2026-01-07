=== SEO Wunderkiste v2.6 ===
Die modulare Lösung für besseres WordPress SEO, Performance, Sicherheit und Verwaltung.

--- BESCHREIBUNG ---
Dieses Plugin vereint 16 leistungsstarke Funktionen in einem einzigen Tool. 
Um die Performance deiner Seite zu schonen, sind standardmäßig alle Module deaktiviert. 
Du kannst unter "Einstellungen > SEO Wunderkiste" genau die Funktionen aktivieren, die du benötigst.

--- DIE MODULE ---

=== SEO & CONTENT (5 Module) ===

1. SEO Meta Settings 
   Erweiterte Meta-Tags pro Seite/Beitrag mit Live-Vorschau.
   Features:
   - SEO Title & Meta Description mit Zeichenzähler
   - Robots Meta Tag (index, follow, max-image-preview, etc.)
   - Canonical URL Steuerung
   - Open Graph Tags (Facebook, LinkedIn, WhatsApp)
   - Twitter Cards (summary_large_image, summary)
   - Author & Copyright Meta
   - Google Vorschau im Editor
   - Status-Spalte in der Übersicht

2. SEO Schema (JSON-LD)
   Fügt Beiträgen und Seiten ein Eingabefeld hinzu, um individuellen Schema.org Code (JSON-LD) in den <head> einzufügen. 

3. Bulk NoIndex Manager 
   Ermöglicht das massenhafte Setzen und Entfernen von NoIndex für Seiten und Beiträge direkt aus der Übersicht.
   Features: Bulk-Aktionen, Status-Spalte, Quick-Edit, automatische Meta-Tag-Ausgabe

4. SEO Zombie Killer
   Leitet sinnlose Anhang-Seiten (Attachment Pages), die Google nicht mag, automatisch per 301-Redirect auf den zugehörigen Beitrag um.

5. Conversion Tracker
   Ermöglicht GA4 und Google Ads Conversion-Tracking auf einzelnen Seiten.
   Perfekt für: Danke-Seiten, Bestellbestätigungen, Download-Seiten
   Features: Separate Felder für GA4 und Google Ads, Conversion Value Support

=== BILD & MEDIA (5 Module) ===

6. Image Resizer (800px)
   Fügt in den Mediendetails einen Button hinzu, um riesige Bilder mit einem Klick auf webfreundliche 800px herunterzuskalieren (92% Qualität).

7. Upload Cleaner
   Bereinigt Dateinamen automatisch beim Upload.
   Beispiel: Aus "Mein Foto_Übersicht.JPG" wird automatisch "mein-foto-uebersicht.jpg".

8. Zero-Click Image SEO
   Generiert aus dem Dateinamen automatisch den Bild-Titel und den Alt-Text (Alternativtext) für Google. 
   Beispiel: "mein-neues-projekt.jpg" erzeugt den Alt-Text "Mein Neues Projekt". 

9. Media Inspector
   Zeigt in der Medienübersicht (Listenansicht) zwei neue Spalten an:
   - Dateigröße (KB/MB)
   - Abmessungen (Pixel)

10. SVG Upload Support
    Erlaubt das Hochladen von SVG-Dateien in die Mediathek (standardmäßig von WordPress blockiert).

=== PERFORMANCE (1 Modul) ===

11. Emoji Bloat Remover
    Entfernt das unnötige JavaScript und CSS für WordPress-Emojis. Das macht die Seite messbar schneller.

=== SICHERHEIT & ADMIN (4 Module) ===

12. XML-RPC Blocker
    Deaktiviert die XML-RPC Schnittstelle. Dies schützt deine Seite effektiv vor Brute-Force-Angriffen und unnötiger Serverlast.

13. Login Türsteher
    Schützt deinen Admin-Bereich. Die Login-Seite ist nur noch erreichbar, wenn ein geheimer Parameter an die URL angehängt wird.
    Konfigurierbarer Schlüssel (Standard: "hintereingang")

14. Comment Blocker
    Deaktiviert Kommentare global auf der gesamten Website.
    Features: Entfernt alle Kommentar-Menüs, schließt bestehende Kommentare, Bulk-Aktion

15. ID Column Display
    Zeigt die Post/Page/Media ID in allen Übersichten an (klickbar zum Kopieren).
    Features: Funktioniert für alle Post-Types, sortierbar, One-Click-Copy

=== CONTENT TOOLS (1 Modul) === ⭐ NEU

16. Date Shortcode
    Fügt das aktuelle Datum via Shortcode in Beiträge und Seiten ein.
    Features:
    - Flexible Formate: 01.02.2026, 1. Februar 2026, nur Jahr, nur Monat, etc.
    - Zeitzonenunterstützung: Wien, New York, Tokyo, UTC und mehr
    - Deutsche Übersetzung: Monatsnamen und Wochentage auf Deutsch
    - Editor-Button: Visueller Shortcode-Generator im Editor
    - Kurzformen: [datum], [jahr], [monat] für schnelle Nutzung
    - Wrapper-Optionen: Optional in <time>, <span> etc. einpacken

--- INSTALLATION & AKTIVIERUNG ---

1.  Lade den Ordner 'seo-wunderkiste' in das Verzeichnis '/wp-content/plugins/' hoch 
    oder lade die ZIP-Datei über das WordPress-Backend (Plugins > Installieren) hoch.
2.  Aktiviere das Plugin im Menü 'Plugins' in WordPress.
3.  WICHTIG: Gehe nach der Aktivierung zu "Einstellungen > SEO Wunderkiste".
4.  Setze Haken bei den Modulen, die du nutzen möchtest, und klicke auf "Speichern". 

--- BENUTZUNG ---

> Wie nutze ich die SEO Meta Settings?
1. Aktiviere das Modul in den Einstellungen
2. Öffne eine beliebige Seite oder einen Beitrag
3. Unterhalb des Editors findest du die Box "🔍 SEO Meta Einstellungen"
4. Nutze die Tabs (Basis SEO, Open Graph, Twitter, Erweitert) um alle Meta-Tags einzustellen
5. Die Google Vorschau zeigt dir live, wie dein Snippet aussehen wird
6. Die Spalte "🔍 SEO" in der Übersicht zeigt den Optimierungsgrad an

> Wie nutze ich das Schema-Feld?
Gehe in einen Beitrag oder eine Seite. Unter dem Editor findest du die Box "Strukturierte Daten". 
Füge dort dein JSON-Objekt ein (ohne <script> Tags). 

> Wie skaliere ich Bilder?
Gehe in die Mediathek, klicke ein Bild an. In den Details (rechts oder im Modal) findest du den Button "Auf 800px skalieren". 

> Wie nutze ich den Login Türsteher?
Aktiviere das Modul in den Einstellungen und lege ein geheimes Wort fest (z.B. "supergeheim").
Deine Login-Seite ist ab dann nur noch unter dieser Adresse erreichbar:
deineseite.de/wp-login.php?supergeheim
Ohne diesen Zusatz werden Besucher auf die Startseite umgeleitet.

> Wie nutze ich den Bulk NoIndex Manager?
1. Aktiviere das Modul in den Einstellungen
2. Gehe zur Seiten- oder Beiträge-Übersicht
3. Wähle die gewünschten Einträge aus
4. Wähle in den Bulk-Aktionen "NoIndex setzen" oder "NoIndex entfernen"
5. Die neue Spalte zeigt den aktuellen Status jedes Eintrags an

> Wie nutze ich den Conversion Tracker?
1. Aktiviere das Modul in den Einstellungen
2. Öffne eine Seite (z.B. Danke-Seite nach Formular)
3. Rechte Sidebar: Box "🎯 Conversion Tracking"
4. Aktiviere GA4 und/oder Google Ads
5. Trage Event-Name und Conversion-IDs ein
6. Veröffentlichen - Fertig!
Voraussetzung: GA4 oder Google Ads Tag muss bereits auf der Website installiert sein.

> Wie nutze ich die ID Column?
Aktiviere das Modul - die IDs erscheinen automatisch in allen Übersichten.
Klicke auf eine ID, sie wird automatisch in die Zwischenablage kopiert!

> Wie funktioniert der Comment Blocker?
Aktiviere das Modul in den Einstellungen - es arbeitet dann vollautomatisch:
- Neue Beiträge/Seiten haben standardmäßig keine Kommentare
- Bestehende Kommentare werden geschlossen
- Kommentar-Menüs verschwinden aus dem Backend
- Optional: Nutze die Bulk-Aktion "Kommentare schließen" für bestehende Inhalte

> Wie nutze ich den Date Shortcode? ⭐ NEU
1. Aktiviere das Modul in den Einstellungen
2. Im Editor erscheint ein "Datum" Button neben "Medien hinzufügen"
3. Klicke darauf und wähle dein gewünschtes Format
4. Oder nutze die Shortcodes direkt:

   Basis-Shortcodes:
   [seowk_date]                              → 07.01.2026 (Standard)
   [datum]                                   → Alias für seowk_date
   [jahr]                                    → 2026
   [monat]                                   → Januar

   Format-Optionen (format="..."):
   numeric        → 07.01.2026
   numeric_short  → 7.1.2026
   full           → 7. Januar 2026
   full_day       → Dienstag, 7. Januar 2026
   month_year     → Januar 2026
   year           → 2026
   month          → Januar
   day            → Dienstag
   iso            → 2026-01-07
   us             → 01/07/2026
   time           → 14:30
   datetime       → 07.01.2026 14:30

   Zeitzonen (timezone="..."):
   Europe/Vienna       → Wien
   Europe/Berlin       → Berlin
   Europe/Zurich       → Zürich
   America/New_York    → New York
   America/Los_Angeles → Los Angeles
   Asia/Tokyo          → Tokyo
   UTC                 → UTC

   Zusätzliche Attribute:
   prefix="..."   → Text vor dem Datum
   suffix="..."   → Text nach dem Datum
   wrapper="time" → In HTML-Tag einpacken (span, time, div, etc.)
   class="..."    → CSS-Klasse hinzufügen
   lang="de"      → Sprache (de/en), Standard: de

   Beispiele:
   [seowk_date format="full"]                         → 7. Januar 2026
   [seowk_date format="year" prefix="© "]             → © 2026
   [seowk_date format="month_year"]                   → Januar 2026
   [seowk_date format="full_day" timezone="America/New_York"] → Montag, 6. Januar 2026 (NY Zeit)
   [seowk_date format="datetime" timezone="Europe/Vienna"]    → 07.01.2026 20:30 (Wiener Zeit)
   [seowk_date format="year" wrapper="time" class="copyright-year"] → <time class="copyright-year">2026</time>
   [seowk_date format="d.m.Y H:i:s"]                  → 07.01.2026 14:30:45 (eigenes PHP-Format)

   Typische Anwendungen:
   - Footer Copyright: © [jahr] Meine Firma
   - Artikel-Datum: Stand: [seowk_date format="full"]
   - Zeitzone anzeigen: Aktuelle Zeit in Wien: [seowk_date format="time" timezone="Europe/Vienna" suffix=" Uhr"]

> Wie funktionieren Cleaner, Image SEO und Redirects?
Diese Module arbeiten vollautomatisch im Hintergrund, sobald sie in den Einstellungen aktiviert wurden. 
Es ist kein weiteres Zutun nötig.

--- CHANGELOG ---

v2.6
- NEU: Date Shortcode Modul für dynamische Datumsanzeige
  - 12 vordefinierte Formate (numerisch, ausgeschrieben, ISO, US, etc.)
  - Zeitzonenunterstützung (Wien, Berlin, New York, Tokyo, etc.)
  - Deutsche Übersetzung für Monate und Wochentage
  - Kurzformen [datum], [jahr], [monat]
  - Visueller Shortcode-Generator im Editor
  - Prefix/Suffix und Wrapper-Optionen
  - Eigene PHP-Datumsformate möglich
- Verbessert: Modul-Anzahl jetzt 16 Module
- Aktualisiert: Version auf 2.6
- NEU: Content Tools Kategorie

v2.5 
- NEU: SEO Meta Settings Modul mit vollständigen Meta-Tags
  - SEO Title & Meta Description mit Live-Vorschau
  - Robots Meta Tag Kontrolle
  - Canonical URL Steuerung
  - Open Graph Tags (og:title, og:description, og:image, og:type, etc.)
  - Twitter Card Tags (twitter:card, twitter:title, twitter:description, twitter:image)
  - Author & Copyright Meta
  - Zeichenzähler mit Empfehlungen (60/160 Zeichen)
  - Tab-basierte Benutzeroberfläche
  - Media Uploader Integration für Bilder
  - SEO-Status-Spalte in der Übersicht
  - Automatische Fallbacks (OG → Twitter, Titel → Meta Description)
- Verbessert: Modul-Anzahl jetzt 15 Module
- Aktualisiert: Version auf 2.5

v2.4
- NEU: Conversion Tracker für GA4 und Google Ads
- NEU: ID Column Display mit Copy-Funktion
- NEU: Komplett überarbeitete Admin-Oberfläche
- NEU: Plugin-Links in der Plugin-Liste
- NEU: Aktivierungs-Notice mit Quick-Link
- NEU: Debug-Info im Admin-Footer (nur für Admins)
- Verbessert: Bessere Code-Struktur und Dokumentation
- Verbessert: Performance-Optimierung beim Laden der Module
- Fix: Diverse kleine Bugfixes

v2.3 
- NEU: ID Column Display mit klickbarer Copy-Funktion
- Verbessert: Optimierte Performance bei modularer Ladung

v2.2 
- NEU: Bulk NoIndex Manager mit Quick-Edit und Spaltenanzeige
- NEU: Comment Blocker für globale Kommentar-Deaktivierung
- Verbessert: Bessere Organisation der Einstellungen

v2.1 
- NEU: SVG Upload Support
- NEU: Emoji Bloat Remover
- NEU: XML-RPC Blocker
- NEU: Login Türsteher mit konfigurierbarem Schlüssel
- Verbessert: Image Resizer Qualität auf 92%

v2.0 (Initial Release)
- Initiale Veröffentlichung mit 6 Basis-Modulen

--- MODUL-STRUKTUR ---

includes/
├── admin-settings.php            # Admin-Einstellungsseite
├── module-meta-settings.php      # SEO Meta Settings
├── module-schema.php             # SEO Schema
├── module-bulk-noindex.php       # NoIndex Manager
├── module-seo-redirects.php      # Zombie Killer
├── module-conversion-tracker.php # Conversion Tracking
├── module-resizer.php            # Image Resizer
├── module-cleaner.php            # Upload Cleaner
├── module-image-seo.php          # Image SEO
├── module-media-columns.php      # Media Inspector
├── module-svg.php                # SVG Support
├── module-disable-emojis.php     # Emoji Remover
├── module-disable-xmlrpc.php     # XML-RPC Blocker
├── module-login-protection.php   # Login Türsteher
├── module-comment-blocker.php    # Comment Blocker
├── module-id-column.php          # ID Display
└── module-date-shortcode.php     # Date Shortcode ⭐ NEU

--- META TAGS BEISPIEL (SEO Meta Settings Output) ---

<!-- SEO Wunderkiste - Meta Settings -->
<title>IT-Beratung & Cloud Lösungen in Hamburg | TechSolutions GmbH</title>
<meta name="description" content="Wir optimieren Ihre IT-Infrastruktur...">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<link rel="canonical" href="https://www.example.com/seite/">
<meta property="og:locale" content="de_DE">
<meta property="og:type" content="website">
<meta property="og:title" content="IT-Beratung & Cloud Lösungen">
<meta property="og:description" content="Sichere IT-Lösungen...">
<meta property="og:url" content="https://www.example.com/seite/">
<meta property="og:site_name" content="TechSolutions GmbH">
<meta property="og:image" content="https://www.example.com/bild.jpg">
<meta property="og:image:alt" content="Team im Meeting">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="IT-Beratung & Cloud Lösungen">
<meta name="twitter:description" content="Sichere IT-Lösungen...">
<meta name="twitter:image" content="https://www.example.com/bild.jpg">
<meta name="author" content="TechSolutions GmbH">
<meta name="copyright" content="TechSolutions GmbH">
<meta name="format-detection" content="telephone=yes">
<!-- /SEO Wunderkiste -->

--- ANFORDERUNGEN ---
* WordPress Version: 5.0 oder höher
* PHP Version: 7.4 oder höher empfohlen

--- AUTOR ---
Entwickelt von Michael Kanda

--- SUPPORT & FEEDBACK ---
Bei Fragen oder Feature-Wünschen kannst du gerne Feedback über die WordPress Plugin-Bewertungen geben.

--- LIZENZ ---
GPL-2.0+
Dieses Plugin ist Open Source und kann frei verwendet und modifiziert werden.
