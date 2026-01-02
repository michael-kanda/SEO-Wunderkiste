<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * ADMIN SETTINGS PAGE - SEO WUNDERKISTE v2.5
 * Alle Module in einer zentralen Übersicht
 * ------------------------------------------------------------------------- */

// 1. Menüpunkt hinzufügen
function seowk_add_admin_menu() {
    add_options_page(
        'SEO Wunderkiste Einstellungen',
        'SEO Wunderkiste',
        'manage_options',
        'seo-wunderkiste',
        'seowk_options_page_html'
    );
}
add_action( 'admin_menu', 'seowk_add_admin_menu' );

// 2. Einstellungen registrieren
function seowk_settings_init() {
    register_setting( 'seowk_plugin_group', 'seowk_settings' );

    add_settings_section(
        'seowk_plugin_section',
        'Aktive Module der Wunderkiste',
        'seowk_section_callback',
        'seo-wunderkiste'
    );

    /* ------------------------------------------------------------------------- *
     * SEO & CONTENT MODULE
     * ------------------------------------------------------------------------- */
    
    add_settings_field(
        'seowk_enable_meta_settings',
        'SEO Meta Settings ⭐ NEU',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_meta_settings',
            'description' => 'Erweiterte Meta-Tags: Title, Description, Open Graph, Twitter Cards pro Seite.'
        )
    );
    
    add_settings_field(
        'seowk_enable_schema',
        'SEO Schema (JSON-LD)',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_schema',
            'description' => 'Fügt ein Eingabefeld für strukturierte Daten hinzu.'
        )
    );

    add_settings_field(
        'seowk_enable_bulk_noindex',
        'Bulk NoIndex Manager',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_bulk_noindex',
            'description' => 'Ermöglicht das massenhafte Setzen/Entfernen von NoIndex für Seiten und Beiträge.'
        )
    );

    add_settings_field(
        'seowk_enable_seo_redirects',
        'SEO Zombie Killer',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_seo_redirects',
            'description' => 'Leitet leere Anhang-Seiten auf Beiträge um (301 Redirect).'
        )
    );

    add_settings_field(
        'seowk_enable_conversion_tracker',
        'Conversion Tracker',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_conversion_tracker',
            'description' => 'Ermöglicht GA4 und Google Ads Conversion-Tracking auf einzelnen Seiten.'
        )
    );

    /* ------------------------------------------------------------------------- *
     * BILD & MEDIA MODULE
     * ------------------------------------------------------------------------- */

    add_settings_field(
        'seowk_enable_resizer',
        'Image Resizer (800px)',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_resizer',
            'description' => 'Button in Mediendetails zum Skalieren auf 800px (92% Qualität).'
        )
    );

    add_settings_field(
        'seowk_enable_cleaner',
        'Upload Cleaner',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_cleaner',
            'description' => 'Dateinamen beim Upload automatisch bereinigen (Umlaute, Leerzeichen).'
        )
    );

    add_settings_field(
        'seowk_enable_image_seo',
        'Zero-Click Image SEO',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_image_seo',
            'description' => 'Auto-Titel & Alt-Tags aus Dateinamen generieren.'
        )
    );

    add_settings_field(
        'seowk_enable_media_columns',
        'Media Inspector',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_media_columns',
            'description' => 'Zeigt Dateigröße und Pixelmaße in der Medienübersicht.'
        )
    );

    add_settings_field(
        'seowk_enable_svg',
        'SVG Upload Support',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_svg',
            'description' => 'Erlaubt das Hochladen von SVG-Dateien in die Mediathek.'
        )
    );

    /* ------------------------------------------------------------------------- *
     * PERFORMANCE MODULE
     * ------------------------------------------------------------------------- */

    add_settings_field(
        'seowk_disable_emojis',
        'Emoji Bloat Remover',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_disable_emojis',
            'description' => 'Entfernt WordPress Emoji-Skripte für schnellere Ladezeiten.'
        )
    );

    /* ------------------------------------------------------------------------- *
     * SICHERHEIT & ADMIN MODULE
     * ------------------------------------------------------------------------- */

    add_settings_field(
        'seowk_disable_xmlrpc',
        'XML-RPC Blocker',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_disable_xmlrpc',
            'description' => 'Schließt die XML-RPC Schnittstelle (Schutz vor Brute-Force-Angriffen).'
        )
    );

    add_settings_field(
        'seowk_enable_login_protection',
        'Login Türsteher',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_login_protection',
            'description' => 'Versteckt die Login-Seite hinter einem geheimen Parameter.'
        )
    );

    add_settings_field(
        'seowk_login_protection_key',
        'Türsteher Schlüssel',
        'seowk_text_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_login_protection_key',
            'description' => 'Dein geheimes Wort. Login nur via: <code>wp-login.php?DEINWORT</code>. (Standard: hintereingang)'
        )
    );

    add_settings_field(
        'seowk_enable_comment_blocker',
        'Comment Blocker',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_comment_blocker',
            'description' => 'Deaktiviert Kommentare global auf der gesamten Website.'
        )
    );

    /* ------------------------------------------------------------------------- *
     * ADMIN TOOLS MODULE
     * ------------------------------------------------------------------------- */

    add_settings_field(
        'seowk_enable_id_column',
        'ID Column Display',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_enable_id_column',
            'description' => 'Zeigt die Post/Page/Media ID in allen Übersichten an (klickbar zum Kopieren).'
        )
    );
}
add_action( 'admin_init', 'seowk_settings_init' );

// 3. Section Callback
function seowk_section_callback() {
    echo '<p style="font-size: 14px; color: #666;">Wähle hier die Werkzeuge aus, die du aktivieren möchtest. Standardmäßig sind alle Module deaktiviert.</p>';
}

// 4. Checkbox Render
function seowk_checkbox_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $checked = isset( $options[ $field ] ) ? $options[ $field ] : false;
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <label style="display: flex; align-items: center;">
        <input type="checkbox" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="1" <?php checked( 1, $checked ); ?>>
        <?php if ( ! empty( $desc ) ) : ?>
            <span style="margin-left: 8px; color: #666;"><?php echo $desc; ?></span>
        <?php endif; ?>
    </label>
    <?php
}

// 5. Text Input Render
function seowk_text_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $value   = isset( $options[ $field ] ) ? $options[ $field ] : '';
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <input type="text" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="hintereingang">
    <?php if ( ! empty( $desc ) ) : ?>
        <p class="description"><?php echo $desc; ?></p>
    <?php endif; ?>
    <?php
}

// 6. HTML Page Output
function seowk_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Erfolgsmeldung nach dem Speichern
    if ( isset( $_GET['settings-updated'] ) ) {
        add_settings_error(
            'seowk_messages',
            'seowk_message',
            'Einstellungen erfolgreich gespeichert! 🎉',
            'updated'
        );
    }

    settings_errors( 'seowk_messages' );
    ?>
    <div class="wrap">
        <h1 style="display: flex; align-items: center; gap: 10px;">
            <span>📦</span>
            <span>SEO Wunderkiste</span>
            <span style="font-size: 14px; background: #2271b1; color: white; padding: 4px 12px; border-radius: 3px;">v2.5</span>
        </h1>
        
        <p style="font-size: 16px; margin: 20px 0;">
            Deine modulare All-in-One Lösung für SEO, Performance und Verwaltung.
        </p>

        <!-- Info Box -->
        <div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0;">💡 So funktioniert's:</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>✅ Aktiviere nur die Module, die du wirklich brauchst</li>
                <li>🚀 Jedes Modul arbeitet unabhängig und performant</li>
                <li>🔒 Standardmäßig sind alle Module deaktiviert (bessere Performance)</li>
                <li>💾 Änderungen werden sofort nach dem Speichern aktiv</li>
            </ul>
        </div>

        <!-- Module Übersicht -->
        <div style="background: white; border: 1px solid #ccd0d4; padding: 20px; margin: 20px 0; border-radius: 4px;">
            <h2 style="margin-top: 0;">📊 Modul-Übersicht:</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div style="padding: 10px; background: #f9f9f9; border-radius: 3px;">
                    <strong>SEO & Content</strong>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">5 Module</div>
                </div>
                <div style="padding: 10px; background: #f9f9f9; border-radius: 3px;">
                    <strong>Bild & Media</strong>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">5 Module</div>
                </div>
                <div style="padding: 10px; background: #f9f9f9; border-radius: 3px;">
                    <strong>Performance</strong>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">1 Modul</div>
                </div>
                <div style="padding: 10px; background: #f9f9f9; border-radius: 3px;">
                    <strong>Sicherheit & Admin</strong>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">4 Module</div>
                </div>
            </div>
        </div>

        <!-- Einstellungs-Formular -->
        <form action="options.php" method="post" style="background: white; border: 1px solid #ccd0d4; padding: 20px; border-radius: 4px;">
            <?php
            settings_fields( 'seowk_plugin_group' );
            do_settings_sections( 'seo-wunderkiste' );
            submit_button( 'Einstellungen speichern', 'primary large', 'submit', true, array( 'style' => 'margin-top: 20px;' ) );
            ?>
        </form>

        <!-- Footer Info -->
        <div style="margin: 30px 0; padding: 15px; background: #f9f9f9; border-radius: 4px; text-align: center; color: #666;">
            <p style="margin: 0;">
                Entwickelt mit ❤️ von <strong>Michael Kanda</strong> | 
                <a href="#" style="color: #2271b1; text-decoration: none;">Dokumentation</a> | 
                <a href="#" style="color: #2271b1; text-decoration: none;">Support</a>
            </p>
        </div>
    </div>

    <style>
    .form-table th {
        width: 250px;
        font-weight: 600;
    }
    .form-table td {
        padding: 15px 10px;
    }
    </style>
    <?php
}
