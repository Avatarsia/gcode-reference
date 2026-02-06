# Umsets

ungsvorschläge - G-Code Reference Plugin

## 🎯 Wie können wir auf dieser Basis weiter arbeiten?

Basierend auf der detaillierten [Analyse](./analysis.md) gibt es **mehrere Wege**, wie wir das Plugin verbessern können:

---

## Option 1: 🔴 Kritische Fixes (Minimal Viable Product)

**Zeitaufwand**: ~12-15 Stunden  
**Komplexität**: Mittel  
**Nutzen**: Plugin wird production-ready für 3D-Druck

### Aufgaben
1. **Internationalisierung** (4-6h)
   - Text Domain implementieren
   - Alle Strings mit `__()` / `_e()` übersetzen
   - POT-Datei generieren
   
2. **3D-Druck Fokus** (2-3h)
   - CNC-Befehle entfernen (G17, G18, G19)
   - Laser-Parameter aus Bewegungsbefehlen entfernen
   - JSON um ~10% reduzieren
   
3. **Deutsche Übersetzungen vervollständigen** (6-8h)
   - Alle leeren `desc.de` Felder füllen
   - Parameter-Beschreibungen übersetzen

### Resultat
✅ Plugin ist marktreif  
✅ Fokus auf 3D-Druck klar  
✅ Vollständig zweisprachig (DE/EN)  

---

## Option 2: 🟢 WordPress.org Release (Complete Package)

**Zeitaufwand**: ~25-30 Stunden  
**Komplexität**: Mittel-Hoch  
**Nutzen**: Plugin bereit für WordPress.org Verzeichnis

### Aufgaben (+ Option 1)
4. **readme.txt erstellen** (2-3h)
   - Plugin-Beschreibung, Features, Screenshots
   - Installation, FAQ, Changelog
   
5. **PHPDoc Dokumentation** (3-4h)
   - Alle Funktionen dokumentieren
   - @param, @return, @since Tags
   
6. **Performance Optimierung** (2-3h)
   - JSON Transients Caching implementieren
   - Assets minifizieren (Gulp/Webpack)
   
7. **Screenshots & Visuals** (2h)
   - 3-4 Screenshots erstellen
   - Banner-Grafik (1544×500px)
   - Icon (256×256px)

8. **Testing & QA** (4-6h)
   - Cross-Browser Tests
   - WordPress 6.x Kompatibilitätstest
   - PHP 7.4 / 8.0 / 8.1 Tests

### Resultat
✅ WordPress.org Submission ready  
✅ Professional documentation  
✅ Performance optimiert  
✅ Production-grade quality  

---

## Option 3: 🚀 Feature-Erweiterung (Deluxe Edition)

**Zeitaufwand**: ~40-50 Stunden  
**Komplexität**: Hoch  
**Nutzen**: Best-in-class G-Code Referenz mit Alleinstellungsmerkmalen

### Aufgaben (+ Option 2)
9. **Marlin Versions-Unterstützung** (6-8h)
   - JSON erweitern um `marlinVersion` Felder
   - Filter nach Firmware-Version
   - Legacy-Befehle markieren
   
10. **Advanced Features** (8-12h)
    - Favoriten-System (LocalStorage + optional Backend)
    - Command History
    - Dark Mode
    - Export (PDF/Markdown)
    
11. **SEO Optimization** (4-6h)
    - Individual Permalinks pro G-Code
    - Strukturierte Daten (Schema.org)
    - Breadcrumbs, Rich Snippets
    
12. **Community Features** (6-8h)
    - GitHub Integration für Issue/PR
    - JSON-Validator-Tool (öffentlich)
    - User-Kommentare pro Befehl (optional)

13. **Multi-Firmware Support** (8-10h)
    - Klipper JSON-Set
    - RepRapFirmware JSON-Set
    - Firmware-Switcher im UI

### Resultat
✅ Marktführer in G-Code Referenzen  
✅ Community-getrieben  
✅ Multi-Firmware Support  
✅ SEO-optimiert für Traffic  

---

## 💼 Empfohlener Approach

Ich empfehle einen **iterativen Ansatz**:

### Phase 1: Basis (2-3 Wochen)
→ **Option 1** umsetzen  
→ Erste Version 1.1.0 für Eigennutzung

### Phase 2: Release (3-4 Wochen)  
→ **Option 2** umsetzen  
→ WordPress.org Submission  
→ Version 2.0.0

### Phase 3: Features (4-6 Wochen, optional)
→ **Option 3** Features nach Priorität  
→ Community Feedback einbeziehen  
→ Version 2.x Updates

---

## 🛠️ Konkrete nächste Schritte

### Sofort starten (heute):

1. **i18n Grundgerüst** (1h)
   ```php
   // gcode-reference.php - Constructor erweitern
   add_action('plugins_loaded', [$this, 'load_textdomain']);
   
   public function load_textdomain() {
       load_plugin_textdomain('gcode-reference', false, 
           dirname(plugin_basename(__FILE__)) . '/languages');
   }
   ```

2. **CNC G-Codes entfernen** (30min)
   - `data/commands.json` öffnen
   - G17, G18, G19 Einträge löschen
   - JSON validieren

3. **Laser-Parameter entfernen** (1h)
   - In G0, G1, G2, G3, G5
   - Parameter `S` mit "Laser" Referenz entfernen

### Diese Woche:

4. **Alle PHP Strings übersetzen** (3-4h)
   - `gcode-reference.php`: 15-20 Strings
   - `admin/settings.php`: 20-25 Strings

5. **Deutsche JSON Übersetzungen** (4-6h)
   - Tool schreiben zum Batch-Update
   - Fehlende `desc.de` Felder füllen

### Nächste Woche:

6. **readme.txt Template** (2h)
   - Von WP.org Beispielen abgeleitet
   - Grundstruktur

7. **Screenshots erstellen** (1-2h)
   - Desktop: Split-View
   - Mobile: Drawer
   - Search-Funktion
   - Explain-Feature

---

## 📋 Checkliste für Version 2.0

```markdown
- [ ] Internationalisierung vollständig
- [ ] Nur 3D-Druck G-Codes (keine CNC/Laser)
- [ ] Deutsche Übersetzungen 100%
- [ ] readme.txt vorhanden
- [ ] Screenshots (min. 3)
- [ ] PHPDoc Kommentare
- [ ] Performance: Transients Caching
- [ ] Assets minifiziert
- [ ] LICENSE Datei (GPL v2+)
- [ ] Tested bis WordPress 6.7
- [ ] PHP 7.4 - 8.3 kompatibel
- [ ] Cross-Browser getestet
- [ ] PHPCS WordPress Standards ✓
- [ ] Zero Errors/Warnings
```

---

## ❓ Fragen zur Klärung

Bevor wir starten, sollten wir klären:

1. **Zielgruppe**: 
   - Nur Marlin oder auch Klipper/RepRap?
   - Anfänger oder auch Profis?

2. **Deployment**:
   - WordPress.org Repository?
   - Eigene Website only?
   - GitHub Releases?

3. **Support**:
   - Community-Support (Forum)?
   - E-Mail Support?
   - Kein Support?

4. **Monetarisierung** (optional):
   - Kostenlos Open Source?
   - Freemium (Pro Version)?
   - Nur Support kostenpflichtig?

5. **Zeitplan**:
   - Quick Fix (Option 1) in 2 Wochen?
   - Full Release (Option 2) in 6-8 Wochen?
   - Langfristiges Projekt (Option 3)?

---

## 🎬 Bereit loszulegen?

Ich bin **zu 95% sicher**, dass wir dieses Projekt erfolgreich umsetzen können! 

Die technische Basis ist **solid**, wir müssen nur:
- ✅ Fokus schärfen (3D-Druck only)
- ✅ Internationalisierung nachrüsten
- ✅ Dokumentation vervollständigen

**Nächste Frage**: Welche Option möchten Sie verfolgen?  
→ Option 1 (Quick Fix)?  
→ Option 2 (WP.org Release)?  
→ Option 3 (Feature-reich)?

Oder sollen wir mit den "Sofort starten" Tasks beginnen? 🚀

---

_Siehe auch: [analysis.md](./analysis.md) für die vollständige technische Analyse._
