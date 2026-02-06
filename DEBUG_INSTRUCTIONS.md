# DEBUG INSTRUCTIONS

## ⚠️ WICHTIG: comment-reply.min.js Warnungen IGNORIEREN

Die Warnungen von `comment-reply.min.js` sind **NICHT** von unserem Plugin!  
Das ist WordPress Core Code. **Diese Warnungen sind harmlos.**

---

## 🔍 ECHTE FEHLER FINDEN

### 1. Browser Console öffnen
- Chrome/Edge: `F12` oder `Strg+Shift+I`
- Firefox: `F12`
- Tab: **Console**

### 2. Filter setzen
- Nur **Errors** anzeigen (nicht Warnings/Info)
- Nach `gcode`, `gref` oder `GCodeRefConfig` suchen

### 3. Checken Sie folgende Fehler:

#### A) JavaScript lädt nicht
```
Failed to load resource: app.min.js
ERROR: Cannot find module
```

#### B) Config fehlt
```
GCodeRefConfig is not defined
jsonUrl missing
```

#### C) JSON lädt nicht
```
Failed to load JSON from: ...
404 Not Found: marlin-commands.json
```

#### D) DOM-Element fehlt
```
Cannot read property 'querySelector' of null
getElementById("gref-root") is null
```

---

## 🧪 QUICK TEST

### Test 1: JavaScript geladen?
Öffnen Sie die Console und tippen:
```javascript
typeof GCodeRefConfig
```
**Erwartete Ausgabe:** `"object"`  
Falls `"undefined"` → JavaScript wird nicht geladen

### Test 2: JSON URL korrekt?
```javascript
console.log(GCodeRefConfig.jsonUrl)
```
**Erwartete Ausgabe:** URL zu `marlin-commands.json`

### Test 3: DOM Element existiert?
```javascript
document.getElementById('gref-root')
```
**Erwartete Ausgabe:** `<div id="gref-root">...</div>`  
Falls `null` → HTML-Struktur fehlt

### Test 4: Fuse.js geladen?
```javascript
typeof Fuse
```
**Erwartete Ausgabe:** `"function"`  
Falls `"undefined"` → Fuse.js fehlt

---

## 📋 SENDEN SIE MIR:

1. **Echte Error-Meldungen** (nicht Warnings!)
2. **Test-Ergebnisse** von oben
3. **Screenshot** der Console (nur Errors)

Dann kann ich das Problem genau identifizieren! 🔧
