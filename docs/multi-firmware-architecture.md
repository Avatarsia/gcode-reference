# Multi-Firmware Architektur - Marlin & Klipper

**Ziel**: Separate Implementierung für Marlin und Klipper G-Code Referenzen auf verschiedenen Seiten

---

## 🎯 Anforderungen

1. ✅ **Klare Trennung**: Marlin ≠ Klipper (keine Vermischung)
2. ✅ **Separate Seiten**: Unterschiedliche WordPress Pages
3. ✅ **Eigene JSON-Dateien**: `marlin-commands.json` + `klipper-commands.json`
4. ✅ **Unabhängige Verwaltung**: Separate Upload/Settings pro Firmware
5. ✅ **Skalierbar**: Später RepRapFirmware, etc. hinzufügbar

---

## 🏗️ Vorgeschlagene Architektur

### Option A: Named Sources (Empfohlen)

**Backend - Settings erweitern**:
```
G-code Reference Settings
├── Marlin Source
│   ├── [✓] Use Custom JSON
│   ├── Upload: marlin-commands.json
│   └── URL: .../uploads/gcode-reference/marlin-commands.json
│
└── Klipper Source
    ├── [✓] Use Custom JSON
    ├── Upload: klipper-commands.json
    └── URL: .../uploads/gcode-reference/klipper-commands.json
```

**Shortcode - Einfache Nutzung**:
```php
// Seite: /marlin-gcode-reference/
[gcode_reference source="marlin"]

// Seite: /klipper-gcode-reference/  
[gcode_reference source="klipper"]

// Fallback (alter Shortcode bleibt kompatibel)
[gcode_reference json_url="https://..."]
```

**Vorteile**:
- ✅ Sauber, benutzerfreundlich
- ✅ Zentrale Verwaltung im Admin
- ✅ Abwärtskompatibel
- ✅ Leicht erweiterbar

---

### Option B: Per-Page Override (Aktuell möglich)

**Nutzung**:
```php
// Seite: /marlin-gcode-reference/
[gcode_reference json_url="https://yoursite.com/wp-content/uploads/gcode-reference/marlin.json"]

// Seite: /klipper-gcode-reference/
[gcode_reference json_url="https://yoursite.com/wp-content/uploads/gcode-reference/klipper.json"]
```

**Status**: ✅ Funktioniert bereits JETZT!

**Nachteile**:
- ⚠️ Manuelle URL-Verwaltung pro Seite
- ⚠️ Keine zentrale Übersicht
- ⚠️ Fehleranfällig bei URL-Änderungen

---

## 📋 Implementierungsplan - Option A

### Phase 1: Backend Settings (3-4h)

**Datei**: `admin/settings.php`

**Änderungen**:

1. **Settings-Struktur erweitern**:
```php
$defaults = [
    'sources' => [
        'marlin' => [
            'enabled' => 1,
            'use_uploaded' => 0,
            'uploaded_json_url' => '',
            'label' => 'Marlin Firmware',
        ],
        'klipper' => [
            'enabled' => 0,
            'use_uploaded' => 0,
            'uploaded_json_url' => '',
            'label' => 'Klipper Firmware',
        ],
    ],
];
```

2. **UI mit Tabs/Accordions**:
```html
<div class="gcode-sources-tabs">
    <h2 class="nav-tab-wrapper">
        <a href="#marlin" class="nav-tab nav-tab-active">Marlin</a>
        <a href="#klipper" class="nav-tab">Klipper</a>
    </h2>
    
    <div id="marlin" class="source-panel">
        <!-- Upload Form für Marlin -->
    </div>
    
    <div id="klipper" class="source-panel" style="display:none;">
        <!-- Upload Form für Klipper -->
    </div>
</div>
```

3. **Upload Handler pro Source**:
```php
public function handle_upload() {
    $source = sanitize_key($_POST['source']); // 'marlin' oder 'klipper'
    
    // Speichern als {source}-commands.json
    $filename = $source . '-commands.json';
    $target = $this->uploads_dir_path() . $filename;
    
    // ... validation & save ...
}
```

---

### Phase 2: Shortcode Enhancement (2h)

**Datei**: `gcode-reference.php`

**Änderungen**:

1. **Shortcode Attribute erweitern**:
```php
public function shortcode($atts = []) {
    $atts = shortcode_atts([
        'height' => '100svh',
        'json_url' => '',        // Legacy: direkter URL Override
        'source' => 'marlin',    // NEU: Named Source ('marlin'|'klipper')
    ], $atts, self::SHORTCODE);
    
    // Logik
    if (!empty($atts['json_url'])) {
        // Legacy: Direkter URL (höchste Priorität)
        $jsonUrl = esc_url_raw($atts['json_url']);
    } else {
        // NEU: Named Source
        $jsonUrl = $this->get_source_json_url($atts['source']);
    }
    
    // ... rest ...
}
```

2. **Helper Funktion**:
```php
private function get_source_json_url($source) {
    $s = $this->get_settings();
    $source = sanitize_key($source);
    
    if (!isset($s['sources'][$source])) {
        return $this->get_default_json_url(); // Fallback
    }
    
    $cfg = $s['sources'][$source];
    
    // Custom JSON oder Default?
    if (!empty($cfg['use_uploaded']) && !empty($cfg['uploaded_json_url'])) {
        return esc_url_raw($cfg['uploaded_json_url']);
    }
    
    // Default JSON im plugin/data/{source}-commands.json
    return plugin_dir_url(__FILE__) . "data/{$source}-commands.json";
}
```

---

### Phase 3: Default JSON Files (1-2h)

**Struktur**:
```
data/
├── marlin-commands.json      (aktuelle commands.json umbenennen)
├── klipper-commands.json     (neu erstellen)
└── commands.json             (Alias zu marlin - Abwärtskompatibilität)
```

**Klipper JSON erstellen**:
- Neue Datei basierend auf Klipper Dokumentation
- Andere G-Code Struktur (z.B. `SET_HEATER_TEMPERATURE`)
- Meta-Block anpassen:
```json
{
  "meta": {
    "id": "klipper",
    "title": {
      "de": "Klipper G-code",
      "en": "Klipper G-code"
    },
    "defaultLanguage": "de"
  },
  "commands": [...]
}
```

---

### Phase 4: REST API Adjustment (1h)

**Datei**: `gcode-reference.php`

**REST Endpoint erweitern** (optional für dynamisches Laden):
```php
register_rest_route('gcode-reference/v1', '/commands/(?P<source>[a-z]+)', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => [$this, 'rest_commands_source'],
    'permission_callback' => '__return_true',
    'args' => [
        'source' => [
            'required' => true,
            'validate_callback' => function($param) {
                return in_array($param, ['marlin', 'klipper'], true);
            }
        ]
    ]
]);
```

---

## 🎨 Frontend - Firmware Identifikation

**Optional**: Visuell kennzeichnen welche Firmware

### CSS Anpassung:
```css
/* Marlin Theme */
.gref[data-firmware="marlin"] {
    --primary-color: #e63946;  /* Rot */
}

/* Klipper Theme */
.gref[data-firmware="klipper"] {
    --primary-color: #06d6a0;  /* Grün */
}
```

### HTML:
```php
<div class="gref" id="gref-root" data-firmware="<?php echo esc_attr($source); ?>">
    <div class="gref__firmware-badge"><?php echo esc_html($sourceLabel); ?></div>
    <!-- ... rest ... -->
</div>
```

---

## 📊 Dateistruktur (nach Implementierung)

```
gcode-reference/
├── gcode-reference.php          [MODIFIZIERT]
├── admin/
│   └── settings.php             [MODIFIZIERT - Tabs/Multi-Source]
├── assets/
│   ├── app.css                  [OPTIONAL - Firmware Theming]
│   ├── app.js                   [Unverändert]
│   └── fuse.min.js
├── data/
│   ├── marlin-commands.json     [UMBENANNT von commands.json]
│   ├── klipper-commands.json    [NEU]
│   └── commands.json            [SYMLINK zu marlin - Legacy]
└── docs/
    └── multi-firmware-architecture.md
```

---

## 🔄 Migration Path (Bestehende Installation)

### Automatische Migration beim Plugin-Update:

```php
// In main class constructor:
add_action('plugins_loaded', [$this, 'maybe_migrate_settings']);

public function maybe_migrate_settings() {
    $version = get_option('gcode_reference_version', '1.0.0');
    
    if (version_compare($version, '2.0.0', '<')) {
        $this->migrate_to_multi_source();
        update_option('gcode_reference_version', '2.0.0');
    }
}

private function migrate_to_multi_source() {
    $old = get_option('gcode_reference_settings', []);
    
    // Alte Settings in Marlin Source umwandeln
    $new = [
        'sources' => [
            'marlin' => [
                'enabled' => 1,
                'use_uploaded' => $old['use_uploaded'] ?? 0,
                'uploaded_json_url' => $old['uploaded_json_url'] ?? '',
                'label' => 'Marlin Firmware',
            ],
            'klipper' => [
                'enabled' => 0,
                'use_uploaded' => 0,
                'uploaded_json_url' => '',
                'label' => 'Klipper Firmware',
            ],
        ],
    ];
    
    update_option('gcode_reference_settings', $new);
}
```

---

## ✅ Vorteile dieser Architektur

1. **Klare Trennung**: Marlin und Klipper komplett getrennt
2. **Benutzerfreundlich**: Ein Click-Upload pro Firmware
3. **Erweiterbar**: Weitere Firmwares einfach hinzufügbar
4. **Abwärtskompatibel**: Alte Shortcodes funktionieren weiter
5. **Zentral verwaltet**: Alle JSONs an einem Ort
6. **Typsicher**: Validation im Shortcode

---

## 📅 Zeitplan

### Minimal (Option B nutzen - 0h)
✅ **JETZT schon möglich** mit `json_url` Parameter

### Optimal (Option A implementieren)
- **Phase 1**: Backend Settings (3-4h)
- **Phase 2**: Shortcode Enhancement (2h)
- **Phase 3**: Klipper JSON erstellen (6-8h)
- **Phase 4**: REST API (1h)
- **Testing & Migration**: (2h)

**Gesamt**: ~14-17 Stunden

---

## 🎯 Nächste Schritte

### Sofort (mit bestehendem System):

1. **Marlin JSON umbenennen**:
   ```bash
   # Im data/ Ordner
   cp commands.json marlin-commands.json
   ```

2. **Klipper Placeholder erstellen**:
   ```json
   {
     "meta": {"id": "klipper", "title": {"de": "Klipper", "en": "Klipper"}},
     "commands": []
   }
   ```
   Speichern als `klipper-commands.json`, hochladen via Admin

3. **Seiten erstellen**:
   - WordPress Admin → Seiten → Neu
   - Titel: "Marlin G-Code Referenz"
   - Content: `[gcode_reference]` (nutzt default/marlin)
   - Titel: "Klipper G-Code Referenz"  
   - Content: `[gcode_reference json_url="URL_TO_KLIPPER"]`

### Mittelfristig (Option A):

Implementierung der Multi-Source Architektur wie oben beschrieben.

---

## ❓ Offene Fragen

1. **Klipper JSON**: Soll ich eine Klipper G-Code Liste recherchieren und JSON erstellen?
2. **UI Design**: Sollen Marlin/Klipper visuell unterscheidbar sein (Farben/Badge)?
3. **Priorität**: Gleich implementieren oder erst Marlin fertigstellen (Option 1 aus recommendations.md)?

---

_Diese Architektur ermöglicht saubere Trennung mit Raum für Wachstum!_ 🚀
