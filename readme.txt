=== G-code Reference ===
Contributors: (your-username)
Tags: gcode, 3d-printing, marlin, reference, documentation
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.0.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Interactive G-code reference for 3D printing with fuzzy search, split view, and multilingual support (DE/EN).

== Description ==

G-code Reference provides an interactive, searchable reference for G-code commands used in 3D printing. Perfect for makers, developers, and anyone working with 3D printers using Marlin and Klipper firmware.

**Key Features:**

* **304 G-code commands** - 254 Marlin + 50 Klipper commands
* **Multi-firmware support** - Marlin and Klipper included
* **Fuzzy search** - find commands even with typos
* **Split-view interface** - Table of Contents + Details side-by-side
* **Responsive design** - Works on desktop, tablet, and mobile
* **Bilingual** - Switch between German and English instantly
* **Copy to clipboard** - One-click code copying
* **"Explain" function** - Quick command descriptions
* **Custom JSON** - Upload your own G-code reference
* **REST API** - Access data programmatically

**Perfect for:**

* 3D printing enthusiasts and professionals
* Firmware developers
* G-code slicer developers
* Educational purposes
* Technical documentation websites

**Supported Firmwares:**

* Marlin (254 commands included)
* Klipper (50 commands included)
* RepRapFirmware (upload custom JSON)

== Installation ==

1. **IMPORTANT:** If updating from an older version, deactivate and delete the old version first!
2. Upload `gcode-reference-2.0.2.zip` via Plugins → Add New → Upload
3. Activate the plugin
4. Add the shortcode `[gcode_reference]` to any page or post
5. (Optional) Configure in Settings → G-code Reference

== Frequently Asked Questions ==

= How do I use the shortcode? =

Simply add `[gcode_reference]` to any page or post. 

For Klipper firmware:
`[gcode_reference source="klipper"]`

Custom height:
`[gcode_reference height="800px"]`

= Can I use my own G-code data? =

Yes! Go to Settings → G-code Reference and upload your own JSON file. The required format is documented in the plugin's data folder.

= Which firmwares are supported? =

The plugin includes Marlin (254 commands) and Klipper (50 commands) by default. You can upload custom JSON files for RepRapFirmware or any other firmware.

= How do I switch between languages? =

The language toggle is visible in the top-right corner of the reference. Click DE or EN to switch between German and English.

= Does this work with custom themes? =

Yes, the plugin is theme-independent and uses its own styling. It works with any WordPress theme.

= I get a "class already declared" error! =

This happens when an old version is still active. Solution:
1. Deactivate the old version
2. Delete the old plugin folder
3. Install the new version

== Screenshots ==

1. Split-view interface with Table of Contents and command details
2. Mobile responsive design with drawer navigation
3. Fuzzy search finding commands instantly
4. Modern admin dashboard with firmware selection
5. Language switcher (DE/EN)

== Changelog ==

= 2.0.5 - 2026-02-06 =
* **Fixed**: Language bug - regenerated minified JS to respect defaultLang setting
* **Fixed**: Parameter descriptions now display in German when defaultLang is 'de'
* **Improved**: Optimized layout for Full HD (1920px) displays
* **Improved**: Wider columns - TOC: 380px (was 320px), Right panel: 460px (was 360px)
* **Improved**: Better spacing between columns (20px gap instead of 16px)

= 2.0.4 - 2026-02-06 =
* **Critical Fix**: Corrected HTML structure to match CSS expectations
* Fixed: TOC was taking full width (976px) instead of 320px
* Fixed: Layout wasn't rendering as 3-column grid
* Fixed: Added gref__shell wrapper for proper layout container
* Fixed: Corrected all CSS class names to match stylesheet

= 2.0.3 - 2026-02-06 =
* **Critical Fix**: Added timestamp-based cache busting for CSS/JS assets
* Fixed: Browser cache was preventing updated assets from loading
* Note: If you still see issues, clear your browser cache (Ctrl+F5)

= 2.0.2 - 2026-02-06 =
* **Critical Fix**: Added class_exists() check to prevent fatal error when multiple versions are installed
* Improved: Installation instructions now mention deactivating old versions first
* Note: If you see "class already declared" error, deactivate and delete old version first!

= 2.0.1 - 2026-02-06 =
* **Critical Fix**: Shortcode now renders correctly
* Fixed: HTML structure output (was missing gref-root and child elements)
* Fixed: JavaScript config object name (GCodeRefConfig)
* Fixed: Default height changed from 100svh to 600px for better compatibility
* Added: Modern admin dashboard with card-based layout
* Added: Klipper firmware support (50 commands with DE/EN translations)
* Added: Firmware selection in admin UI
* Improved: Shortcode documentation in admin panel

= 2.0.0 - 2026-02-06 =
* **Major Update**: Internationalization support
* **Breaking**: Focused exclusively on 3D printing G-codes (removed CNC/Laser commands)
* Added: Full translation support (DE/EN)
* Added: Text domain and POT file for translators
* Removed: G17, G18, G19 (CNC plane select commands)
* Removed: Laser power parameters from movement commands
* Improved: JSON validation and error handling
* Improved: Admin UI with clearer messaging
* Fixed: PHP 8.x compatibility
* Total commands: 251 (was 254)

= 1.0.0 - 2025-XX-XX =
* Initial release
* 254 G-code commands
* Fuzzy search with Fuse.js
* Split-view responsive UI
* DE/EN language toggle
* REST API endpoint
* Custom JSON upload capability
* Shortcode support

== Upgrade Notice ==

= 2.0.0 =
Major update with internationalization support and 3D printing focus. Removes 3 CNC-specific commands. Full backward compatibility maintained.

== Technical Details ==

**REST API Endpoint:**

`GET /wp-json/gcode-reference/v1/commands`

Returns the complete G-code dataset as JSON.

**JSON Schema:**

```json
{
  "meta": {
    "id": "marlin",
    "title": { "de": "...", "en": "..." },
    "defaultLanguage": "de"
  },
  "commands": [
    {
      "id": "G0",
      "code": "G0",
      "title": { "de": "...", "en": "..." },
      "desc": { "de": "...", "en": "..." },
      "category": "movement",
      "params": [...]
    }
  ]
}
```

**Shortcode Attributes:**

* `source` - Firmware source (marlin, klipper)
* `json_url` - Override JSON source URL
* `height` - Container height (default: 600px)

**Browser Support:**

* Chrome/Edge 90+
* Firefox 88+
* Safari 14+
* Mobile browsers (iOS Safari, Chrome Mobile)

== Privacy ==

This plugin does not:
* Collect any user data
* Use cookies
* Make external API calls
* Track user behavior

Language preference is stored in browser localStorage only.

== Support ==

For bug reports and feature requests, please use the WordPress.org support forum or visit the plugin's GitHub repository.

== Translations ==

The plugin is translation-ready. Translations available:

* English (en_US) - Built-in
* German (de_DE) - Built-in

To translate to your language, use the provided .pot file or contribute via translate.wordpress.org.
