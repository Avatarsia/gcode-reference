<?php

/**
 * Plugin Name: G-code Reference (JSON)
 * Description: Interactive G-code reference with split TOC, search, copy, explain. JSON-driven. DE/EN UI toggle.
 * Version: 2.0.6
 */

if (!defined('ABSPATH')) exit;

class GCode_Reference_JSON
{
  const OPT_KEY = 'gcode_reference_settings';
  const SHORTCODE = 'gcode_reference';
  const TEXT_DOMAIN = 'gcode-reference';

  const HANDLE_CSS = 'gcode-reference-app';
  const HANDLE_FUSE = 'gcode-reference-fuse';
  const HANDLE_JS  = 'gcode-reference-js';

  public function __construct()
  {
    add_action('plugins_loaded', [$this, 'load_textdomain']);
    add_action('wp_enqueue_scripts', [$this, 'register_assets']);
    add_action('rest_api_init', [$this, 'register_rest_routes']);
    add_shortcode(self::SHORTCODE, [$this, 'shortcode']);
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'action_links']);

    if (is_admin()) {
      require_once __DIR__ . '/admin/settings.php';
      new GCode_Reference_JSON_Settings(self::OPT_KEY);
    }
  }

  /**
   * Load text domain for translations.
   *
   * @since 2.0.0
   */
  public function load_textdomain()
  {
    load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
  }

  public function plugin_version()
  {
    return dirname(plugin_basename(__FILE__));
  }

  /**
   * Register CSS and JS assets.
   *
   * @since 1.0.0
   */
  public function register_assets()
  {
    $base = plugin_dir_url(__FILE__);

    // Use timestamp for cache busting to prevent old cached versions
    $version = '2.0.6-' . filemtime(__DIR__ . '/assets/app.min.css');

    // Use minified assets in production, original in development
    $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

    wp_register_style(
      self::HANDLE_CSS,
      $base . 'assets/app' . $suffix . '.css',
      [],
      $version
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
      $base . 'assets/app' . $suffix . '.js',
      [self::HANDLE_FUSE],
      $version,
      true
    );
  }

  /**
   * Add Settings link to plugin action links.
   *
   * @since 1.0.0
   * @param array $links Existing plugin action links.
   * @return array Modified plugin action links.
   */
  public function action_links($links)
  {
    $url = admin_url('options-general.php?page=gcode-reference');
    array_unshift($links, '<a href="' . esc_url($url) . '">' . __('Settings', self::TEXT_DOMAIN) . '</a>');
    return $links;
  }

  /**
   * Get plugin settings.
   *
   * @since 1.0.0
   * @return array Plugin settings.
   */
  private function get_settings()
  {
    $defaults = [
      'uploaded_json_url' => '',
      'use_uploaded' => 0,
    ];
    $opt = get_option(self::OPT_KEY, []);
    if (!is_array($opt)) $opt = [];
    return array_merge($defaults, $opt);
  }

  /**
   * Get default JSON file URL.
   *
   * @since 1.0.0
   * @param string $source Source identifier (marlin, klipper, etc.).
   * @return string Default JSON URL.
   */
  private function get_default_json_url($source = 'marlin')
  {
    $source = sanitize_key($source);
    if (empty($source)) $source = 'marlin';

    // Map source to filename
    $filename = $source . '-commands.json';
    return plugin_dir_url(__FILE__) . 'data/' . $filename;
  }

  private function get_rest_json_url()
  {
    return rest_url('gcode-reference/v1/commands');
  }

  private function get_default_json_path($source = 'marlin')
  {
    $source = sanitize_key($source);
    if (empty($source)) $source = 'marlin';

    // Map source to filename
    $filename = $source . '-commands.json';
    return plugin_dir_path(__FILE__) . 'data/' . $filename;
  }

  private function get_uploaded_json_path()
  {
    $u = wp_upload_dir();
    return trailingslashit($u['basedir']) . 'gcode-reference/commands.json';
  }

  private function get_effective_json_path()
  {
    $s = $this->get_settings();
    if (!empty($s['use_uploaded'])) {
      $uploaded = $this->get_uploaded_json_path();
      if (is_readable($uploaded)) return $uploaded;
    }
    $default = $this->get_default_json_path();
    return is_readable($default) ? $default : '';
  }

  private function get_effective_json_url()
  {
    $s = $this->get_settings();
    if (!empty($s['use_uploaded']) && !empty($s['uploaded_json_url'])) {
      return esc_url_raw($s['uploaded_json_url']);
    }
    return $this->get_default_json_url();
  }

  /**
   * Register REST API routes.
   *
   * @since 1.0.0
   */
  public function register_rest_routes()
  {
    register_rest_route('gcode-reference/v1', '/commands', [
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'rest_commands'],
      'permission_callback' => '__return_true',
    ]);
  }

  /**
   * REST API callback for /commands endpoint.
   *
   * @since 1.0.0
   * @param WP_REST_Request $request REST request object.
   * @return WP_REST_Response|WP_Error Response or error.
   */
  public function rest_commands($request)
  {
    $path = $this->get_effective_json_path();
    if (!$path || !is_readable($path)) {
      return new WP_Error('gcode_reference_missing', __('commands.json not found', self::TEXT_DOMAIN), ['status' => 404]);
    }

    // Try to get cached data
    $cache_key = 'gcode_json_' . md5($path . filemtime($path));
    $cached_data = get_transient($cache_key);

    if (false !== $cached_data && is_array($cached_data)) {
      return rest_ensure_response($cached_data);
    }

    // Read and parse JSON
    $raw = file_get_contents($path);
    if ($raw === false || $raw === '') {
      return new WP_Error('gcode_reference_read', __('Failed to read commands.json', self::TEXT_DOMAIN), ['status' => 500]);
    }

    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return new WP_Error('gcode_reference_invalid', sprintf(__('Invalid JSON: %s', self::TEXT_DOMAIN), json_last_error_msg()), ['status' => 500]);
    }

    // Cache for 12 hours
    set_transient($cache_key, $data, 12 * HOUR_IN_SECONDS);

    return rest_ensure_response($data);
  }

  /**
   * Shortcode handler for [gcode_reference].
   *
   * @since 1.0.0
   * @param array $atts Shortcode attributes.
   * @return string Rendered shortcode output.
   */
  public function shortcode($atts = [])
  {
    $atts = shortcode_atts([
      'json_url' => '',
      'height' => '600px',
      'source' => 'marlin',
    ], $atts, 'gcode_reference');

    wp_enqueue_style(self::HANDLE_CSS);
    wp_enqueue_script(self::HANDLE_JS);

    $json_url = !empty($atts['json_url']) ? esc_url_raw($atts['json_url']) : $this->get_default_json_url($atts['source']);

    $config = [
      'jsonUrl' => $json_url,
      'fallbackJsonUrl' => '',
      'ui' => [
        'defaultLang' => 'de',
      ],
    ];

    // CRITICAL: JavaScript expects "GCodeRefConfig" not "gcodeReferenceConfig"
    wp_add_inline_script(self::HANDLE_JS, 'window.GCodeRefConfig = ' . wp_json_encode($config) . ';', 'before');

    // CRITICAL: HTML structure MUST match CSS expectations
    ob_start();
?>
    <div id="gref-root" class="gref">
      <div id="gref-status" class="gref__status"></div>

      <div class="gref__shell" style="height: <?php echo esc_attr($atts['height']); ?>;">
        <!-- LEFT: TOC -->
        <div class="gref__toc">
          <div class="gref__tocTop">
            <div class="gref__tocTitle">G-codes</div>
            <div class="gref__lang">
              <button type="button" class="gref__langBtn" data-lang="de">DE</button>
              <button type="button" class="gref__langBtn" data-lang="en">EN</button>
            </div>
          </div>
          <div id="gref-toc" class="gref__tocList"></div>
        </div>

        <!-- CENTER: Search + Results -->
        <div class="gref__pane">
          <div class="gref__top">
            <div class="gref__label">G-code Suche</div>
            <div class="gref__searchRow">
              <input id="gref-search" type="search" class="gref__search" placeholder="Suche..." />
              <button type="button" class="gref__clear" aria-label="Clear search">×</button>
            </div>
            <div class="gref__hint"></div>
          </div>

          <div class="gref__resultsWrap">
            <div id="gref-results" class="gref__results"></div>
          </div>
        </div>

        <!-- RIGHT: Explanation panel -->
        <div class="gref__panel">
          <div class="gref__panelTitle">Erklärung</div>
          <div id="gref-explain" class="gref__panelBody"></div>
        </div>
      </div>
    </div>
<?php
    return ob_get_clean();
  }
}

new GCode_Reference_JSON();
