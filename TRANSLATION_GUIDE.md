# Übersetzungs-Anleitung für G-code Reference Plugin

## 1. UI-Strings (WordPress Backend)

**Datei:** `languages/gcode-reference-de_DE.po`

**19 zu übersetzende Strings:**

```po
msgid "Settings"
msgstr "Einstellungen"

msgid "G-Code Reference Settings"
msgstr "G-Code Referenz Einstellungen"

msgid "JSON File Options"
msgstr "JSON-Datei Optionen"

msgid "Use Default JSON"
msgstr "Standard-JSON verwenden"

msgid "Use Uploaded JSON"
msgstr "Hochgeladene JSON verwenden"

msgid "Upload Custom JSON"
msgstr "Eigene JSON hochladen"

msgid "No uploaded file exists."
msgstr "Keine hochgeladene Datei vorhanden."

msgid "Delete Uploaded JSON"
msgstr "Hochgeladene JSON löschen"

msgid "Save Changes"
msgstr "Änderungen speichern"

msgid "commands.json not found"
msgstr "commands.json nicht gefunden"

msgid "Failed to read commands.json"
msgstr "Fehler beim Lesen von commands.json"

msgid "Invalid JSON: %s"
msgstr "Ungültige JSON: %s"

msgid "Unauthorized"
msgstr "Keine Berechtigung"

msgid "File uploaded successfully."
msgstr "Datei erfolgreich hochgeladen."

msgid "Upload failed."
msgstr "Upload fehlgeschlagen."

msgid "File deleted successfully."
msgstr "Datei erfolgreich gelöscht."

msgid "Delete failed."
msgstr "Löschen fehlgeschlagen."
```

**Nach dem Ausfüllen kompilieren:**
```bash
msgfmt languages/gcode-reference-de_DE.po -o languages/gcode-reference-de_DE.mo
```

---

## 2. G-code Commands (JSON)

**Datei:** `data/marlin-commands.json`

**Struktur:** Jeder Befehl hat optionale `desc.de` Felder:

```json
{
  "id": "G0",
  "desc": {
    "en": "Linear Move (non-printing)",
    "de": "Lineare Bewegung (ohne Extrusion)"
  },
  "params": [
    {
      "name": "X",
      "desc": {
        "en": "X-axis position",
        "de": "X-Achsen-Position"
      }
    }
  ],
  "notes": {
    "en": "G0 and G1 are identical in Marlin",
    "de": "G0 und G1 sind in Marlin identisch"
  }
}
```

**Zu übersetzen:**
1. **`desc.de`** - Befehls-Beschreibung (~150 Befehle)
2. **`params[].desc.de`** - Parameter-Beschreibungen
3. **`notes.de`** - Hinweise/Beispiele

**Beispiele häufiger Befehle:**

| Code | EN | DE Vorschlag |
|------|----|----|
| G0 | Linear Move (non-printing) | Lineare Bewegung (ohne Extrusion) |
| G1 | Linear Move | Lineare Bewegung |
| G28 | Auto Home | Automatisches Homing |
| M104 | Set Hotend Temperature | Hotend-Temperatur setzen |
| M109 | Wait for Hotend Temperature | Auf Hotend-Temperatur warten |
| M140 | Set Bed Temperature | Bett-Temperatur setzen |

---

## 3. Tipps

### Schnellere manuelle Übersetzung:
1. **Bulk-Edit in VS Code:**
   - Suche: `"de": ""`
   - Ersetze mit DeepL/Google Translate
   
2. **JSON-Editor verwenden:**
   - z.B. https://jsoneditoronline.org/
   - Besserer Überblick

3. **Priorität setzen:**
   - Häufigste Befehle zuerst (G0-G3, G28, M104-109, M140-190)
   - Seltenere später

4. **Konsistenz:**
   - Fachbegriffe einheitlich übersetzen
   - "Hotend" = Hotend (nicht übersetzen)
   - "Bed" = Bett
   - "Extrude" = Extrudieren
   - "Home" = Homing

---

## Status

- ✅ Framework: i18n + desc.de Struktur vorhanden
- ⏳ UI: 19 Strings zu übersetzen
- ⏳ JSON: ~150 Befehle zu übersetzen

**Geschätzte Zeit:** 2-4 Stunden (bei manueller Übersetzung)

---

**Bei Fragen:** Deutsche G-code Referenzen:
- https://marlinfw.org/meta/gcode/
- RepRap Wiki (hat oft deutsche Übersetzungen)
