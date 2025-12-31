=== SEO Wunderkiste v2.4 ===
Die modulare Lösung für besseres WordPress SEO, Performance, Sicherheit und Verwaltung.

--- BESCHREIBUNG ---
Dieses Plugin vereint 14 leistungsstarke Funktionen in einem einzigen Tool. 
Um die Performance deiner Seite zu schonen, sind standardmäßig alle Module deaktiviert. 
Du kannst unter "Einstellungen > SEO Wunderkiste" genau die Funktionen aktivieren, die du benötigst.

--- DIE MODULE ---

=== SEO & CONTENT (4 Module) ===

1. SEO Schema (JSON-LD)
   Fügt Beiträgen und Seiten ein Eingabefeld hinzu, um individuellen Schema.org Code (JSON-LD) in den <head> einzufügen. 

2. Bulk NoIndex Manager ⭐
   Ermöglicht das massenhafte Setzen und Entfernen von NoIndex für Seiten und Beiträge direkt aus der Übersicht.
   Features: Bulk-Aktionen, Status-Spalte, Quick-Edit, automatische Meta-Tag-Ausgabe

3. SEO Zombie Killer
   Leitet sinnlose Anhang-Seiten (Attachment Pages), die Google nicht mag, automatisch per 301-Redirect auf den zugehörigen Beitrag um.

4. Conversion Tracker ⭐ NEU
   Ermöglicht GA4 und Google Ads Conversion-Tracking auf einzelnen Seiten.
   Perfekt für: Danke-Seiten, Bestellbestätigungen, Download-Seiten
   Features: Separate Felder für GA4 und Google Ads, Conversion Value Support

=== BILD & MEDIA (5 Module) ===

5. Image Resizer (800px)
   Fügt in den Mediendetails einen Button hinzu, um riesige Bilder mit einem Klick auf webfreundliche 800px herunterzuskalieren (92% Qualität).

6. Upload Cleaner
   Bereinigt Dateinamen automatisch beim Upload.
   Beispiel: Aus "Mein Foto_Übersicht.JPG" wird automatisch "mein-foto-uebersicht.jpg".

7. Zero-Click Image SEO
   Generiert aus dem Dateinamen automatisch den Bild-Titel und den Alt-Text (Alternativtext) für Google. 
   Beispiel: "mein-neues-projekt.jpg" erzeugt den Alt-Text "Mein Neues Projekt". 

8. Media Inspector
   Zeigt in der Medienübersicht (Listenansicht) zwei neue Spalten an:
   - Dateigröße (KB/MB)
   - Abmessungen (Pixel)

9. SVG Upload Support
   Erlaubt das Hochladen von SVG-Dateien in die Mediathek (standardmäßig von WordPress blockiert).

=== PERFORMANCE (1 Modul) ===

10. Emoji Bloat Remover
    Entfernt das unnötige JavaScript und CSS für WordPress-Emojis. Das macht die Seite messbar schneller.

=== SICHERHEIT & ADMIN (4 Module) ===

11. XML-RPC Blocker
    Deaktiviert die XML-RPC Schnittstelle. Dies schützt deine Seite effektiv vor Brute-Force-Angriffen und unnötiger Serverlast.

12. Login Türsteher
    Schützt deinen Admin-Bereich. Die Login-Seite ist nur noch erreichbar, wenn ein geheimer Parameter an die URL angehängt wird.
    Konfigurierbarer Schlüssel (Standard: "hintereingang")

13. Comment Blocker ⭐
    Deaktiviert Kommentare global auf der gesamten Website.
    Features: Entfernt alle Kommentar-Menüs, schließt bestehende Kommentare, Bulk-Aktion

14. ID Column Display ⭐ NEU
    Zeigt die Post/Page/Media ID in allen Übersichten an (klickbar zum Kopieren).
    Features: Funktioniert für alle Post-Types, sortierbar, One-Click-Copy

--- INSTALLATION & AKTIVIERUNG ---

1.  Lade den Ordner 'seo-wunderkiste' in das Verzeichnis '/wp-content/plugins/' hoch 
    oder lade die ZIP-Datei über das WordPress-Backend (Plugins > Installieren) hoch.
2.  Aktiviere das Plugin im Menü 'Plugins' in WordPress.
3.  WICHTIG: Gehe nach der Aktivierung zu "Einstellungen > SEO Wunderkiste".
4.  Setze Haken bei den Modulen, die du nutzen möchtest, und klicke auf "Speichern". 

--- BENUTZUNG ---

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

> Wie funktionieren Cleaner, Image SEO und Redirects?
Diese Module arbeiten vollautomatisch im Hintergrund, sobald sie in den Einstellungen aktiviert wurden. 
Es ist kein weiteres Zutun nötig.

--- CHANGELOG ---

v2.4 (2024) 🎉 MAJOR UPDATE
- NEU: Conversion Tracker für GA4 und Google Ads
- NEU: ID Column Display mit Copy-Funktion
- NEU: Komplett überarbeitete Admin-Oberfläche
- NEU: Plugin-Links in der Plugin-Liste
- NEU: Aktivierungs-Notice mit Quick-Link
- NEU: Debug-Info im Admin-Footer (nur für Admins)
- Verbessert: Bessere Code-Struktur und Dokumentation
- Verbessert: Performance-Optimierung beim Laden der Module
- Fix: Diverse kleine Bugfixes

v2.3 (2024)
- NEU: ID Column Display mit klickbarer Copy-Funktion
- Verbessert: Optimierte Performance bei modularer Ladung

v2.2 (2024)
- NEU: Bulk NoIndex Manager mit Quick-Edit und Spaltenanzeige
- NEU: Comment Blocker für globale Kommentar-Deaktivierung
- Verbessert: Bessere Organisation der Einstellungen

v2.1 (2024)
- NEU: SVG Upload Support
- NEU: Emoji Bloat Remover
- NEU: XML-RPC Blocker
- NEU: Login Türsteher mit konfigurierbarem Schlüssel
- Verbessert: Image Resizer Qualität auf 92%

v2.0 (Initial Release)
- Initiale Veröffentlichung mit 6 Basis-Modulen

--- MODUL-STRUKTUR ---

includes/
├── admin-settings.php           # Admin-Einstellungsseite
├── module-schema.php           # SEO Schema
├── module-bulk-noindex.php     # NoIndex Manager
├── module-seo-redirects.php    # Zombie Killer
├── module-conversion-tracker.php # Conversion Tracking ⭐ NEU
├── module-resizer.php          # Image Resizer
├── module-cleaner.php          # Upload Cleaner
├── module-image-seo.php        # Image SEO
├── module-media-columns.php    # Media Inspector
├── module-svg.php              # SVG Support
├── module-disable-emojis.php   # Emoji Remover
├── module-disable-xmlrpc.php   # XML-RPC Blocker
├── module-login-protection.php # Login Türsteher
├── module-comment-blocker.php  # Comment Blocker
└── module-id-column.php        # ID Display ⭐ NEU

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
