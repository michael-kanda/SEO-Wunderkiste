<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Image Resizer 800px / 1200px
 * ------------------------------------------------------------------------- */

function ir800_add_resize_button( $form_fields, $post ) {
    if ( ! wp_attachment_is_image( $post->ID ) ) { return $form_fields; }
    $metadata = wp_get_attachment_metadata( $post->ID );
    $current_size = $metadata && isset( $metadata['width'] ) ? '<span style="color:#666; font-size:12px;">Aktuell: ' . $metadata['width'] . ' × ' . $metadata['height'] . ' px</span><br>' : '';
    $form_fields['ir800_resize'] = array(
        'label' => __( 'Skalierung', 'seo-wunderkiste' ),
        'input' => 'html',
        'html'  => $current_size . '<div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post->ID . '" data-size="800" data-nonce="' . wp_create_nonce('ir800_resize_' . $post->ID) . '">800px</button>
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post->ID . '" data-size="1200" data-nonce="' . wp_create_nonce('ir800_resize_' . $post->ID) . '">1200px</button>
        </div><p class="description">Überschreibt das Originalbild (92% Qualität).</p><span class="ir800-status" style="color: #2271b1; font-weight: bold; display: none;"></span>'
    );
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'ir800_add_resize_button', 10, 2 );

function ir800_admin_footer_script() {
    ?>
    <script>
    jQuery(document).on('click', '.ir800-trigger', function(e) {
        e.preventDefault();
        var btn = jQuery(this), container = btn.closest('.ir800-container, td, .setting'), status = container.find('.ir800-status');
        var id = btn.data('id'), size = btn.data('size') || 800, nonce = btn.data('nonce');
        if(!confirm('Bild auf ' + size + 'px verkleinern? Original wird überschrieben.')) return;
        container.find('.ir800-trigger').prop('disabled', true);
        status.text('Arbeite...').css('color', '#2271b1').show();
        jQuery.post(ajaxurl, { action: 'ir800_resize_image', attachment_id: id, target_size: size, security: nonce }, function(r) {
            if (r.success) { status.text('✓ ' + r.data).css('color', 'green'); }
            else { status.text('✗ ' + (r.data || 'Fehler')).css('color', 'red'); container.find('.ir800-trigger').prop('disabled', false); }
        });
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'ir800_admin_footer_script' );

function ir800_ajax_resize_image() {
    // Validierung
    if ( ! isset( $_POST['attachment_id'] ) || ! isset( $_POST['security'] ) ) {
        wp_send_json_error( __( 'Ungültige Anfrage.', 'seo-wunderkiste' ) );
    }
    
    $attachment_id = intval( $_POST['attachment_id'] );
    $target_size = isset( $_POST['target_size'] ) ? intval( $_POST['target_size'] ) : 800;
    if ( ! in_array( $target_size, array( 800, 1200 ), true ) ) { $target_size = 800; }
    
    // Nonce prüfen
    check_ajax_referer( 'ir800_resize_' . $attachment_id, 'security' );
    
    // Berechtigungen prüfen
    if ( ! current_user_can( 'upload_files' ) ) { 
        wp_send_json_error( __( 'Keine Berechtigung.', 'seo-wunderkiste' ) ); 
    }
    $path = get_attached_file( $attachment_id );
    if ( ! $path || ! file_exists( $path ) ) { wp_send_json_error( 'Datei nicht gefunden.' ); }
    $editor = wp_get_image_editor( $path );
    if ( is_wp_error( $editor ) ) { wp_send_json_error( 'Bildfehler.' ); }
    $editor->set_quality( 92 );
    $size = $editor->get_size();
    if ( $size['width'] <= $target_size && $size['height'] <= $target_size ) { wp_send_json_error( 'Bereits ' . $target_size . 'px oder kleiner.' ); }
    $resized = $editor->resize( $target_size, $target_size, false );
    if ( is_wp_error( $resized ) ) { wp_send_json_error( 'Resize-Fehler.' ); }
    $saved = $editor->save( $path );
    if ( is_wp_error( $saved ) ) { wp_send_json_error( 'Speicherfehler.' ); }
    $metadata = wp_generate_attachment_metadata( $attachment_id, $path );
    wp_update_attachment_metadata( $attachment_id, $metadata );
    $new_size = $editor->get_size();
    wp_send_json_success( $new_size['width'] . '×' . $new_size['height'] . 'px' );
}
add_action( 'wp_ajax_ir800_resize_image', 'ir800_ajax_resize_image' );

function ir800_add_list_column( $columns ) { $columns['ir800_action'] = 'Resizer'; return $columns; }
add_filter( 'manage_upload_columns', 'ir800_add_list_column' );

function ir800_fill_list_column( $column_name, $post_id ) {
    if ( 'ir800_action' !== $column_name ) return;
    if ( wp_attachment_is_image( $post_id ) ) {
        $nonce = wp_create_nonce('ir800_resize_' . $post_id);
        echo '<div class="ir800-container" style="display: flex; gap: 4px;">
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post_id . '" data-size="800" data-nonce="' . $nonce . '">800</button>
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post_id . '" data-size="1200" data-nonce="' . $nonce . '">1200</button>
        </div><span class="ir800-status" style="display:block; font-size:11px; margin-top:2px;"></span>';
    }
}
add_action( 'manage_media_custom_column', 'ir800_fill_list_column', 10, 2 );

function ir800_list_css() { echo '<style>.column-ir800_action { width: 120px; } .ir800-container .button { min-width: 45px; padding: 0 8px; }</style>'; }
add_action('admin_head', 'ir800_list_css');
