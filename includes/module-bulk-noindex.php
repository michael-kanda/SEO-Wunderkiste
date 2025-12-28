<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Bulk NoIndex Manager
 * Ermöglicht das massenhafte Setzen von NoIndex für Seiten und Beiträge
 * ------------------------------------------------------------------------- */

// 1. Bulk-Aktion zur Dropdown-Liste hinzufügen
function seowk_add_bulk_noindex_actions( $bulk_actions ) {
    $bulk_actions['seowk_set_noindex'] = 'NoIndex setzen (SEO)';
    $bulk_actions['seowk_remove_noindex'] = 'NoIndex entfernen (SEO)';
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-post', 'seowk_add_bulk_noindex_actions' );
add_filter( 'bulk_actions-edit-page', 'seowk_add_bulk_noindex_actions' );

// 2. Bulk-Aktion verarbeiten
function seowk_handle_bulk_noindex( $redirect_to, $action, $post_ids ) {
    
    // NoIndex setzen
    if ( $action === 'seowk_set_noindex' ) {
        foreach ( $post_ids as $post_id ) {
            update_post_meta( $post_id, '_seowk_noindex', '1' );
        }
        $redirect_to = add_query_arg( 'seowk_noindex_set', count( $post_ids ), $redirect_to );
    }
    
    // NoIndex entfernen
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

// 3. Erfolgs-Nachricht anzeigen
function seowk_bulk_noindex_admin_notice() {
    if ( ! empty( $_REQUEST['seowk_noindex_set'] ) ) {
        $count = intval( $_REQUEST['seowk_noindex_set'] );
        printf( 
            '<div class="notice notice-success is-dismissible"><p>NoIndex wurde für %d Einträge gesetzt.</p></div>', 
            $count 
        );
    }
    
    if ( ! empty( $_REQUEST['seowk_noindex_removed'] ) ) {
        $count = intval( $_REQUEST['seowk_noindex_removed'] );
        printf( 
            '<div class="notice notice-success is-dismissible"><p>NoIndex wurde für %d Einträge entfernt.</p></div>', 
            $count 
        );
    }
}
add_action( 'admin_notices', 'seowk_bulk_noindex_admin_notice' );

// 4. Spalte in der Übersicht hinzufügen
function seowk_add_noindex_column( $columns ) {
    $columns['seowk_noindex_status'] = '<span title="NoIndex Status">🔍 NoIndex</span>';
    return $columns;
}
add_filter( 'manage_posts_columns', 'seowk_add_noindex_column' );
add_filter( 'manage_pages_columns', 'seowk_add_noindex_column' );

// 5. Spalten-Inhalt füllen
function seowk_fill_noindex_column( $column_name, $post_id ) {
    if ( 'seowk_noindex_status' === $column_name ) {
        $is_noindex = get_post_meta( $post_id, '_seowk_noindex', true );
        
        if ( $is_noindex ) {
            echo '<span style="color: #d63638; font-weight: bold;" title="Wird von Suchmaschinen nicht indexiert">✗ NoIndex</span>';
        } else {
            echo '<span style="color: #00a32a;" title="Wird von Suchmaschinen indexiert">✓ Index</span>';
        }
    }
}
add_action( 'manage_posts_custom_column', 'seowk_fill_noindex_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'seowk_fill_noindex_column', 10, 2 );

// 6. Meta-Tag im Frontend ausgeben
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

// 7. Quick-Edit Unterstützung (Einzelne Einträge schnell bearbeiten)
function seowk_add_noindex_quick_edit( $column_name, $post_type ) {
    if ( 'seowk_noindex_status' !== $column_name ) {
        return;
    }
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label>
                <input type="checkbox" name="seowk_noindex_quick" value="1">
                <span class="checkbox-title">NoIndex setzen</span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action( 'quick_edit_custom_box', 'seowk_add_noindex_quick_edit', 10, 2 );

// 8. Quick-Edit Wert speichern
function seowk_save_noindex_quick_edit( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['seowk_noindex_quick'] ) ) {
        update_post_meta( $post_id, '_seowk_noindex', '1' );
    } else {
        delete_post_meta( $post_id, '_seowk_noindex' );
    }
}
add_action( 'save_post', 'seowk_save_noindex_quick_edit' );

// 9. JavaScript für Quick-Edit (Wert beim Öffnen anzeigen)
function seowk_noindex_quick_edit_script() {
    global $current_screen;
    
    if ( ! in_array( $current_screen->post_type, array( 'post', 'page' ) ) ) {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $wp_inline_edit = inlineEditPost.edit;
        
        inlineEditPost.edit = function( id ) {
            $wp_inline_edit.apply( this, arguments );
            
            var post_id = 0;
            if ( typeof( id ) == 'object' ) {
                post_id = parseInt( this.getId( id ) );
            }
            
            if ( post_id > 0 ) {
                var $row = $( '#post-' + post_id );
                var $noindex_status = $row.find( '.column-seowk_noindex_status' ).text();
                
                if ( $noindex_status.indexOf('NoIndex') > -1 ) {
                    $( 'input[name="seowk_noindex_quick"]' ).prop( 'checked', true );
                } else {
                    $( 'input[name="seowk_noindex_quick"]' ).prop( 'checked', false );
                }
            }
        };
    });
    </script>
    <?php
}
add_action( 'admin_footer-edit.php', 'seowk_noindex_quick_edit_script' );

// 10. CSS für bessere Darstellung
function seowk_noindex_column_css() {
    echo '<style>
        .column-seowk_noindex_status { 
            width: 100px; 
            text-align: center;
        }
    </style>';
}
add_action( 'admin_head', 'seowk_noindex_column_css' );
