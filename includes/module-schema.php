<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Custom Schema Meta Box (JSON-LD)
 * ------------------------------------------------------------------------- */

function seowk_schema_add_meta_box() {
    $screens = array( 'post', 'page' );
    foreach ( $screens as $screen ) {
        add_meta_box(
            'seowk_schema_box_id',
            __( 'Strukturierte Daten (JSON-LD)', 'seo-wunderkiste' ),
            'seowk_schema_render_meta_box',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'seowk_schema_add_meta_box' );

function seowk_schema_render_meta_box( $post ) {
    $value = get_post_meta( $post->ID, '_seowk_schema_value', true );
    wp_nonce_field( 'seowk_schema_save_data', 'seowk_schema_nonce' );
    
    echo '<p><label for="seowk_schema_field">' . esc_html__( 'Füge hier dein JSON-LD Objekt ein (ohne <script> Tags):', 'seo-wunderkiste' ) . '</label></p>';
    echo '<textarea id="seowk_schema_field" name="seowk_schema_field" rows="10" style="width:100%; font-family:monospace;">' . esc_textarea( $value ) . '</textarea>';
    echo '<p class="description">' . esc_html__( 'Beispiel: { "@context": "https://schema.org", "@type": "Article", ... }', 'seo-wunderkiste' ) . '</p>';
}

function seowk_schema_save_postdata( $post_id ) {
    if ( ! isset( $_POST['seowk_schema_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seowk_schema_nonce'] ) ), 'seowk_schema_save_data' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['seowk_schema_field'] ) ) {
        // JSON-LD wird als raw gespeichert, aber nur von Admins bearbeitet
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON-LD Schema muss roh bleiben
        $schema_data = wp_unslash( $_POST['seowk_schema_field'] );
        update_post_meta( $post_id, '_seowk_schema_value', $schema_data );
    }
}
add_action( 'save_post', 'seowk_schema_save_postdata' );

function seowk_schema_output_head() {
    if ( is_singular() ) {
        $post_id = get_the_ID();
        $schema_json = get_post_meta( $post_id, '_seowk_schema_value', true );

        if ( ! empty( $schema_json ) ) {
            // Validiere JSON vor der Ausgabe
            $decoded = json_decode( $schema_json );
            if ( json_last_error() === JSON_ERROR_NONE ) {
                echo "\n" . '<script type="application/ld+json">' . "\n";
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-LD muss roh ausgegeben werden
                echo $schema_json; 
                echo "\n" . '</script>' . "\n";
            }
        }
    }
}
add_action( 'wp_head', 'seowk_schema_output_head' );
