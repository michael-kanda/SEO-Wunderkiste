<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Conversion Tracker
 * Fügt Conversion-Tracking für GA4 und Google Ads auf einzelnen Seiten hinzu
 * Perfekt für: Danke-Seiten, Bestätigungsseiten, Download-Seiten, etc.
 * ------------------------------------------------------------------------- */

// 1. Meta-Box im Editor hinzufügen (Posts und Pages)
function seowk_conversion_add_meta_box() {
    $screens = array( 'post', 'page' );
    
    foreach ( $screens as $screen ) {
        add_meta_box(
            'seowk_conversion_box',
            '🎯 Conversion Tracking',
            'seowk_conversion_render_meta_box',
            $screen,
            'side',  // Sidebar (rechts)
            'high'   // Hohe Priorität
        );
    }
}
add_action( 'add_meta_boxes', 'seowk_conversion_add_meta_box' );

// 2. Meta-Box HTML ausgeben
function seowk_conversion_render_meta_box( $post ) {
    // Nonce für Sicherheit
    wp_nonce_field( 'seowk_conversion_save', 'seowk_conversion_nonce' );
    
    // Gespeicherte Werte abrufen
    $ga4_enabled = get_post_meta( $post->ID, '_seowk_ga4_conversion_enabled', true );
    $ga4_event = get_post_meta( $post->ID, '_seowk_ga4_conversion_event', true );
    $ga4_value = get_post_meta( $post->ID, '_seowk_ga4_conversion_value', true );
    
    $ads_enabled = get_post_meta( $post->ID, '_seowk_ads_conversion_enabled', true );
    $ads_id = get_post_meta( $post->ID, '_seowk_ads_conversion_id', true );
    $ads_label = get_post_meta( $post->ID, '_seowk_ads_conversion_label', true );
    $ads_value = get_post_meta( $post->ID, '_seowk_ads_conversion_value', true );
    
    ?>
    <div style="padding: 10px 0;">
        
        <!-- GA4 CONVERSION -->
        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd;">
            <h4 style="margin: 0 0 10px 0; color: #4285f4;">
                📊 Google Analytics 4
            </h4>
            
            <label style="display: block; margin-bottom: 10px;">
                <input type="checkbox" name="seowk_ga4_conversion_enabled" value="1" <?php checked( 1, $ga4_enabled ); ?>>
                <strong>GA4 Conversion aktivieren</strong>
            </label>
            
            <div id="ga4-fields" style="<?php echo $ga4_enabled ? '' : 'display:none;'; ?>">
                <p style="margin: 10px 0 5px 0;">
                    <label style="font-weight: 600; display: block; margin-bottom: 3px;">
                        Event Name:
                    </label>
                    <input type="text" name="seowk_ga4_conversion_event" value="<?php echo esc_attr( $ga4_event ); ?>" placeholder="purchase, conversion, lead" style="width: 100%;" />
                    <span style="font-size: 11px; color: #666;">z.B.: purchase, conversion, form_submit</span>
                </p>
                
                <p style="margin: 10px 0 5px 0;">
                    <label style="font-weight: 600; display: block; margin-bottom: 3px;">
                        Conversion Value (optional):
                    </label>
                    <input type="number" step="0.01" name="seowk_ga4_conversion_value" value="<?php echo esc_attr( $ga4_value ); ?>" placeholder="49.99" style="width: 100%;" />
                    <span style="font-size: 11px; color: #666;">Wert in Euro (leer = kein Wert)</span>
                </p>
            </div>
        </div>
        
        <!-- GOOGLE ADS CONVERSION -->
        <div style="margin-bottom: 10px;">
            <h4 style="margin: 0 0 10px 0; color: #34a853;">
                💰 Google Ads
            </h4>
            
            <label style="display: block; margin-bottom: 10px;">
                <input type="checkbox" name="seowk_ads_conversion_enabled" value="1" <?php checked( 1, $ads_enabled ); ?>>
                <strong>Google Ads Conversion aktivieren</strong>
            </label>
            
            <div id="ads-fields" style="<?php echo $ads_enabled ? '' : 'display:none;'; ?>">
                <p style="margin: 10px 0 5px 0;">
                    <label style="font-weight: 600; display: block; margin-bottom: 3px;">
                        Conversion ID:
                    </label>
                    <input type="text" name="seowk_ads_conversion_id" value="<?php echo esc_attr( $ads_id ); ?>" placeholder="AW-123456789" style="width: 100%;" />
                    <span style="font-size: 11px; color: #666;">Format: AW-123456789</span>
                </p>
                
                <p style="margin: 10px 0 5px 0;">
                    <label style="font-weight: 600; display: block; margin-bottom: 3px;">
                        Conversion Label:
                    </label>
                    <input type="text" name="seowk_ads_conversion_label" value="<?php echo esc_attr( $ads_label ); ?>" placeholder="abc123def456" style="width: 100%;" />
                    <span style="font-size: 11px; color: #666;">Aus Google Ads Conversion-Tag</span>
                </p>
                
                <p style="margin: 10px 0 5px 0;">
                    <label style="font-weight: 600; display: block; margin-bottom: 3px;">
                        Conversion Value (optional):
                    </label>
                    <input type="number" step="0.01" name="seowk_ads_conversion_value" value="<?php echo esc_attr( $ads_value ); ?>" placeholder="49.99" style="width: 100%;" />
                    <span style="font-size: 11px; color: #666;">Wert in Euro (leer = kein Wert)</span>
                </p>
            </div>
        </div>
        
        <!-- INFO BOX -->
        <div style="background: #f0f6fc; border-left: 3px solid #2271b1; padding: 10px; margin-top: 15px; font-size: 12px;">
            <strong>💡 Tipp:</strong> Aktiviere dies auf Danke-Seiten, Bestätigungsseiten oder Download-Seiten nach Formular-Absendung.
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Toggle GA4 Felder
        $('input[name="seowk_ga4_conversion_enabled"]').on('change', function() {
            $('#ga4-fields').toggle(this.checked);
        });
        
        // Toggle Ads Felder
        $('input[name="seowk_ads_conversion_enabled"]').on('change', function() {
            $('#ads-fields').toggle(this.checked);
        });
    });
    </script>
    <?php
}

// 3. Meta-Daten speichern
function seowk_conversion_save_meta_data( $post_id ) {
    // Security Checks
    if ( ! isset( $_POST['seowk_conversion_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['seowk_conversion_nonce'], 'seowk_conversion_save' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // GA4 Daten speichern
    if ( isset( $_POST['seowk_ga4_conversion_enabled'] ) ) {
        update_post_meta( $post_id, '_seowk_ga4_conversion_enabled', 1 );
    } else {
        delete_post_meta( $post_id, '_seowk_ga4_conversion_enabled' );
    }
    
    if ( isset( $_POST['seowk_ga4_conversion_event'] ) ) {
        update_post_meta( $post_id, '_seowk_ga4_conversion_event', sanitize_text_field( $_POST['seowk_ga4_conversion_event'] ) );
    }
    
    if ( isset( $_POST['seowk_ga4_conversion_value'] ) ) {
        update_post_meta( $post_id, '_seowk_ga4_conversion_value', sanitize_text_field( $_POST['seowk_ga4_conversion_value'] ) );
    }
    
    // Google Ads Daten speichern
    if ( isset( $_POST['seowk_ads_conversion_enabled'] ) ) {
        update_post_meta( $post_id, '_seowk_ads_conversion_enabled', 1 );
    } else {
        delete_post_meta( $post_id, '_seowk_ads_conversion_enabled' );
    }
    
    if ( isset( $_POST['seowk_ads_conversion_id'] ) ) {
        update_post_meta( $post_id, '_seowk_ads_conversion_id', sanitize_text_field( $_POST['seowk_ads_conversion_id'] ) );
    }
    
    if ( isset( $_POST['seowk_ads_conversion_label'] ) ) {
        update_post_meta( $post_id, '_seowk_ads_conversion_label', sanitize_text_field( $_POST['seowk_ads_conversion_label'] ) );
    }
    
    if ( isset( $_POST['seowk_ads_conversion_value'] ) ) {
        update_post_meta( $post_id, '_seowk_ads_conversion_value', sanitize_text_field( $_POST['seowk_ads_conversion_value'] ) );
    }
}
add_action( 'save_post', 'seowk_conversion_save_meta_data' );

// 4. Tracking-Codes im Frontend ausgeben
function seowk_conversion_output_tracking() {
    // Nur auf einzelnen Seiten/Posts
    if ( ! is_singular() ) {
        return;
    }
    
    $post_id = get_the_ID();
    
    // GA4 Conversion Tracking
    $ga4_enabled = get_post_meta( $post_id, '_seowk_ga4_conversion_enabled', true );
    
    if ( $ga4_enabled ) {
        $event_name = get_post_meta( $post_id, '_seowk_ga4_conversion_event', true );
        $event_value = get_post_meta( $post_id, '_seowk_ga4_conversion_value', true );
        
        if ( ! empty( $event_name ) ) {
            ?>
            <!-- GA4 Conversion Tracking by SEO Wunderkiste -->
            <script>
            window.addEventListener('load', function() {
                if (typeof gtag === 'function') {
                    <?php if ( ! empty( $event_value ) ) : ?>
                    gtag('event', '<?php echo esc_js( $event_name ); ?>', {
                        'value': <?php echo floatval( $event_value ); ?>,
                        'currency': 'EUR',
                        'page_location': window.location.href
                    });
                    <?php else : ?>
                    gtag('event', '<?php echo esc_js( $event_name ); ?>', {
                        'page_location': window.location.href
                    });
                    <?php endif; ?>
                    console.log('✅ GA4 Conversion Event fired: <?php echo esc_js( $event_name ); ?>');
                } else {
                    console.warn('⚠️ gtag() not found. Install GA4 first!');
                }
            });
            </script>
            <?php
        }
    }
    
    // Google Ads Conversion Tracking
    $ads_enabled = get_post_meta( $post_id, '_seowk_ads_conversion_enabled', true );
    
    if ( $ads_enabled ) {
        $conversion_id = get_post_meta( $post_id, '_seowk_ads_conversion_id', true );
        $conversion_label = get_post_meta( $post_id, '_seowk_ads_conversion_label', true );
        $conversion_value = get_post_meta( $post_id, '_seowk_ads_conversion_value', true );
        
        if ( ! empty( $conversion_id ) && ! empty( $conversion_label ) ) {
            ?>
            <!-- Google Ads Conversion Tracking by SEO Wunderkiste -->
            <script>
            window.addEventListener('load', function() {
                if (typeof gtag === 'function') {
                    gtag('event', 'conversion', {
                        'send_to': '<?php echo esc_js( $conversion_id ); ?>/<?php echo esc_js( $conversion_label ); ?>',
                        <?php if ( ! empty( $conversion_value ) ) : ?>
                        'value': <?php echo floatval( $conversion_value ); ?>,
                        'currency': 'EUR',
                        <?php endif; ?>
                        'transaction_id': ''
                    });
                    console.log('✅ Google Ads Conversion fired: <?php echo esc_js( $conversion_id ); ?>');
                } else {
                    console.warn('⚠️ gtag() not found. Install Google Ads Tag first!');
                }
            });
            </script>
            <?php
        }
    }
}
add_action( 'wp_footer', 'seowk_conversion_output_tracking', 999 );

// 5. Admin-Spalte: Zeigt an, welche Seiten Conversion-Tracking haben
function seowk_conversion_add_admin_column( $columns ) {
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        
        // Nach Titel-Spalte einfügen
        if ( $key === 'title' ) {
            $new_columns['seowk_conversion'] = '🎯 Tracking';
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_posts_columns', 'seowk_conversion_add_admin_column' );
add_filter( 'manage_pages_columns', 'seowk_conversion_add_admin_column' );

// 6. Admin-Spalte füllen
function seowk_conversion_fill_admin_column( $column_name, $post_id ) {
    if ( 'seowk_conversion' !== $column_name ) {
        return;
    }
    
    $ga4_enabled = get_post_meta( $post_id, '_seowk_ga4_conversion_enabled', true );
    $ads_enabled = get_post_meta( $post_id, '_seowk_ads_conversion_enabled', true );
    
    $output = array();
    
    if ( $ga4_enabled ) {
        $output[] = '<span style="color: #4285f4; font-weight: 600;" title="GA4 Conversion aktiv">GA4</span>';
    }
    
    if ( $ads_enabled ) {
        $output[] = '<span style="color: #34a853; font-weight: 600;" title="Google Ads Conversion aktiv">Ads</span>';
    }
    
    if ( empty( $output ) ) {
        echo '<span style="color: #ccc;">—</span>';
    } else {
        echo implode( ' + ', $output );
    }
}
add_action( 'manage_posts_custom_column', 'seowk_conversion_fill_admin_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'seowk_conversion_fill_admin_column', 10, 2 );

// 7. CSS für Admin-Spalte
function seowk_conversion_admin_css() {
    echo '<style>
        .column-seowk_conversion { 
            width: 80px; 
            text-align: center;
        }
    </style>';
}
add_action( 'admin_head', 'seowk_conversion_admin_css' );
