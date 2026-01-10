<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Login Türsteher
 * ------------------------------------------------------------------------- */

function seowk_secret_login_parameter() {
    $options = get_option( 'seowk_settings' );
    $secret_key = ! empty( $options['seowk_login_protection_key'] ) ? trim( $options['seowk_login_protection_key'] ) : 'hintereingang';
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
    
    if ( strpos( $request_uri, 'wp-login.php' ) !== false && ! is_user_logged_in() ) {
        if ( ! isset( $_GET[ $secret_key ] ) ) {
            wp_safe_redirect( home_url() );
            exit;
        }
    }
}
add_action( 'init', 'seowk_secret_login_parameter' );
