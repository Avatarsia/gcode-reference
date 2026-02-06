# WordPress Test Anleitung

## ✅ SCHNELLTEST

### 1. Plugin installieren
- Gehen Sie zu: **Plugins → Installieren → Plugin hochladen**
- Laden Sie `gcode-reference-2.0.1.zip` hoch
- Klicken Sie **Aktivieren**

### 2. Testseite erstellen
- Gehen Sie zu: **Seiten → Erstellen**
- Titel: "G-code Test"
- Inhalt: Fügen Sie ein:

```
[gcode_reference]
```

- Klicken Sie **Veröffentlichen**

### 3. Testen
- Öffnen Sie die Seite im Frontend
- **Erwartetes Ergebnis:** Interaktive G-code Referenz mit:
  - Suchfeld oben
  - Liste der G-code Befehle links
  - Details rechts
  - DE/EN Buttons oben rechts

---

## 🔍 Wenn nichts erscheint:

### Browser Console öffnen (F12)
Suchen Sie nach einem dieser ECHTEN Fehler:

#### ❌ 404 Fehler
```
Failed to load resource: 404
marlin-commands.json not found
```
**Fix:** JSON-Datei wurde nicht hochgeladen

#### ❌ JavaScript Fehler
```
Uncaught ReferenceError: Fuse is not defined
```
**Fix:** fuse.min.js fehlt

#### ❌ Config Fehler
```
GCodeRefConfig.jsonUrl missing
```
**Fix:** wp_add_inline_script Problem

---

## ✅ Alternative Tests

### Test mit Klipper:
```
[gcode_reference source="klipper"]
```

### Test mit custom Höhe:
```
[gcode_reference height="800px"]
```

---

## 📸 Screenshot machen

Falls es NICHT funktioniert:
1. Machen Sie Screenshot vom Frontend (ganze Seite)
2. Machen Sie Screenshot der Browser Console (F12)
3. Senden Sie beide Screenshots

Dann kann ich das genaue Problem sehen! 🔧
