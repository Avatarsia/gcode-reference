# Klipper G-Code Referenz - TODO

## Status
🟡 **Platzhalter erstellt** - Vollständige Implementierung folgt später

## Aktuelle Datei
`data/klipper-commands.json` - Enthält nur 5 Basic G-Codes als Beispiel

## Was fehlt noch

### Standard G-Codes
- [ ] G2/G3 - Arc moves
- [ ] G4 - Dwell
- [ ] G20/G21 - Units
- [ ] G90/G91 - Absolute/Relative positioning
- [ ] G92 - Set Position
- [ ] Weitere Standard-G-Codes

### Standard M-Codes
- [ ] M106/M107 - Fan control
- [ ] M109 - Wait for hotend temperature
- [ ] M190 - Wait for bed temperature
- [ ] M112 - Emergency stop
- [ ] M114 - Get position
- [ ] M220 - Set speed factor
- [ ] M221 - Set flow rate
- [ ] Weitere Standard-M-Codes

### Klipper-Spezifische Extended Commands
- [ ] **BED_MESH_CALIBRATE** - Bett-Mesh kalibrieren
- [ ] **BED_MESH_PROFILE** - Mesh-Profile verwalten
- [ ] **SET_HEATER_TEMPERATURE** - Heizer-Temperatur setzen
- [ ] **SET_FAN_SPEED** - Lüftergeschwindigkeit setzen
- [ ] **SET_PRESSURE_ADVANCE** - Pressure Advance einstellen
- [ ] **SET_VELOCITY_LIMIT** - Geschwindigkeitslimits
- [ ] **PID_CALIBRATE** - PID-Kalibrierung
- [ ] **STEPPER_BUZZ** - Stepper-Test
- [ ] **QUERY_ENDSTOPS** - Endstop-Status
- [ ] **QUERY_PROBE** - Probe-Status
- [ ] **Z_OFFSET_APPLY_PROBE** - Z-Offset anwenden
- [ ] **FIRMWARE_RESTART** - Firmware neu starten
- [ ] **RESTART** - Klipper neu starten
- [ ] **STATUS** - Status abfragen
- [ ] **SAVE_CONFIG** - Konfiguration speichern
- [ ] **TUNING_TOWER** - Tuning Tower
- [ ] **SET_GCODE_OFFSET** - G-Code Offset
- [ ] **SET_RETRACTION** - Retraktion einstellen
- [ ] Weitere Extended Commands

## Recherche-Quellen

1. **Offizielle Klipper Dokumentation**:
   - https://www.klipper3d.org/G-Codes.html
   - https://www.klipper3d.org/Command_Templates.html

2. **G-Code Reference**:
   - Marlin als Basis (viele G-Codes sind kompatibel)
   - Klipper-spezifische Erweiterungen dokumentieren

3. **Community Resources**:
   - Klipper GitHub Issues/Discussions
   - Klipper Discourse Forum

## Struktur-Anforderungen

Alle Befehle müssen folgende Struktur haben:
```json
{
  "id": "COMMAND_ID",
  "code": "COMMAND",
  "title": {
    "de": "Deutscher Titel",
    "en": "English Title"
  },
  "desc": {
    "de": "Deutsche Beschreibung",
    "en": "English Description"
  },
  "category": "movement|temperature|calibration|other",
  "aliases": ["lowercase_alias"],
  "requires": ["KLIPPER_FEATURE"],
  "examples": [
    {
      "code": "COMMAND PARAM1 PARAM2",
      "note": {
        "de": "Deutsche Erklärung",
        "en": "English Explanation"
      }
    }
  ],
  "params": [
    {
      "key": "PARAM",
      "type": "number|string|bool",
      "desc": {
        "de": "Parameter-Beschreibung DE",
        "en": "Parameter Description EN"
      }
    }
  ],
  "usage": ""
}
```

## Priorität

**Phase 1** (Wichtig):
- Standard G-Codes (G0-G92)
- Standard M-Codes (M0-M999)

**Phase 2** (Klipper-Features):
- Extended Commands (SET_*, CALIBRATE_*, etc.)
- Makros & Templates

**Phase 3** (Optional):
- Klipper-spezifische Features (Druck-Tuning)
- Advanced Commands

## Zeitschätzung

- **Phase 1**: ~8-12 Stunden (Recherche + Übersetzung)
- **Phase 2**: ~6-10 Stunden (Extended Commands)
- **Phase 3**: ~4-6 Stunden (Advanced Features)

**Gesamt**: ~18-28 Stunden für vollständige Klipper-Referenz

---

_Erstellt: 2026-02-06_  
_Status: Platzhalter aktiv, Vollständige Implementierung ausstehend_
