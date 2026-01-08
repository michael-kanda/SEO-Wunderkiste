<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Bulk NoIndex Manager
 * ------------------------------------------------------------------------- */

function seowk_add_bulk_noindex_actions( $bulk_actions ) {
    $bulk_actions['seowk_set_noindex'] = __( 'NoIndex setzen (SEO)', 'seo-wunderkiste' );
    $bulk_actions['seowk_remove_noindex'] = __( 'NoIndex entfernen (SEO)', 'seo-wunderkiste' );
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-post', 'seowk_add_bulk_noindex_actions' );
add_filter( 'bulk_actions-edit-page', 'seowk_add_bulk_noindex_actions' );

function seowk_handle_bulk_noindex( $redirect_to, $action, $post_ids ) {
    if ( $action === 'seowk_set_noindex' ) {
        foreach ( $post_ids as $post_id ) {
            update_post_meta( $post_id, '_seowk_noindex', '1' );
        }
        $redirect_to = add_query_arg( 'seowk_noindex_set', count( $post_ids ), $redirect_to );
    }
    
    if ( $action === 'seowk_remove_noindex' ) {
        foreach ( $post_ids as $post_id ) {
            delete_post_meta( $post_id, '_seowk_noindex' );
        }
        $redirect_to = add_query_arg( 'seowk_noindex_removed', count( $post_ids ), $redirect_to );
    }
    
    return $redirect_to;
}
add_filter( 'handle_bulk_actions-edit-post', 'seowk_handle_bulk_noindex', 10, 3 );
add_filter( 'handle_bulk_actions-edit-page', 'seowk_handle_bulk_noindex', 10, 3 );

function seowk_bulk_noindex_admin_notice() {
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Display only
    if ( ! empty( $_REQUEST['seowk_noindex_set'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $count = intval( $_REQUEST['seowk_noindex_set'] );
        printf( 
            '<div class="notice notice-success is-dismissible"><p>%s</p></div>', 
            /* translators: %d: number of entries */
            sprintf( esc_html__( 'NoIndex wurde für %d Einträge gesetzt.', 'seo-wunderkiste' ), $count )
        );
    }
    
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( ! empty( $_REQUEST['seowk_noindex_removed'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $count = intval( $_REQUEST['seowk_noindex_removed'] );
        printf( 
            '<div class="notice notice-success is-dismissible"><p>%s</p></div>', 
            /* translators: %d: number of entries */
            sprintf( esc_html__( 'NoIndex wurde für %d Einträge entfernt.', 'seo-wunderkiste' ), $count )
        );
    }
}
add_action( 'admin_notices', 'seowk_bulk_noindex_admin_notice' );

function seowk_add_noindex_column( $columns ) {
    $columns['seowk_noindex_status'] = '<span title="NoIndex Status">🔍 NoIndex</span>';
    return $columns;
}
add_filter( 'manage_posts_columns', 'seowk_add_noindex_column' );
add_filter( 'manage_pages_columns', 'seowk_add_noindex_column' );

function seowk_fill_noindex_column( $column_name, $post_id ) {
    if ( 'seowk_noindex_status' === $column_name ) {
        $is_noindex = get_post_meta( $post_id, '_seowk_noindex', true );
        
        if ( $is_noindex ) {
            echo '<span style="color: #d63638; font-weight: bold;" title="' . esc_attr__( 'Wird von Suchmaschinen nicht indexiert', 'seo-wunderkiste' ) . '">✗ NoIndex</span>';
        } else {
            echo '<span style="color: #00a32a;" title="' . esc_attr__( 'Wird von Suchmaschinen indexiert', 'seo-wunderkiste' ) . '">✓ Index</span>';
        }
    }
}
add_action( 'manage_posts_custom_column', 'seowk_fill_noindex_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'seowk_fill_noindex_column', 10, 2 );

function seowk_output_noindex_meta() {
    if ( is_singular() ) {
        $post_id = get_the_ID();
        $is_noindex = get_post_meta( $post_id, '_seowk_noindex', true );
        
        if ( $is_noindex ) {
            echo '<meta name="robots" content="noindex, nofollow">' . "\n";
        }
    }
}
add_action( 'wp_head', 'seowk_output_noindex_meta', 1 );

function seowk_noindex_column_css() {
    echo '<style>.column-seowk_noindex_status { width: 100px; text-align: center; }</style>';
}
add_action( 'admin_head', 'seowk_noindex_column_css' );
