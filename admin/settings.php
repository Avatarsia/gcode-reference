<?php
if (!defined('ABSPATH')) exit;

class GCode_Reference_JSON_Settings
{
  private $opt_key;

  public function __construct($opt_key)
  {
    $this->opt_key = $opt_key;
    add_action('admin_menu', [$this, 'menu']);
    add_action('admin_post_gcode_reference_upload_json', [$this, 'handle_upload']);
    add_action('admin_post_gcode_reference_toggle_source', [$this, 'handle_toggle']);
  }

  public function menu()
  {
    add_options_page(
      __('G-code Reference', 'gcode-reference'),
      __('G-code Reference', 'gcode-reference'),
      'manage_options',
      'gcode-reference',
      [$this, 'page']
    );
  }

  private function get_settings()
  {
    $defaults = [
      // Legacy single-source (for backward compatibility)
      'uploaded_json_url' => '',
      'use_uploaded' => 0,

      // NEW: Multi-source structure
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
    $opt = get_option($this->opt_key, []);
    if (!is_array($opt)) $opt = [];
    return array_merge($defaults, $opt);
  }

  private function update_settings($new)
  {
    update_option($this->opt_key, $new, false);
  }

  private function uploads_dir_path()
  {
    $u = wp_upload_dir();
    return trailingslashit($u['basedir']) . 'gcode-reference/';
  }

  private function uploads_dir_url()
  {
    $u = wp_upload_dir();
    return trailingslashit($u['baseurl']) . 'gcode-reference/';
  }

  private function validate_json($raw)
  {
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) return [false, __('Invalid JSON: ', 'gcode-reference') . json_last_error_msg()];

    // Expect root object with meta + commands
    if (!is_array($data) || empty($data['commands']) || !is_array($data['commands'])) {
      return [false, __('Schema error: expected { meta: {...}, commands: [...] }', 'gcode-reference')];
    }

    // Quick sanity checks (tight but not overly restrictive)
    foreach ($data['commands'] as $i => $cmd) {
      if (!is_array($cmd)) return [false, "Command #$i must be an object"];
      foreach (['id', 'code'] as $k) {
        if (empty($cmd[$k]) || !is_string($cmd[$k])) return [false, "Command #$i missing '$k' string"];
      }
      if (!preg_match('/^[A-Za-z0-9\.\-\_]+$/', $cmd['id'])) {
        return [false, "Command #$i id has invalid characters (allowed: letters, numbers, . - _)"];
      }
      if (isset($cmd['examples']) && !is_array($cmd['examples'])) return [false, "Command #$i examples must be an array"];
    }

    return [true, 'OK'];
  }

  public function handle_toggle()
  {
    if (!current_user_can('manage_options')) wp_die(__('Forbidden', 'gcode-reference'));
    check_admin_referer('gcode_reference_toggle_source');

    $s = $this->get_settings();
    $use = isset($_POST['use_uploaded']) ? 1 : 0;
    $s['use_uploaded'] = $use;
    $this->update_settings($s);

    wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'updated' => '1'], admin_url('options-general.php')));
    exit;
  }

  public function handle_upload()
  {
    if (!current_user_can('manage_options')) wp_die(__('Forbidden', 'gcode-reference'));
    check_admin_referer('gcode_reference_upload_json');

    if (empty($_FILES['json_file']) || !isset($_FILES['json_file']['tmp_name'])) {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'no_file'], admin_url('options-general.php')));
      exit;
    }

    $file = $_FILES['json_file'];

    // Basic checks
    if (!empty($file['error'])) {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'upload_error'], admin_url('options-general.php')));
      exit;
    }

    // Only accept .json
    $name = isset($file['name']) ? $file['name'] : '';
    if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) !== 'json') {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'ext'], admin_url('options-general.php')));
      exit;
    }

    $raw = file_get_contents($file['tmp_name']);
    if ($raw === false || $raw === '') {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'empty'], admin_url('options-general.php')));
      exit;
    }

    list($ok, $msg) = $this->validate_json($raw);
    if (!$ok) {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'invalid', 'msg' => rawurlencode($msg)], admin_url('options-general.php')));
      exit;
    }

    // Store in uploads (writable on most hosts)
    $dir = $this->uploads_dir_path();
    if (!wp_mkdir_p($dir)) {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'mkdir'], admin_url('options-general.php')));
      exit;
    }

    $target = $dir . 'commands.json';
    $written = file_put_contents($target, $raw);
    if ($written === false) {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'write'], admin_url('options-general.php')));
      exit;
    }

    $s = $this->get_settings();
    $s['uploaded_json_url'] = $this->uploads_dir_url() . 'commands.json';
    $this->update_settings($s);

    wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'uploaded' => '1'], admin_url('options-general.php')));
    exit;
  }

  public function page()
  {
    $s = $this->get_settings();
    $use_uploaded = !empty($s['use_uploaded']);
    $uploaded_url = !empty($s['uploaded_json_url']) ? esc_url($s['uploaded_json_url']) : '';

    $err = isset($_GET['err']) ? sanitize_text_field($_GET['err']) : '';
    $msg = isset($_GET['msg']) ? sanitize_text_field($_GET['msg']) : '';

?>
    <div class="wrap">
      <h1><?php _e('G-code Reference', 'gcode-reference'); ?></h1>

      <?php if ($err): ?>
        <div class="notice notice-error">
          <p>
            <strong><?php _e('Error:', 'gcode-reference'); ?></strong>
            <?php echo esc_html($err); ?>
            <?php if ($msg) echo ' — ' . esc_html($msg); ?>
          </p>
        </div>
      <?php elseif (isset($_GET['uploaded'])): ?>
        <div class="notice notice-success">
          <p><?php _e('JSON uploaded successfully.', 'gcode-reference'); ?></p>
        </div>
      <?php elseif (isset($_GET['updated'])): ?>
        <div class="notice notice-success">
          <p><?php _e('Settings saved.', 'gcode-reference'); ?></p>
        </div>
      <?php endif; ?>

      <h2><?php _e('Data source', 'gcode-reference'); ?></h2>
      <p>
        <?php _e('Default JSON is bundled in the plugin. Optionally upload an override JSON (saved in uploads).', 'gcode-reference'); ?>
      </p>

      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('gcode_reference_toggle_source'); ?>
        <input type="hidden" name="action" value="gcode_reference_toggle_source" />
        <label>
          <input type="checkbox" name="use_uploaded" <?php checked($use_uploaded); ?> />
          <?php _e('Use uploaded JSON override', 'gcode-reference'); ?>
        </label>
        <p class="description">
          <?php _e('Uploaded JSON URL:', 'gcode-reference'); ?> <?php echo $uploaded_url ? '<code>' . esc_html($uploaded_url) . '</code>' : '<em>' . __('none', 'gcode-reference') . '</em>'; ?>
        </p>
        <?php submit_button(__('Save source settings', 'gcode-reference')); ?>
      </form>

      <hr />

      <h2><?php _e('Upload JSON', 'gcode-reference'); ?></h2>
      <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('gcode_reference_upload_json'); ?>
        <input type="hidden" name="action" value="gcode_reference_upload_json" />
        <input type="file" name="json_file" accept="application/json,.json" required />
        <?php submit_button(__('Upload and validate', 'gcode-reference')); ?>
      </form>

      <hr />

      <h2>Shortcode</h2>
      <p><code>[gcode_reference]</code></p>
      <p>Override JSON URL for a page: <code>[gcode_reference json_url="https://example.com/commands.json"]</code></p>
    </div>
<?php
  }
}
