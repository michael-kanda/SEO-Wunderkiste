=== SEO Wunderkiste v2.2 ===
Die modulare Lösung für besseres WordPress SEO, Sicherheit und schlanke Bilder.

--- BESCHREIBUNG ---
Dieses Plugin vereint 12 leistungsstarke Funktionen in einem einzigen Tool. 
Um die Performance deiner Seite zu schonen, sind standardmäßig alle Module deaktiviert. 
Du kannst unter "Einstellungen > SEO Wunderkiste" genau die Funktionen aktivieren, die du benötigst.

--- DIE MODULE ---

1. SEO Schema (JSON-LD)
   Fügt Beiträgen und Seiten ein Eingabefeld hinzu, um individuellen Schema.org Code (JSON-LD) in den <head> einzufügen. 

2. Image Resizer (800px)
   Fügt in den Mediendetails einen Button hinzu, um riesige Bilder mit einem Klick auf webfreundliche 800px herunterzuskalieren. 

3. Upload Cleaner
   Bereinigt Dateinamen automatisch beim Upload.
   Beispiel: Aus "Mein Foto_Übersicht.JPG" wird automatisch "mein-foto-uebersicht.jpg".

4. Zero-Click Image SEO
   Generiert aus dem Dateinamen automatisch den Bild-Titel und den Alt-Text (Alternativtext) für Google. 
   Beispiel: "mein-neues-projekt.jpg" erzeugt den Alt-Text "Mein Neues Projekt". 

5. Media Library Inspector
   Zeigt in der Medienübersicht (Listenansicht) zwei neue Spalten an:
   - Dateigröße (KB/MB)
   - Abmessungen (Pixel)

6. SEO Zombie Killer (Redirects)
   Leitet sinnlose Anhang-Seiten (Attachment Pages), die Google nicht mag, automatisch per 301-Redirect auf den zugehörigen Beitrag um.

7. SVG Upload Support
   Erlaubt das Hochladen von SVG-Dateien in die Mediathek (standardmäßig von WordPress blockiert).

8. Emoji Bloat Remover
   Entfernt das unnötige JavaScript und CSS für WordPress-Emojis. Das macht die Seite messbar schneller.

9. XML-RPC Blocker
   Deaktiviert die XML-RPC Schnittstelle. Dies schützt deine Seite effektiv vor Brute-Force-Angriffen und unnötiger Serverlast.

10. Login Türsteher
    Schützt deinen Admin-Bereich. Die Login-Seite ist nur noch erreichbar, wenn ein geheimer Parameter an die URL angehängt wird.

11. Bulk NoIndex Manager
    Ermöglicht das massenhafte Setzen und Entfernen von NoIndex für Seiten und Beiträge direkt aus der Übersicht.
    Features:
    - Bulk-Aktionen für mehrere Einträge gleichzeitig
    - Neue Spalte mit NoIndex-Status in der Übersicht
    - Quick-Edit Unterstützung
    - Automatische Meta-Tag-Ausgabe im Frontend

12. Comment Blocker
    Deaktiviert Kommentare global auf der gesamten Website.
    Features:
    - Entfernt Kommentar-Support aus allen Post-Types
    - Versteckt Kommentar-Menüs im Backend
    - Schließt bestehende Kommentare
    - Bulk-Aktion zum Schließen von Kommentaren
    - Deaktiviert Kommentar-Feeds

--- INSTALLATION & AKTIVIERUNG ---

1.  Lade den Ordner 'seo-wunderkiste' in das Verzeichnis '/wp-content/plugins/' hoch oder lade die ZIP-Datei über das WordPress-Backend (Plugins > Installieren) hoch.
2.  Aktiviere das Plugin im Menü 'Plugins' in WordPress.
3.  WICHTIG: Gehe nach der Aktivierung zu "Einstellungen > SEO Wunderkiste".
4.  Setze Haken bei den Modulen, die du nutzen möchtest, und klicke auf "Speichern". 

--- BENUTZUNG ---

> Wie nutze ich das Schema-Feld?
Gehe in einen Beitrag oder eine Seite. Unter dem Editor findest du die Box "Strukturierte Daten". Füge dort dein JSON-Objekt ein (ohne <script> Tags). 

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

> Wie funktioniert der Comment Blocker?
Aktiviere das Modul in den Einstellungen - es arbeitet dann vollautomatisch:
- Neue Beiträge/Seiten haben standardmäßig keine Kommentare
- Bestehende Kommentare werden geschlossen
- Kommentar-Menüs verschwinden aus dem Backend
- Optional: Nutze die Bulk-Aktion "Kommentare schließen" für bestehende Inhalte

> Wie funktionieren Cleaner, Image SEO und Redirects?
Diese Module arbeiten vollautomatisch im Hintergrund, sobald sie in den Einstellungen aktiviert wurden. Es ist kein weiteres Zutun nötig.

--- CHANGELOG ---

v2.2 (2024)
- NEU: Bulk NoIndex Manager mit Quick-Edit und Spaltenanzeige
- NEU: Comment Blocker für globale Kommentar-Deaktivierung
- Verbessert: Bessere Organisation der Einstellungen
- Verbessert: Optimierte Performance bei modularer Ladung

v2.1 (2024)
- NEU: SVG Upload Support
- NEU: Emoji Bloat Remover
- NEU: XML-RPC Blocker
- NEU: Login Türsteher mit konfigurierbarem Schlüssel
- Verbessert: Image Resizer Qualität auf 92%

v2.0 (Initial Release)
- Initiale Veröffentlichung mit 6 Basis-Modulen

--- ANFORDERUNGEN ---
* WordPress Version: 5.0 oder höher
* PHP Version: 7.4 oder höher empfohlen

--- AUTOR ---
Entwickelt von Michael Kanda

--- SUPPORT & FEEDBACK ---
Bei Fragen oder Feature-Wünschen kannst du gerne Feedback über die WordPress Plugin-Bewertungen geben.
