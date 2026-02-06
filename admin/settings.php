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
    add_action('admin_head', [$this, 'admin_styles']);
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

  public function admin_styles()
  {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'settings_page_gcode-reference') {
?>
      <style>
        .gcode-admin-wrap {
          max-width: 1200px;
          margin: 20px 0;
        }

        .gcode-admin-header {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: #fff;
          padding: 30px;
          border-radius: 8px;
          margin-bottom: 30px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .gcode-admin-header h1 {
          margin: 0 0 10px 0;
          font-size: 28px;
          font-weight: 600;
          color: #fff;
        }

        .gcode-admin-header p {
          margin: 0;
          opacity: 0.95;
          font-size: 15px;
        }

        .gcode-cards-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
        }

        .gcode-card {
          background: #fff;
          border: 1px solid #ddd;
          border-radius: 8px;
          padding: 0;
          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
          transition: all 0.2s ease;
        }

        .gcode-card:hover {
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
          border-color: #667eea;
        }

        .gcode-card-header {
          padding: 20px;
          border-bottom: 1px solid #f0f0f0;
          display: flex;
          align-items: center;
          gap: 12px;
        }

        .gcode-card-icon {
          width: 40px;
          height: 40px;
          border-radius: 8px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 20px;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: #fff;
        }

        .gcode-card-title {
          margin: 0;
          font-size: 18px;
          font-weight: 600;
          color: #1d2327;
        }

        .gcode-card-body {
          padding: 20px;
        }

        .gcode-firmware-list {
          list-style: none;
          margin: 0;
          padding: 0;
        }

        .gcode-firmware-item {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 12px;
          margin-bottom: 8px;
          background: #f8f9fa;
          border-radius: 6px;
          transition: background 0.2s;
        }

        .gcode-firmware-item:hover {
          background: #e9ecef;
        }

        .gcode-firmware-info {
          display: flex;
          align-items: center;
          gap: 10px;
        }

        .gcode-firmware-badge {
          display: inline-block;
          padding: 4px 10px;
          background: #667eea;
          color: #fff;
          border-radius: 12px;
          font-size: 11px;
          font-weight: 600;
          text-transform: uppercase;
        }

        .gcode-firmware-badge.default {
          background: #10b981;
        }

        .gcode-shortcode-box {
          background: #f8f9fa;
          border-left: 4px solid #667eea;
          padding: 12px 16px;
          margin: 10px 0;
          border-radius: 4px;
          font-family: 'Courier New', monospace;
          font-size: 13px;
        }

        .gcode-notice {
          background: #fff3cd;
          border-left: 4px solid #ffc107;
          padding: 12px 16px;
          margin: 10px 0;
          border-radius: 4px;
        }

        .gcode-notice.info {
          background: #cfe2ff;
          border-left-color: #0dcaf0;
        }

        .gcode-notice.success {
          background: #d1e7dd;
          border-left-color: #198754;
        }

        .gcode-stats {
          display: flex;
          gap: 20px;
          flex-wrap: wrap;
        }

        .gcode-stat {
          flex: 1;
          min-width: 120px;
        }

        .gcode-stat-value {
          font-size: 32px;
          font-weight: 700;
          color: #667eea;
          line-height: 1;
          margin-bottom: 5px;
        }

        .gcode-stat-label {
          font-size: 13px;
          color: #6c757d;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
          .gcode-cards-grid {
            grid-template-columns: 1fr;
          }
        }
      </style>
    <?php
    }
  }

  private function get_settings()
  {
    $defaults = [
      'uploaded_json_url' => '',
      'use_uploaded' => 0,
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

    if (!is_array($data) || empty($data['commands']) || !is_array($data['commands'])) {
      return [false, __('Schema error: expected { meta: {...}, commands: [...] }', 'gcode-reference')];
    }

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

    if (!empty($file['error'])) {
      wp_safe_redirect(add_query_arg(['page' => 'gcode-reference', 'err' => 'upload_error'], admin_url('options-general.php')));
      exit;
    }

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
    <div class="wrap gcode-admin-wrap">

      <!-- Modern Header -->
      <div class="gcode-admin-header">
        <h1><span class="dashicons dashicons-media-code" style="font-size: 28px; width: 28px; height: 28px;"></span> <?php _e('G-code Reference', 'gcode-reference'); ?></h1>
        <p><?php _e('Interactive G-code documentation for 3D printer firmware', 'gcode-reference'); ?></p>
      </div>

      <?php if ($err): ?>
        <div class="notice notice-error is-dismissible">
          <p>
            <strong><?php _e('Error:', 'gcode-reference'); ?></strong>
            <?php echo esc_html($err); ?>
            <?php if ($msg) echo ' — ' . esc_html($msg); ?>
          </p>
        </div>
      <?php elseif (isset($_GET['uploaded'])): ?>
        <div class="notice notice-success is-dismissible">
          <p><?php _e('JSON uploaded successfully.', 'gcode-reference'); ?></p>
        </div>
      <?php elseif (isset($_GET['updated'])): ?>
        <div class="notice notice-success is-dismissible">
          <p><?php _e('Settings saved.', 'gcode-reference'); ?></p>
        </div>
      <?php endif; ?>

      <!-- Cards Grid -->
      <div class="gcode-cards-grid">

        <!-- Firmware Overview Card -->
        <div class="gcode-card">
          <div class="gcode-card-header">
            <div class="gcode-card-icon">
              <span class="dashicons dashicons-admin-tools" style="font-size: 20px; width: 20px; height: 20px;"></span>
            </div>
            <h2 class="gcode-card-title"><?php _e('Available Firmware', 'gcode-reference'); ?></h2>
          </div>
          <div class="gcode-card-body">
            <ul class="gcode-firmware-list">
              <li class="gcode-firmware-item">
                <div class="gcode-firmware-info">
                  <strong>Marlin</strong>
                  <span class="gcode-firmware-badge default"><?php _e('Default', 'gcode-reference'); ?></span>
                </div>
                <span style="color: #667eea; font-weight: 600;">254 <?php _e('Commands', 'gcode-reference'); ?></span>
              </li>
              <li class="gcode-firmware-item">
                <div class="gcode-firmware-info">
                  <strong>Klipper</strong>
                </div>
                <span style="color: #667eea; font-weight: 600;">50 <?php _e('Commands', 'gcode-reference'); ?></span>
              </li>
            </ul>

            <div class="gcode-notice info" style="margin-top: 15px;">
              <strong><?php _e('Total:', 'gcode-reference'); ?></strong> 304 <?php _e('G-code commands available', 'gcode-reference'); ?>
            </div>
          </div>
        </div>

        <!-- Usage Card -->
        <div class="gcode-card">
          <div class="gcode-card-header">
            <div class="gcode-card-icon">
              <span class="dashicons dashicons-editor-code" style="font-size: 20px; width: 20px; height: 20px;"></span>
            </div>
            <h2 class="gcode-card-title"><?php _e('Shortcode Usage', 'gcode-reference'); ?></h2>
          </div>
          <div class="gcode-card-body">
            <p><strong><?php _e('Basic:', 'gcode-reference'); ?></strong></p>
            <div class="gcode-shortcode-box">[gcode_reference]</div>

            <p style="margin-top: 15px;"><strong><?php _e('Select Firmware:', 'gcode-reference'); ?></strong></p>
            <div class="gcode-shortcode-box">[gcode_reference source="marlin"]</div>
            <div class="gcode-shortcode-box">[gcode_reference source="klipper"]</div>

            <p style="margin-top: 15px;"><strong><?php _e('Custom Height:', 'gcode-reference'); ?></strong></p>
            <div class="gcode-shortcode-box">[gcode_reference height="800px"]</div>
          </div>
        </div>

        <!-- Statistics Card -->
        <div class="gcode-card">
          <div class="gcode-card-header">
            <div class="gcode-card-icon">
              <span class="dashicons dashicons-chart-bar" style="font-size: 20px; width: 20px; height: 20px;"></span>
            </div>
            <h2 class="gcode-card-title"><?php _e('Statistics', 'gcode-reference'); ?></h2>
          </div>
          <div class="gcode-card-body">
            <div class="gcode-stats">
              <div class="gcode-stat">
                <div class="gcode-stat-value">2</div>
                <div class="gcode-stat-label"><?php _e('Firmware', 'gcode-reference'); ?></div>
              </div>
              <div class="gcode-stat">
                <div class="gcode-stat-value">304</div>
                <div class="gcode-stat-label"><?php _e('Commands', 'gcode-reference'); ?></div>
              </div>
              <div class="gcode-stat">
                <div class="gcode-stat-value">2</div>
                <div class="gcode-stat-label"><?php _e('Languages', 'gcode-reference'); ?></div>
              </div>
            </div>

            <div class="gcode-notice success" style="margin-top: 20px;">
              <strong><?php _e('Version:', 'gcode-reference'); ?></strong> 2.0.0<br>
              <strong><?php _e('Status:', 'gcode-reference'); ?></strong> <?php _e('Production Ready', 'gcode-reference'); ?>
            </div>
          </div>
        </div>

      </div>

      <!-- Advanced Settings -->
      <div class="gcode-card">
        <div class="gcode-card-header">
          <div class="gcode-card-icon">
            <span class="dashicons dashicons-admin-settings" style="font-size: 20px; width: 20px; height: 20px;"></span>
          </div>
          <h2 class="gcode-card-title"><?php _e('Advanced Settings', 'gcode-reference'); ?></h2>
        </div>
        <div class="gcode-card-body">

          <h3><?php _e('Custom JSON Upload', 'gcode-reference'); ?></h3>
          <p><?php _e('Override bundled commands with your own custom JSON file.', 'gcode-reference'); ?></p>

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
            <?php submit_button(__('Save Settings', 'gcode-reference'), 'primary', 'submit', false); ?>
          </form>

          <hr style="margin: 25px 0; border: none; border-top: 1px solid #ddd;" />

          <h3><?php _e('Upload New JSON', 'gcode-reference'); ?></h3>
          <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('gcode_reference_upload_json'); ?>
            <input type="hidden" name="action" value="gcode_reference_upload_json" />
            <p>
              <input type="file" name="json_file" accept="application/json,.json" required style="margin-bottom: 10px;" />
            </p>
            <?php submit_button(__('Upload and Validate', 'gcode-reference'), 'secondary', 'submit', false); ?>
          </form>

        </div>
      </div>

    </div>
<?php
  }
}
