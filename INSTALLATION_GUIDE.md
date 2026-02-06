# ⚠️ INSTALLATION ANLEITUNG - BITTE GENAU BEFOLGEN!

## 🚨 **PROBLEM:**
Sie haben wahrscheinlich noch eine **alte Version** des Plugins installiert!

Das führt zu:
- ❌ Kein Settings-Menü
- ❌ Keine Admin-Seite
- ❌ Plugin lädt nicht richtig

---

## ✅ **LÖSUNG - SCHRITT FÜR SCHRITT:**

### **Schritt 1: Aufräumen**

1. Gehen Sie zu **WordPress Admin → Plugins**
2. Suchen Sie nach **ALLEN** Versionen von "G-code Reference"
3. **DEAKTIVIEREN** Sie jede Version
4. **LÖSCHEN** Sie jede Version (Button "Löschen")

**WICHTIG:** Wirklich ALLE Versionen löschen!

---

### **Schritt 2: Ordner prüfen**

Via FTP/cPanel prüfen:
```
/wp-content/plugins/
```

Löschen Sie ALLE Ordner wie:
-`gcode-reference/`
- `gcode-reference-2.0.1/`
- `gcode-reference-2.0.2/`
- etc.

---

### **Schritt 3: Neue Version installieren**

1. Gehen Sie zu **Plugins → Installieren → Plugin hochladen**
2. Wählen Sie: `gcode-reference-2.0.3.zip`
3. Klicken Sie **Jetzt installieren**
4. Klicken Sie **Plugin aktivieren**

---

### **Schritt 4: Testen**

Nach Aktivierung sollten Sie sehen:

✅ **In der Plugin-Liste:**
- "Einstellungen" Link unter dem Plugin-Namen

✅ **Im WordPress-Menü:**
- Einstellungen → G-code Reference

✅ **Admin-Seite:**
- Modernes Dashboard mit Karten
- Firmware-Auswahl
- Shortcode-Beispiele

---

## 🧪 **Schnelltest:**

Erstellen Sie eine Testseite mit:
```
[gcode_reference]
```

Erwartetes Ergebnis:
- ✅ Interaktive G-code Referenz
- ✅ Suchfeld oben
- ✅ G-code Liste links
- ✅ DE/EN Buttons

---

## 🆘 **Falls es IMMER NOCH nicht funktioniert:**

Senden Sie mir bitte:
1. **Screenshot** der Plugin-Liste
2. **Screenshot** der Browser Console (F12 → Console Tab)
3. **Welche** WordPress Version nutzen Sie?
4. **Welches** Theme ist aktiv?

Dann finde ich das Problem! 🔧
