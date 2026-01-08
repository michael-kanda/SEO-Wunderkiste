<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Comment Blocker (Globale Kommentar-Deaktivierung)
 * Deaktiviert Kommentare komplett oder nur für neue Inhalte
 * ------------------------------------------------------------------------- */

// 1. Kommentare für neue Beiträge/Seiten standardmäßig geschlossen
function seowk_disable_comments_post_types_support() {
    $post_types = get_post_types( array( 'public' => true ), 'names' );
    
    foreach ( $post_types as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
}
add_action( 'init', 'seowk_disable_comments_post_types_support' );

// 2. Kommentare in der Admin-Bar verstecken
function seowk_disable_comments_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
}
add_action( 'wp_before_admin_bar_render', 'seowk_disable_comments_admin_bar' );

// 3. Kommentar-Menü aus dem Dashboard entfernen
function seowk_disable_comments_admin_menu() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'seowk_disable_comments_admin_menu' );

// 4. Kommentar-Meta-Boxen aus dem Editor entfernen
function seowk_disable_comments_dashboard() {
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
add_action( 'admin_init', 'seowk_disable_comments_dashboard' );

// 5. Kommentar-Support aus dem Editor entfernen
function seowk_disable_comments_edit_screen() {
    $post_types = get_post_types( array( 'public' => true ), 'names' );
    
    foreach ( $post_types as $post_type ) {
        remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
        remove_meta_box( 'commentsdiv', $post_type, 'normal' );
        remove_meta_box( 'trackbacksdiv', $post_type, 'normal' );
    }
}
add_action( 'admin_menu', 'seowk_disable_comments_edit_screen' );

// 6. Bestehende Kommentare schließen (Status auf "closed" setzen)
function seowk_disable_existing_comments( $open, $post_id ) {
    return false;
}
add_filter( 'comments_open', 'seowk_disable_existing_comments', 20, 2 );
add_filter( 'pings_open', 'seowk_disable_existing_comments', 20, 2 );

// 7. Kommentar-Feed deaktivieren
function seowk_disable_comments_feed() {
    if ( is_comment_feed() ) {
        wp_die( 
            esc_html__( 'Kommentare sind auf dieser Website deaktiviert.', 'seo-wunderkiste' ), 
            esc_html__( 'Kommentare deaktiviert', 'seo-wunderkiste' ), 
            array( 'response' => 403 ) 
        );
    }
}
add_action( 'do_feed_rss2_comments', 'seowk_disable_comments_feed', 1 );
add_action( 'do_feed_atom_comments', 'seowk_disable_comments_feed', 1 );

// 8. Kommentar-Links aus dem Header entfernen
function seowk_remove_comments_feed_link() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );
}
add_action( 'wp_head', 'seowk_remove_comments_feed_link', 1 );

// 9. Widget "Letzte Kommentare" ausblenden
function seowk_disable_comments_widget() {
    unregister_widget( 'WP_Widget_Recent_Comments' );
}
add_action( 'widgets_init', 'seowk_disable_comments_widget' );

// 10. CSS: Kommentar-bezogene Elemente im Admin ausblenden
function seowk_hide_comments_admin_css() {
    echo '<style>
        .column-comments { display: none !important; }
    </style>';
}
add_action( 'admin_head', 'seowk_hide_comments_admin_css' );

// 11. Bulk-Aktion: Alle Kommentare schließen
function seowk_add_bulk_close_comments_action( $bulk_actions ) {
    $bulk_actions['seowk_close_comments'] = __( 'Kommentare schließen', 'seo-wunderkiste' );
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-post', 'seowk_add_bulk_close_comments_action' );
add_filter( 'bulk_actions-edit-page', 'seowk_add_bulk_close_comments_action' );

function seowk_handle_bulk_close_comments( $redirect_to, $action, $post_ids ) {
    if ( $action === 'seowk_close_comments' ) {
        foreach ( $post_ids as $post_id ) {
            $post_data = array(
                'ID' => $post_id,
                'comment_status' => 'closed',
                'ping_status' => 'closed'
            );
            wp_update_post( $post_data );
        }
        $redirect_to = add_query_arg( 'seowk_comments_closed', count( $post_ids ), $redirect_to );
    }
    return $redirect_to;
}
add_filter( 'handle_bulk_actions-edit-post', 'seowk_handle_bulk_close_comments', 10, 3 );
add_filter( 'handle_bulk_actions-edit-page', 'seowk_handle_bulk_close_comments', 10, 3 );

// 12. Erfolgs-Nachricht für Bulk-Aktion
function seowk_bulk_close_comments_notice() {
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nur Anzeige einer Erfolgsmeldung
    if ( ! empty( $_REQUEST['seowk_comments_closed'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $count = intval( $_REQUEST['seowk_comments_closed'] );
        printf( 
            '<div class="notice notice-success is-dismissible"><p>%s</p></div>', 
            /* translators: %d: number of entries */
            sprintf( esc_html__( 'Kommentare wurden für %d Einträge geschlossen.', 'seo-wunderkiste' ), $count )
        );
    }
}
add_action( 'admin_notices', 'seowk_bulk_close_comments_notice' );

// 13. OPTIONAL: Alle bestehenden Kommentare in der DB schließen (einmalig)
// Diese Funktion wird NICHT automatisch ausgeführt!
function seowk_close_all_existing_comments() {
    global $wpdb;
    
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Einmalige Admin-Funktion
    $wpdb->query( "UPDATE {$wpdb->posts} SET comment_status = 'closed', ping_status = 'closed' WHERE comment_status = 'open'" );
    
    return __( 'Alle bestehenden Kommentare wurden geschlossen.', 'seo-wunderkiste' );
}
