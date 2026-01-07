<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Image Resizer 800px / 1200px
 * Version: 2.3 - Mit 800px und 1200px Option
 * ------------------------------------------------------------------------- */

// 1. Button im "Attachment Details" Modal (Einzelansicht)
function ir800_add_resize_button( $form_fields, $post ) {
    if ( ! wp_attachment_is_image( $post->ID ) ) {
        return $form_fields;
    }

    // Aktuelle Bildgröße ermitteln
    $metadata = wp_get_attachment_metadata( $post->ID );
    $current_size = '';
    if ( $metadata && isset( $metadata['width'] ) && isset( $metadata['height'] ) ) {
        $current_size = '<span style="color:#666; font-size:12px;">Aktuell: ' . $metadata['width'] . ' × ' . $metadata['height'] . ' px</span><br>';
    }

    $form_fields['ir800_resize'] = array(
        'label' => 'Skalierung',
        'input' => 'html',
        'html'  => '
            ' . $current_size . '
            <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                <button type="button" class="button button-small ir800-trigger" data-id="' . $post->ID . '" data-size="800" data-nonce="' . wp_create_nonce('ir800_resize_' . $post->ID) . '">
                    800px
                </button>
                <button type="button" class="button button-small ir800-trigger" data-id="' . $post->ID . '" data-size="1200" data-nonce="' . wp_create_nonce('ir800_resize_' . $post->ID) . '">
                    1200px
                </button>
            </div>
            <p class="description" style="margin-top:5px;">Überschreibt das Originalbild (92% Qualität).</p>
            <span class="ir800-status" style="color: #2271b1; font-weight: bold; display: none;"></span>
        ',
    );
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'ir800_add_resize_button', 10, 2 );

// 2. JavaScript (Funktioniert für Modal UND Listenansicht)
function ir800_admin_footer_script() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('click', '.ir800-trigger', function(e) {
            e.preventDefault();
            var button = $(this);
            var container = button.closest('.ir800-container, td, .setting');
            var status = container.find('.ir800-status').length ? container.find('.ir800-status') : button.siblings('.ir800-status');
            var attachmentId = button.data('id');
            var targetSize = button.data('size') || 800;
            var nonce = button.data('nonce');

            if(!confirm('Möchtest du dieses Bild wirklich permanent auf ' + targetSize + 'px verkleinern? Das Original wird überschrieben.')) { return; }

            // Alle Buttons in diesem Container deaktivieren
            container.find('.ir800-trigger').prop('disabled', true);
            status.text('Arbeite...').css('color', '#2271b1').show();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: { 
                    action: 'ir800_resize_image', 
                    attachment_id: attachmentId, 
                    target_size: targetSize,
                    security: nonce 
                },
                success: function(response) {
                    if (response.success) {
                        status.text('✓ ' + response.data).css('color', 'green');
                    } else {
                        status.text('✗ ' + (response.data || 'Unbekannt')).css('color', 'red');
                        container.find('.ir800-trigger').prop('disabled', false);
                    }
                },
                error: function() {
                    status.text('✗ Server Fehler.').css('color', 'red');
                    container.find('.ir800-trigger').prop('disabled', false);
                }
            });
        });
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'ir800_admin_footer_script' );

// 3. PHP Logik (AJAX Handler) - MIT VERBESSERTER QUALITÄT
function ir800_ajax_resize_image() {
    $attachment_id = intval( $_POST['attachment_id'] );
    $target_size = isset( $_POST['target_size'] ) ? intval( $_POST['target_size'] ) : 800;
    
    // Erlaubte Größen validieren
    $allowed_sizes = array( 800, 1200 );
    if ( ! in_array( $target_size, $allowed_sizes ) ) {
        $target_size = 800;
    }
    
    check_ajax_referer( 'ir800_resize_' . $attachment_id, 'security' );

    if ( ! current_user_can( 'upload_files' ) ) { 
        wp_send_json_error( 'Keine Berechtigung.' ); 
    }

    $path = get_attached_file( $attachment_id );
    if ( ! $path || ! file_exists( $path ) ) { 
        wp_send_json_error( 'Datei nicht gefunden.' ); 
    }

    $editor = wp_get_image_editor( $path );
    if ( is_wp_error( $editor ) ) { 
        wp_send_json_error( 'Bildfehler.' ); 
    }

    // QUALITÄT SETZEN - 92 für hohe Qualität bei akzeptabler Dateigröße
    $editor->set_quality( 92 );

    $size = $editor->get_size();
    if ( $size['width'] <= $target_size && $size['height'] <= $target_size ) { 
        wp_send_json_error( 'Bereits ' . $target_size . 'px oder kleiner.' ); 
    }

    $resized = $editor->resize( $target_size, $target_size, false );
    if ( is_wp_error( $resized ) ) { 
        wp_send_json_error( 'Resize-Fehler.' ); 
    }

    $saved = $editor->save( $path );
    if ( is_wp_error( $saved ) ) { 
        wp_send_json_error( 'Speicherfehler.' ); 
    }

    // Metadaten aktualisieren (wichtig für korrekte Anzeige in Mediathek)
    $metadata = wp_generate_attachment_metadata( $attachment_id, $path );
    wp_update_attachment_metadata( $attachment_id, $metadata );

    // Neue Größe für Feedback ermitteln
    $new_size = $editor->get_size();
    $feedback = $new_size['width'] . '×' . $new_size['height'] . 'px (92% Qual.)';

    wp_send_json_success( $feedback );
}
add_action( 'wp_ajax_ir800_resize_image', 'ir800_ajax_resize_image' );


/* ------------------------------------------------------------------------- *
 * 4. Spalte in der Listenansicht
 * ------------------------------------------------------------------------- */

// Spalten-Überschrift hinzufügen
function ir800_add_list_column( $columns ) {
    $columns['ir800_action'] = 'Resizer';
    return $columns;
}
add_filter( 'manage_upload_columns', 'ir800_add_list_column' );

// Spalten-Inhalt (Buttons)
function ir800_fill_list_column( $column_name, $post_id ) {
    if ( 'ir800_action' !== $column_name ) {
        return;
    }
    
    if ( wp_attachment_is_image( $post_id ) ) {
        $nonce = wp_create_nonce('ir800_resize_' . $post_id);
        echo '<div class="ir800-container" style="display: flex; gap: 4px; flex-wrap: wrap;">';
        echo '<button type="button" class="button button-small ir800-trigger" data-id="' . $post_id . '" data-size="800" data-nonce="' . $nonce . '" title="Auf 800px skalieren">800</button>';
        echo '<button type="button" class="button button-small ir800-trigger" data-id="' . $post_id . '" data-size="1200" data-nonce="' . $nonce . '" title="Auf 1200px skalieren">1200</button>';
        echo '</div>';
        echo '<span class="ir800-status" style="display:block; font-size:11px; margin-top:2px;"></span>';
    }
}
add_action( 'manage_media_custom_column', 'ir800_fill_list_column', 10, 2 );

// CSS für Spaltenbreite
function ir800_list_css() {
    echo '<style>
        .column-ir800_action { width: 120px; }
        .ir800-container .button { min-width: 45px; padding: 0 8px; }
    </style>';
}
add_action('admin_head', 'ir800_list_css');
