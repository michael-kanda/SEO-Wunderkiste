<?php
/**
 * Plugin Name: SEO Wunderkiste
 * Plugin URI: https://developer.designare.at/seo-wunderkiste
 * Description: Deine modulare All-in-One Lösung: SEO Schema, Meta Settings, Bild-Optimierung, Cleaner, Security, Tracking & mehr.
 * Version: 2.7
 * Author: Michael Kanda
 * Author URI: https://developer.designare.at
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: seo-wunderkiste
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ------------------------------------------------------------------------- *
 * PLUGIN CONSTANTS
 * ------------------------------------------------------------------------- */

define( 'SEOWK_VERSION', '2.7' );
define( 'SEOWK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEOWK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SEOWK_PLUGIN_FILE', __FILE__ );

/* ------------------------------------------------------------------------- *
 * ADMIN SETTINGS LADEN
 * ------------------------------------------------------------------------- */

require_once SEOWK_PLUGIN_DIR . 'includes/admin-settings.php';

/* ------------------------------------------------------------------------- *
 * OPTIONEN ABRUFEN (prefixed variable)
 * ------------------------------------------------------------------------- */

$seowk_options = get_option( 'seowk_settings', array() );

/* ------------------------------------------------------------------------- *
 * MODULE BEDINGT LADEN
 * ------------------------------------------------------------------------- */

// SEO & CONTENT MODULE
if ( ! empty( $seowk_options['seowk_enable_meta_settings'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-meta-settings.php';
}

if ( ! empty( $seowk_options['seowk_enable_schema'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-schema.php';
}

if ( ! empty( $seowk_options['seowk_enable_bulk_noindex'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-bulk-noindex.php';
}

if ( ! empty( $seowk_options['seowk_enable_seo_redirects'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-seo-redirects.php';
}

if ( ! empty( $seowk_options['seowk_enable_conversion_tracker'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-conversion-tracker.php';
}

// BILD & MEDIA MODULE
if ( ! empty( $seowk_options['seowk_enable_resizer'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-resizer.php';
}

if ( ! empty( $seowk_options['seowk_enable_cleaner'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-cleaner.php';
}

if ( ! empty( $seowk_options['seowk_enable_image_seo'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-image-seo.php';
}

if ( ! empty( $seowk_options['seowk_enable_media_columns'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-media-columns.php';
}

if ( ! empty( $seowk_options['seowk_enable_svg'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-svg.php';
}

// PERFORMANCE MODULE
if ( ! empty( $seowk_options['seowk_disable_emojis'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-disable-emojis.php';
}

// SICHERHEIT & ADMIN MODULE
if ( ! empty( $seowk_options['seowk_disable_xmlrpc'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-disable-xmlrpc.php';
}

if ( ! empty( $seowk_options['seowk_enable_login_protection'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-login-protection.php';
}

if ( ! empty( $seowk_options['seowk_enable_comment_blocker'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-comment-blocker.php';
}

// ADMIN TOOLS MODULE
if ( ! empty( $seowk_options['seowk_enable_id_column'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-id-column.php';
}

// CONTENT TOOLS MODULE
if ( ! empty( $seowk_options['seowk_enable_date_shortcode'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-date-shortcode.php';
}

if ( ! empty( $seowk_options['seowk_enable_semantic_blocks'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-semantic-blocks.php';
}

/* ------------------------------------------------------------------------- *
 * PLUGIN ACTIVATION & DEACTIVATION HOOKS
 * ------------------------------------------------------------------------- */

function seowk_plugin_activate() {
    if ( ! get_option( 'seowk_settings' ) ) {
        $default_options = array(
            'seowk_login_protection_key' => 'hintereingang'
        );
        add_option( 'seowk_settings', $default_options );
    }
    
    // Languages-Ordner erstellen
    $languages_dir = SEOWK_PLUGIN_DIR . 'languages';
    if ( ! file_exists( $languages_dir ) ) {
        wp_mkdir_p( $languages_dir );
    }

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'seowk_plugin_activate' );

function seowk_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'seowk_plugin_deactivate' );

/* ------------------------------------------------------------------------- *
 * PLUGIN LINKS IN DER PLUGIN-ÜBERSICHT
 * ------------------------------------------------------------------------- */

function seowk_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=seo-wunderkiste">Einstellungen</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'seowk_add_settings_link' );

function seowk_add_plugin_meta_links( $links, $file ) {
    if ( strpos( $file, basename( __FILE__ ) ) !== false ) {
        $new_links = array(
            '<a href="#" style="color: #d63638; font-weight: 600;">Dokumentation</a>',
            '<a href="#" style="color: #2271b1;">Support</a>'
        );
        $links = array_merge( $links, $new_links );
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'seowk_add_plugin_meta_links', 10, 2 );

/* ------------------------------------------------------------------------- *
 * ADMIN NOTICES
 * ------------------------------------------------------------------------- */

function seowk_admin_notice_after_activation() {
    if ( get_transient( 'seowk_activation_notice' ) ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong>🎉 SEO Wunderkiste aktiviert!</strong> 
                Gehe zu <a href="<?php echo esc_url( admin_url( 'options-general.php?page=seo-wunderkiste' ) ); ?>">Einstellungen → SEO Wunderkiste</a>, 
                um deine gewünschten Module zu aktivieren.
            </p>
        </div>
        <?php
        delete_transient( 'seowk_activation_notice' );
    }
}
add_action( 'admin_notices', 'seowk_admin_notice_after_activation' );

function seowk_set_activation_transient() {
    set_transient( 'seowk_activation_notice', true, 5 );
}
register_activation_hook( __FILE__, 'seowk_set_activation_transient' );

/* ------------------------------------------------------------------------- *
 * DEBUG INFO (nur für Admins)
 * ------------------------------------------------------------------------- */

function seowk_admin_footer_debug() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $seowk_opts = get_option( 'seowk_settings', array() );
    $active_modules = array_filter( $seowk_opts );
    $module_count = count( $active_modules );

    ?>
    <script>
    console.log('%c🎯 SEO Wunderkiste v<?php echo esc_js( SEOWK_VERSION ); ?>', 'background: #2271b1; color: white; padding: 5px 10px; border-radius: 3px;');
    console.log('Aktive Module: <?php echo esc_js( (string) $module_count ); ?>');
    <?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
    console.log('Module:', <?php echo wp_json_encode( array_keys( $active_modules ) ); ?>);
    <?php endif; ?>
    </script>
    <?php
}
add_action( 'admin_footer', 'seowk_admin_footer_debug' );

/* ------------------------------------------------------------------------- *
 * MODUL-ÜBERSICHT FÜR ENTWICKLER
 * ------------------------------------------------------------------------- */

function seowk_get_available_modules() {
    return array(
        'seo_content' => array(
            'seowk_enable_meta_settings' => 'SEO Meta Settings',
            'seowk_enable_schema' => 'SEO Schema (JSON-LD)',
            'seowk_enable_bulk_noindex' => 'Bulk NoIndex Manager',
            'seowk_enable_seo_redirects' => 'SEO Zombie Killer',
            'seowk_enable_conversion_tracker' => 'Conversion Tracker',
        ),
        'media' => array(
            'seowk_enable_resizer' => 'Image Resizer (800px)',
            'seowk_enable_cleaner' => 'Upload Cleaner',
            'seowk_enable_image_seo' => 'Zero-Click Image SEO',
            'seowk_enable_media_columns' => 'Media Inspector',
            'seowk_enable_svg' => 'SVG Upload Support',
        ),
        'performance' => array(
            'seowk_disable_emojis' => 'Emoji Bloat Remover',
        ),
        'security_admin' => array(
            'seowk_disable_xmlrpc' => 'XML-RPC Blocker',
            'seowk_enable_login_protection' => 'Login Türsteher',
            'seowk_enable_comment_blocker' => 'Comment Blocker',
            'seowk_enable_id_column' => 'ID Column Display',
        ),
        'content_tools' => array(
            'seowk_enable_date_shortcode' => 'Date Shortcode',
            'seowk_enable_semantic_blocks' => 'Semantic Blocks',
        ),
    );
}
