<?php
/**
 * Plugin Name: G-code Reference (JSON)
 * Description: Interactive G-code reference with split TOC, search, copy, explain. JSON-driven. DE/EN UI toggle.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) exit;

class GCode_Reference_JSON {
  const OPT_KEY = 'gcode_reference_settings';
  const SHORTCODE = 'gcode_reference';

  const HANDLE_CSS = 'gcode-reference-app';
  const HANDLE_FUSE = 'gcode-reference-fuse';
  const HANDLE_JS  = 'gcode-reference-js';

  public function __construct() {
    add_action('wp_enqueue_scripts', [$this, 'register_assets']);
    add_action('rest_api_init', [$this, 'register_rest_routes']);
    add_shortcode(self::SHORTCODE, [$this, 'shortcode']);
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'action_links']);

    if (is_admin()) {
      require_once __DIR__ . '/admin/settings.php';
      new GCode_Reference_JSON_Settings(self::OPT_KEY);
    }
  }

  public function register_assets() {
    $base = plugin_dir_url(__FILE__);

    wp_register_style(
      self::HANDLE_CSS,
      $base . 'assets/app.css',
      [],
      '1.0.0'
    );

    wp_register_script(
      self::HANDLE_FUSE,
      $base . 'assets/fuse.min.js',
      [],
      '7.0.0',
      true
    );

    wp_register_script(
      self::HANDLE_JS,
      $base . 'assets/app.js',
      [self::HANDLE_FUSE],
      '1.0.0',
      true
    );
  }

  public function action_links($links) {
    $url = admin_url('options-general.php?page=gcode-reference');
    array_unshift($links, '<a href="' . esc_url($url) . '">Settings</a>');
    return $links;
  }

  private function get_settings() {
    $defaults = [
      'uploaded_json_url' => '',
      'use_uploaded' => 0,
    ];
    $opt = get_option(self::OPT_KEY, []);
    if (!is_array($opt)) $opt = [];
    return array_merge($defaults, $opt);
  }

  private function get_default_json_url() {
    return plugin_dir_url(__FILE__) . 'data/commands.json';
  }

  private function get_rest_json_url() {
    return rest_url('gcode-reference/v1/commands');
  }

  private function get_default_json_path() {
    return plugin_dir_path(__FILE__) . 'data/commands.json';
  }

  private function get_uploaded_json_path() {
    $u = wp_upload_dir();
    return trailingslashit($u['basedir']) . 'gcode-reference/commands.json';
  }

  private function get_effective_json_path() {
    $s = $this->get_settings();
    if (!empty($s['use_uploaded'])) {
      $uploaded = $this->get_uploaded_json_path();
      if (is_readable($uploaded)) return $uploaded;
    }
    $default = $this->get_default_json_path();
    return is_readable($default) ? $default : '';
  }

  private function get_effective_json_url() {
    $s = $this->get_settings();
    if (!empty($s['use_uploaded']) && !empty($s['uploaded_json_url'])) {
      return esc_url_raw($s['uploaded_json_url']);
    }
    return $this->get_default_json_url();
  }

  public function register_rest_routes() {
    register_rest_route('gcode-reference/v1', '/commands', [
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'rest_commands'],
      'permission_callback' => '__return_true',
    ]);
  }

  public function rest_commands($request) {
    $path = $this->get_effective_json_path();
    if (!$path || !is_readable($path)) {
      return new WP_Error('gcode_reference_missing', 'commands.json not found', ['status' => 404]);
    }

    $raw = file_get_contents($path);
    if ($raw === false || $raw === '') {
      return new WP_Error('gcode_reference_read', 'Failed to read commands.json', ['status' => 500]);
    }

    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return new WP_Error('gcode_reference_invalid', 'Invalid JSON: ' . json_last_error_msg(), ['status' => 500]);
    }

    return rest_ensure_response($data);
  }

  public function shortcode($atts = []) {
    $atts = shortcode_atts([
      'height' => '100svh',
      // Optional per-page override: [gcode_reference json_url="..."]
      'json_url' => '',
    ], $atts, self::SHORTCODE);

    wp_enqueue_style(self::HANDLE_CSS);
    wp_enqueue_script(self::HANDLE_JS);

    $jsonUrl = !empty($atts['json_url']) ? esc_url_raw($atts['json_url']) : $this->get_effective_json_url();
    $fallbackJsonUrl = empty($atts['json_url']) ? $this->get_rest_json_url() : '';

    // Pass only URLs + UI config. Command content stays in JSON file.
    $config = [
      'jsonUrl' => $jsonUrl,
      'fallbackJsonUrl' => $fallbackJsonUrl,
      'ui' => [
        'defaultLang' => 'de',
        'enableLangToggle' => true,
      ],
      'height' => $atts['height'],
    ];

    wp_add_inline_script(self::HANDLE_JS, 'window.GCodeRefConfig=' . wp_json_encode($config) . ';', 'before');

    ob_start(); ?>
      <div class="gref" id="gref-root" style="--gref-height: <?php echo esc_attr($atts['height']); ?>;">
        <!-- Mobile top bar -->
        <div class="gref__mobileTop">
          <button class="gref__tocBtn" type="button" aria-haspopup="dialog" aria-controls="gref-drawer" aria-expanded="false" data-i18n="commands">
            Commands
          </button>

          <div class="gref__mobileTitle">G-code Reference</div>

          <div class="gref__lang">
            <button class="gref__langBtn" type="button" data-lang="de">DE</button>
            <button class="gref__langBtn" type="button" data-lang="en">EN</button>
          </div>
        </div>

        <div class="gref__shell">
          <!-- Left TOC -->
          <aside class="gref__toc" aria-label="Command table of contents">
            <div class="gref__tocTop">
              <div class="gref__tocTitle">Commands</div>
              <div class="gref__lang">
                <button class="gref__langBtn" type="button" data-lang="de">DE</button>
                <button class="gref__langBtn" type="button" data-lang="en">EN</button>
              </div>
            </div>
            <div class="gref__tocList" id="gref-toc"></div>
          </aside>

          <!-- Right pane (only this scrolls) -->
          <section class="gref__pane" aria-label="Command details">
            <div class="gref__top">
              <label class="gref__label" for="gref-search" data-i18n="searchLabel">Search</label>
              <div class="gref__searchRow">
                <input id="gref-search" class="gref__search" type="search" autocomplete="off" />
                <button class="gref__clear" type="button" aria-label="Clear search">×</button>
              </div>
              <div class="gref__hint" data-i18n="hint">
                Tip: Click a code line → explanation (right). Press Enter to jump.
              </div>
            </div>

            <div class="gref__resultsWrap">
              <div class="gref__results" id="gref-results"></div>
            </div>
          </section>

          <aside class="gref__panel" aria-live="polite">
            <div class="gref__panelTitle" data-i18n="explainTitle">Explanation</div>
            <div class="gref__panelBody" id="gref-explain" data-i18n="explainEmpty">
              Click a code line to see what it does.
            </div>
          </aside>
        </div>

        <!-- Mobile drawer -->
        <div class="gref__drawerBackdrop" id="gref-backdrop" hidden></div>
        <div class="gref__drawer" id="gref-drawer" role="dialog" aria-modal="true" aria-label="Commands drawer" hidden>
          <div class="gref__drawerTop">
            <div class="gref__drawerTitle">Commands</div>
            <button class="gref__drawerClose" type="button" aria-label="Close">×</button>
          </div>
          <div class="gref__drawerList" id="gref-toc-mobile"></div>
        </div>
        <div class="gref__promo">
        <div class="gref__promoTitle" data-i18n="promoTitle">Need help with Marlin / G-code?</div>
        <div class="gref__promoText" data-i18n="promoText">
             If you’re stuck with firmware, start/end G-code, calibration, or troubleshooting: we can help quickly and practically.
         </div>
         <a class="gref__promoBtn" data-i18n="promoCta" href="https://www.partner-3d.de/kontakt/" rel="nofollow">
              Contact 3D Partner
         </a>
        </div>
        <div class="gref__status" id="gref-status" aria-live="polite"></div>
      </div>
    <?php
    return ob_get_clean();
  }
}

new GCode_Reference_JSON();
