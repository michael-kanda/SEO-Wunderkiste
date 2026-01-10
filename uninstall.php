<?php
/**
 * SEO Wunderkiste Uninstall
 *
 * Wird ausgeführt, wenn das Plugin über das WordPress-Admin deinstalliert wird.
 * Entfernt alle Plugin-Optionen und Post-Meta-Daten aus der Datenbank.
 *
 * @package SEO_Wunderkiste
 * @since 2.8
 */

// Sicherheitscheck: Nur ausführen, wenn von WordPress aufgerufen
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/* ------------------------------------------------------------------------- *
 * PLUGIN-OPTIONEN LÖSCHEN
 * ------------------------------------------------------------------------- */

delete_option( 'seowk_settings' );

// Multisite: Optionen für alle Sites löschen
if ( is_multisite() ) {
    global $wpdb;
    
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
    
    foreach ( $blog_ids as $blog_id ) {
        switch_to_blog( $blog_id );
        delete_option( 'seowk_settings' );
        restore_current_blog();
    }
}

/* ------------------------------------------------------------------------- *
 * POST META LÖSCHEN
 * ------------------------------------------------------------------------- */

global $wpdb;

// Liste aller vom Plugin erstellten Post-Meta-Keys
$meta_keys = array(
    // SEO Meta Settings
    '_seowk_meta_title',
    '_seowk_meta_description',
    '_seowk_meta_robots',
    '_seowk_og_title',
    '_seowk_og_description',
    '_seowk_og_image',
    
    // Schema
    '_seowk_schema_value',
    
    // NoIndex
    '_seowk_noindex',
    
    // Conversion Tracker
    '_seowk_ga4_conversion_enabled',
    '_seowk_ga4_conversion_event',
    '_seowk_ga4_conversion_value',
    '_seowk_ads_conversion_enabled',
    '_seowk_ads_conversion_id',
    '_seowk_ads_conversion_label',
    '_seowk_ads_conversion_value',
);

// Meta-Daten löschen
foreach ( $meta_keys as $meta_key ) {
    $wpdb->delete( 
        $wpdb->postmeta, 
        array( 'meta_key' => $meta_key ), 
        array( '%s' ) 
    );
}

/* ------------------------------------------------------------------------- *
 * USER META LÖSCHEN
 * ------------------------------------------------------------------------- */

$user_meta_keys = array(
    'seowk_svg_notice_dismissed',
);

foreach ( $user_meta_keys as $user_meta_key ) {
    $wpdb->delete( 
        $wpdb->usermeta, 
        array( 'meta_key' => $user_meta_key ), 
        array( '%s' ) 
    );
}

/* ------------------------------------------------------------------------- *
 * TRANSIENTS LÖSCHEN
 * ------------------------------------------------------------------------- */

delete_transient( 'seowk_activation_notice' );

/* ------------------------------------------------------------------------- *
 * CACHE LEEREN
 * ------------------------------------------------------------------------- */

wp_cache_flush();
