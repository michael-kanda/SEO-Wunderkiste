<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Comment Blocker (Globale Kommentar-Deaktivierung)
 * ------------------------------------------------------------------------- */

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

function seowk_disable_comments_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
}
add_action( 'wp_before_admin_bar_render', 'seowk_disable_comments_admin_bar' );

function seowk_disable_comments_admin_menu() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'seowk_disable_comments_admin_menu' );

function seowk_disable_comments_dashboard() {
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
add_action( 'admin_init', 'seowk_disable_comments_dashboard' );

function seowk_disable_comments_edit_screen() {
    $post_types = get_post_types( array( 'public' => true ), 'names' );
    foreach ( $post_types as $post_type ) {
        remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
        remove_meta_box( 'commentsdiv', $post_type, 'normal' );
        remove_meta_box( 'trackbacksdiv', $post_type, 'normal' );
    }
}
add_action( 'admin_menu', 'seowk_disable_comments_edit_screen' );

function seowk_disable_existing_comments( $open, $post_id ) {
    return false;
}
add_filter( 'comments_open', 'seowk_disable_existing_comments', 20, 2 );
add_filter( 'pings_open', 'seowk_disable_existing_comments', 20, 2 );

function seowk_disable_comments_feed() {
    if ( is_comment_feed() ) {
        wp_die( esc_html__( 'Kommentare sind auf dieser Website deaktiviert.', 'seo-wunderkiste' ), '', array( 'response' => 403 ) );
    }
}
add_action( 'do_feed_rss2_comments', 'seowk_disable_comments_feed', 1 );
add_action( 'do_feed_atom_comments', 'seowk_disable_comments_feed', 1 );

function seowk_remove_comments_feed_link() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );
}
add_action( 'wp_head', 'seowk_remove_comments_feed_link', 1 );

function seowk_disable_comments_widget() {
    unregister_widget( 'WP_Widget_Recent_Comments' );
}
add_action( 'widgets_init', 'seowk_disable_comments_widget' );

function seowk_hide_comments_admin_css() {
    echo '<style>.column-comments { display: none !important; }</style>';
}
add_action( 'admin_head', 'seowk_hide_comments_admin_css' );
