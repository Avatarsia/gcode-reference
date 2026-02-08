# Anleitung zum Aktualisieren des Plugins

Dank des neuen Build-Skripts `npm run zip` können Updates nun **ohne** vorheriges Löschen durchgeführt werden.

## 🚀 Schnellanleitung (Empfohlen)

1.  **Vorbereitung:**
    *   Führe deine Code-Änderungen durch.
    *   Erhöhe die **Version** in `gcode-reference.php` (z.B. von `2.1.1` auf `2.1.2`).
    *   Erhöhe ebenfalls die Version in `package.json`.

2.  **ZIP-Paket erstellen:**
    *   Öffne ein Terminal im Projektordner.
    *   Führe den Befehl aus:
        ```powershell
        npm run zip
        ```
    *   Dies erstellt automatisch die Datei **`gcode-reference-update.zip`**.
    *   *Hinweis: Das Skript sorgt dafür, dass die Ordnerstruktur korrekt ist und keine Entwickler-Dateien enthalten sind.*

3.  **In WordPress hochladen:**
    *   Gehe im WordPress-Adminbereich zu **Plugins > Installieren > Plugin hochladen**.
    *   Wähle die Datei `gcode-reference-update.zip` aus.
    *   Klicke auf **Jetzt installieren**.

4.  **Bestätigen:**
    *   WordPress erkennt, dass das Plugin bereits existiert.
    *   Vergleiche die Versionsnummern (Aktuell vs. Hochgeladen).
    *   Klicke auf den Button **"Das aktuelle Plugin durch das hochgeladene ersetzen"**.

✅ **Fertig!** Alle Einstellungen und hochgeladenen JSON-Dateien bleiben erhalten.

---

## ⚠️ Wichtige Hinweise

*   **Keine manuellen Änderungen im Plugin-Ordner:**
    Beim Update wird der Ordner `wp-content/plugins/gcode-reference/` komplett überschrieben. Änderungen, die du direkt dort (via FTP) gemacht hast, gehen verloren.
    
*   **Sichere Daten:**
    *   **Einstellungen:** Werden sicher in der Datenbank gespeichert.
    *   **Eigene JSON-Dateien:** Werden sicher in `wp-content/uploads/gcode-reference/` gespeichert und bleiben beim Update erhalten.

*   **Fehlerbehebung:**
    Sollte WordPress das Update nicht als "Ersetzen" anbieten, hast du wahrscheinlich eine ZIP-Datei mit falscher Struktur verwendet (z.B. Ordnername mit Versionsnummer). Verwende immer `npm run zip`, um dies zu verhindern.
