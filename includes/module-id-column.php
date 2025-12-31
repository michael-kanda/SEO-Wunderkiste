<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: ID Column Display
 * Zeigt die Post/Page/Media ID in allen Übersichten an
 * ------------------------------------------------------------------------- */

// 1. Spalte zu allen relevanten Post-Types hinzufügen
function seowk_add_id_column( $columns ) {
    // Füge die ID-Spalte direkt nach der Checkbox ein
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        
        // Nach der Checkbox-Spalte einfügen
        if ( $key === 'cb' ) {
            $new_columns['seowk_id'] = 'ID';
        }
    }
    
    return $new_columns;
}

// Für Beiträge
add_filter( 'manage_posts_columns', 'seowk_add_id_column' );

// Für Seiten
add_filter( 'manage_pages_columns', 'seowk_add_id_column' );

// Für Medien
add_filter( 'manage_media_columns', 'seowk_add_id_column' );

// Für Custom Post Types (dynamisch)
function seowk_add_id_column_to_cpts() {
    $post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );
    
    foreach ( $post_types as $post_type ) {
        add_filter( "manage_{$post_type}_posts_columns", 'seowk_add_id_column' );
    }
}
add_action( 'admin_init', 'seowk_add_id_column_to_cpts' );

// 2. Spalten-Inhalt füllen (für Posts und Pages)
function seowk_fill_id_column( $column_name, $post_id ) {
    if ( 'seowk_id' === $column_name ) {
        echo '<strong style="color: #2271b1;">' . $post_id . '</strong>';
    }
}
add_action( 'manage_posts_custom_column', 'seowk_fill_id_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'seowk_fill_id_column', 10, 2 );

// 3. Spalten-Inhalt für Medien
function seowk_fill_media_id_column( $column_name, $post_id ) {
    if ( 'seowk_id' === $column_name ) {
        echo '<strong style="color: #2271b1;">' . $post_id . '</strong>';
    }
}
add_action( 'manage_media_custom_column', 'seowk_fill_media_id_column', 10, 2 );

// 4. Spalten-Inhalt für Custom Post Types
function seowk_fill_cpt_id_column( $column_name, $post_id ) {
    if ( 'seowk_id' === $column_name ) {
        echo '<strong style="color: #2271b1;">' . $post_id . '</strong>';
    }
}
add_action( 'manage_posts_custom_column', 'seowk_fill_cpt_id_column', 10, 2 );

// 5. Spalte sortierbar machen
function seowk_make_id_column_sortable( $columns ) {
    $columns['seowk_id'] = 'ID';
    return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'seowk_make_id_column_sortable' );
add_filter( 'manage_edit-page_sortable_columns', 'seowk_make_id_column_sortable' );
add_filter( 'manage_upload_sortable_columns', 'seowk_make_id_column_sortable' );

// 6. Sortierung implementieren
function seowk_id_column_orderby( $query ) {
    if ( ! is_admin() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( 'ID' === $orderby ) {
        $query->set( 'orderby', 'ID' );
    }
}
add_action( 'pre_get_posts', 'seowk_id_column_orderby' );

// 7. CSS für optimale Darstellung
function seowk_id_column_css() {
    echo '<style>
        /* ID-Spalte schmal halten */
        .column-seowk_id { 
            width: 60px !important;
            text-align: center;
        }
        
        /* Responsive: Auf kleinen Bildschirmen verstecken */
        @media screen and (max-width: 782px) {
            .column-seowk_id {
                display: none;
            }
        }
        
        /* Hover-Effekt für bessere Lesbarkeit */
        .column-seowk_id strong:hover {
            color: #135e96;
            cursor: default;
        }
    </style>';
}
add_action( 'admin_head', 'seowk_id_column_css' );

// 8. BONUS: Quick-Copy Funktion mit JavaScript (Kopiere ID per Klick)
function seowk_id_column_quick_copy_js() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Klickbaren Tooltip für ID-Spalte
        $('.column-seowk_id strong').each(function() {
            var $this = $(this);
            var id = $this.text();
            
            // Tooltip hinzufügen
            $this.attr('title', 'Klicken zum Kopieren: ' + id);
            $this.css('cursor', 'pointer');
            
            // Click-Handler für Copy-Funktion
            $this.on('click', function(e) {
                e.preventDefault();
                
                // ID in Zwischenablage kopieren
                var tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(id).select();
                document.execCommand('copy');
                tempInput.remove();
                
                // Visuelles Feedback
                var originalText = $this.text();
                $this.text('✓ Kopiert!').css('color', '#00a32a');
                
                setTimeout(function() {
                    $this.text(originalText).css('color', '#2271b1');
                }, 1000);
            });
        });
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'seowk_id_column_quick_copy_js' );

// 9. Admin-Hinweis bei erster Aktivierung (optional)
function seowk_id_column_activation_notice() {
    $screen = get_current_screen();
    
    // Nur auf Post/Page Übersichten anzeigen
    if ( ! in_array( $screen->base, array( 'edit', 'upload' ) ) ) {
        return;
    }
    
    // Prüfe, ob Hinweis bereits gesehen wurde
    $noticed = get_option( 'seowk_id_column_noticed', false );
    
    if ( ! $noticed ) {
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <strong>💡 SEO Wunderkiste Tipp:</strong> 
                Du kannst jetzt auf eine ID klicken, um sie in die Zwischenablage zu kopieren!
            </p>
        </div>
        <?php
        
        // Markiere als gesehen (nur einmal anzeigen)
        update_option( 'seowk_id_column_noticed', true );
    }
}
add_action( 'admin_notices', 'seowk_id_column_activation_notice' );
