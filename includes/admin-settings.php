<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * ADMIN SETTINGS PAGE - SEO WUNDERKISTE v2.7
 * ------------------------------------------------------------------------- */

function seowk_add_admin_menu() {
    add_options_page(
        __( 'SEO Wunderkiste Einstellungen', 'seo-wunderkiste' ),
        __( 'SEO Wunderkiste', 'seo-wunderkiste' ),
        'manage_options',
        'seo-wunderkiste',
        'seowk_options_page_html'
    );
}
add_action( 'admin_menu', 'seowk_add_admin_menu' );

function seowk_settings_init() {
    register_setting( 
        'seowk_plugin_group', 
        'seowk_settings',
        array(
            'sanitize_callback' => 'seowk_sanitize_settings',
        )
    );

    add_settings_section(
        'seowk_plugin_section',
        __( 'Aktive Module der Wunderkiste', 'seo-wunderkiste' ),
        'seowk_section_callback',
        'seo-wunderkiste'
    );

    // SEO & CONTENT MODULE
    seowk_add_module_field( 'seowk_enable_meta_settings', 'SEO Meta Settings', 'Erweiterte Meta-Tags: Title, Description, Open Graph, Twitter Cards pro Seite.' );
    seowk_add_module_field( 'seowk_enable_schema', 'SEO Schema (JSON-LD)', 'Fügt ein Eingabefeld für strukturierte Daten hinzu.' );
    seowk_add_module_field( 'seowk_enable_bulk_noindex', 'Bulk NoIndex Manager', 'Ermöglicht das massenhafte Setzen/Entfernen von NoIndex.' );
    seowk_add_module_field( 'seowk_enable_seo_redirects', 'SEO Zombie Killer', 'Leitet leere Anhang-Seiten auf Beiträge um (301).' );
    seowk_add_module_field( 'seowk_enable_conversion_tracker', 'Conversion Tracker', 'Ermöglicht GA4 und Google Ads Conversion-Tracking.' );
    
    // BILD & MEDIA MODULE
    seowk_add_module_field( 'seowk_enable_resizer', 'Image Resizer (800px/1200px)', 'Button in Mediendetails zum Skalieren (92% Qualität).' );
    seowk_add_module_field( 'seowk_enable_cleaner', 'Upload Cleaner', 'Dateinamen beim Upload automatisch bereinigen.' );
    seowk_add_module_field( 'seowk_enable_image_seo', 'Zero-Click Image SEO', 'Auto-Titel & Alt-Tags aus Dateinamen generieren.' );
    seowk_add_module_field( 'seowk_enable_media_columns', 'Media Inspector', 'Zeigt Dateigröße und Pixelmaße in der Medienübersicht.' );
    seowk_add_module_field( 'seowk_enable_svg', 'SVG Upload Support', 'Erlaubt das Hochladen von SVG-Dateien mit Sicherheits-Sanitization.' );
    
    // PERFORMANCE MODULE
    seowk_add_module_field( 'seowk_disable_emojis', 'Emoji Bloat Remover', 'Entfernt WordPress Emoji-Skripte für schnellere Ladezeiten.' );
    
    // SICHERHEIT & ADMIN MODULE
    seowk_add_module_field( 'seowk_disable_xmlrpc', 'XML-RPC Blocker', 'Schließt die XML-RPC Schnittstelle.' );
    seowk_add_module_field( 'seowk_enable_login_protection', 'Login Türsteher', 'Versteckt die Login-Seite hinter einem geheimen Parameter.' );
    
    add_settings_field(
        'seowk_login_protection_key',
        __( 'Türsteher Schlüssel', 'seo-wunderkiste' ),
        'seowk_text_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_login_protection_key',
            'description' => __( 'Dein geheimes Wort. Login nur via: <code>wp-login.php?DEINWORT</code>. (Standard: hintereingang)', 'seo-wunderkiste' )
        )
    );
    
    seowk_add_module_field( 'seowk_enable_comment_blocker', 'Comment Blocker', 'Deaktiviert Kommentare global auf der Website.' );
    seowk_add_module_field( 'seowk_enable_id_column', 'ID Column Display', 'Zeigt die Post/Page/Media ID in allen Übersichten an.' );
    
    // CONTENT TOOLS MODULE
    seowk_add_module_field( 'seowk_enable_date_shortcode', 'Date Shortcode', 'Fügt aktuelles Datum via Shortcode ein.' );
    seowk_add_module_field( 'seowk_enable_semantic_blocks', 'Semantic Blocks', 'HTML5 Wrapper-Blöcke für bessere Struktur und SEO.' );
}
add_action( 'admin_init', 'seowk_settings_init' );

function seowk_add_module_field( $id, $title, $description ) {
    add_settings_field(
        $id,
        $title,
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => $id,
            'description' => $description
        )
    );
}

function seowk_sanitize_settings( $input ) {
    $sanitized = array();
    
    $checkbox_fields = array(
        'seowk_enable_meta_settings', 'seowk_enable_schema', 'seowk_enable_bulk_noindex',
        'seowk_enable_seo_redirects', 'seowk_enable_conversion_tracker', 'seowk_enable_resizer',
        'seowk_enable_cleaner', 'seowk_enable_image_seo', 'seowk_enable_media_columns',
        'seowk_enable_svg', 'seowk_disable_emojis', 'seowk_disable_xmlrpc',
        'seowk_enable_login_protection', 'seowk_enable_comment_blocker', 'seowk_enable_id_column',
        'seowk_enable_date_shortcode', 'seowk_enable_semantic_blocks',
    );
    
    foreach ( $checkbox_fields as $field ) {
        $sanitized[ $field ] = ! empty( $input[ $field ] ) ? 1 : 0;
    }
    
    if ( isset( $input['seowk_login_protection_key'] ) ) {
        $sanitized['seowk_login_protection_key'] = sanitize_text_field( $input['seowk_login_protection_key'] );
    }
    
    return $sanitized;
}

function seowk_section_callback() {
    echo '<p style="font-size: 14px; color: #666;">' . esc_html__( 'Wähle hier die Werkzeuge aus, die du aktivieren möchtest.', 'seo-wunderkiste' ) . '</p>';
}

function seowk_checkbox_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $checked = isset( $options[ $field ] ) ? $options[ $field ] : false;
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <label style="display: flex; align-items: center;">
        <input type="checkbox" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="1" <?php checked( 1, $checked ); ?>>
        <?php if ( ! empty( $desc ) ) : ?>
            <span style="margin-left: 8px; color: #666;"><?php echo esc_html( $desc ); ?></span>
        <?php endif; ?>
    </label>
    <?php
}

function seowk_text_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $value   = isset( $options[ $field ] ) ? $options[ $field ] : '';
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <input type="text" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="hintereingang">
    <?php if ( ! empty( $desc ) ) : ?>
        <p class="description"><?php echo wp_kses( $desc, array( 'code' => array() ) ); ?></p>
    <?php endif; ?>
    <?php
}

function seowk_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    settings_errors( 'seowk_messages' );
    ?>
    <div class="wrap">
        <h1 style="display: flex; align-items: center; gap: 10px;">
            <span>📦</span>
            <span><?php esc_html_e( 'SEO Wunderkiste', 'seo-wunderkiste' ); ?></span>
            <span style="font-size: 14px; background: #2271b1; color: white; padding: 4px 12px; border-radius: 3px;">v<?php echo esc_html( SEOWK_VERSION ); ?></span>
        </h1>
        
        <p style="font-size: 16px; margin: 20px 0;">
            <?php esc_html_e( 'Deine modulare All-in-One Lösung für SEO, Performance und Verwaltung.', 'seo-wunderkiste' ); ?>
        </p>

        <div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0;">💡 <?php esc_html_e( 'So funktioniert\'s:', 'seo-wunderkiste' ); ?></h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>✅ <?php esc_html_e( 'Aktiviere nur die Module, die du wirklich brauchst', 'seo-wunderkiste' ); ?></li>
                <li>🚀 <?php esc_html_e( 'Jedes Modul arbeitet unabhängig und performant', 'seo-wunderkiste' ); ?></li>
                <li>🔒 <?php esc_html_e( 'Standardmäßig sind alle Module deaktiviert', 'seo-wunderkiste' ); ?></li>
            </ul>
        </div>

        <form action="options.php" method="post" style="background: white; border: 1px solid #ccd0d4; padding: 20px; border-radius: 4px;">
            <?php
            settings_fields( 'seowk_plugin_group' );
            do_settings_sections( 'seo-wunderkiste' );
            submit_button( __( 'Einstellungen speichern', 'seo-wunderkiste' ), 'primary large' );
            ?>
        </form>

        <div style="margin: 30px 0; padding: 15px; background: #f9f9f9; border-radius: 4px; text-align: center; color: #666;">
            <p style="margin: 0;">
                <?php 
                printf( 
                    /* translators: %s: author name */
                    esc_html__( 'Entwickelt mit ❤️ von %s', 'seo-wunderkiste' ), 
                    '<strong>Michael Kanda</strong>' 
                ); 
                ?>
            </p>
        </div>
    </div>

    <style>
    .form-table th { width: 250px; font-weight: 600; }
    .form-table td { padding: 15px 10px; }
    </style>
    <?php
}
