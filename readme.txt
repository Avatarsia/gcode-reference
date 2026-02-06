=== G-code Reference ===
Contributors: (your-username)
Tags: gcode, 3d-printing, marlin, reference, documentation
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Interactive G-code reference for 3D printing with fuzzy search, split view, and multilingual support (DE/EN).

== Description ==

G-code Reference provides an interactive, searchable reference for G-code commands used in 3D printing. Perfect for makers, developers, and anyone working with 3D printers using Marlin firmware.

**Key Features:**

* **254+ G-code commands** specifically curated for 3D printing
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

* Marlin (included)
* Klipper (upload custom JSON)
* RepRapFirmware (upload custom JSON)

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/gcode-reference/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the shortcode `[gcode_reference]` to any page or post
4. (Optional) Upload a custom JSON file in Settings → G-code Reference

== Frequently Asked Questions ==

= How do I use the shortcode? =

Simply add `[gcode_reference]` to any page or post. You can optionally specify a custom JSON URL:

`[gcode_reference json_url="https://example.com/custom-commands.json"]`

= Can I use my own G-code data? =

Yes! Go to Settings → G-code Reference and upload your own JSON file. The required format is documented in the plugin's data folder.

= Which firmwares are supported? =

The plugin includes Marlin G-codes by default. You can upload custom JSON files for Klipper, RepRapFirmware, or any other firmware.

= How do I switch between languages? =

The language toggle is visible in the top-right corner of the reference. Click the flag icon to switch between German (DE) and English (EN).

= Does this work with custom themes? =

Yes, the plugin is theme-independent and uses its own styling. It works with any WordPress theme.

= Can I use this commercially? =

Yes! The plugin is licensed under GPL v2+, which allows commercial use.

== Screenshots ==

1. Split-view interface with Table of Contents and command details
2. Mobile responsive design with drawer navigation
3. Fuzzy search finding commands instantly
4. Admin settings page for custom JSON upload
5. Language switcher (DE/EN)

== Changelog ==

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

* `json_url` - Override JSON source URL
* `height` - Container height (default: 100svh)

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
