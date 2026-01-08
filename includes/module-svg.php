<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: SVG Upload Support (Sicher)
 * Version: 2.0 - Mit Sanitization zum Entfernen von Scripts/Events
 * ------------------------------------------------------------------------- */

/**
 * SVG MIME-Type erlauben
 */
function seowk_add_svg_mime_type( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'seowk_add_svg_mime_type' );

/**
 * SVG Dateiprüfung für WordPress 4.7.1+
 */
function seowk_fix_svg_mime_type( $data, $file, $filename, $mimes ) {
    $ext = isset( $data['ext'] ) ? $data['ext'] : '';
    
    if ( strlen( $ext ) < 1 ) {
        $exploded = explode( '.', $filename );
        $ext = strtolower( end( $exploded ) );
    }
    
    if ( $ext === 'svg' ) {
        $data['type'] = 'image/svg+xml';
        $data['ext']  = 'svg';
    } elseif ( $ext === 'svgz' ) {
        $data['type'] = 'image/svg+xml';
        $data['ext']  = 'svgz';
    }
    
    return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'seowk_fix_svg_mime_type', 10, 4 );

/**
 * SVG beim Upload sanitizen (bereinigen)
 */
function seowk_sanitize_svg_upload( $file ) {
    if ( $file['type'] !== 'image/svg+xml' ) {
        return $file;
    }
    
    $should_sanitize = apply_filters( 'seowk_sanitize_svg', true );
    
    if ( ! $should_sanitize && current_user_can( 'unfiltered_html' ) ) {
        return $file;
    }
    
    $file_path = $file['tmp_name'];
    
    if ( ! file_exists( $file_path ) ) {
        return $file;
    }
    
    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Lokale Datei
    $svg_content = file_get_contents( $file_path );
    
    if ( empty( $svg_content ) ) {
        return $file;
    }
    
    $clean_svg = seowk_sanitize_svg_content( $svg_content );
    
    if ( $clean_svg === false ) {
        $file['error'] = __( 'Diese SVG-Datei enthält ungültigen oder unsicheren Code.', 'seo-wunderkiste' );
        return $file;
    }
    
    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- Lokale Datei
    file_put_contents( $file_path, $clean_svg );
    
    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'seowk_sanitize_svg_upload' );

/**
 * SVG-Inhalt bereinigen
 */
function seowk_sanitize_svg_content( $svg_content ) {
    $use_errors = libxml_use_internal_errors( true );
    
    $dom = new DOMDocument();
    $dom->formatOutput = false;
    $dom->preserveWhiteSpace = true;
    
    $loaded = $dom->loadXML( $svg_content, LIBXML_NONET | LIBXML_DTDLOAD | LIBXML_DTDATTR );
    
    if ( ! $loaded ) {
        libxml_clear_errors();
        libxml_use_internal_errors( $use_errors );
        return false;
    }
    
    libxml_clear_errors();
    libxml_use_internal_errors( $use_errors );
    
    $xpath = new DOMXPath( $dom );
    
    // Gefährliche Elemente entfernen
    $dangerous_elements = array(
        'script', 'foreignObject', 'set', 'animate', 'animateMotion', 'animateTransform', 'handler',
    );
    
    foreach ( $dangerous_elements as $tag ) {
        $elements = $xpath->query( '//' . $tag );
        if ( $elements ) {
            foreach ( $elements as $element ) {
                $element->parentNode->removeChild( $element );
            }
        }
    }
    
    // Gefährliche Attribute entfernen
    $dangerous_attributes = array(
        'onabort', 'onactivate', 'onbegin', 'onblur', 'onclick', 'oncontextmenu',
        'ondblclick', 'onend', 'onerror', 'onfocus', 'oninput', 'onkeydown', 'onkeypress',
        'onkeyup', 'onload', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove',
        'onmouseout', 'onmouseover', 'onmouseup', 'onresize', 'onscroll', 'onsubmit',
        'onunload', 'onzoom', 'formaction',
    );
    
    $all_elements = $dom->getElementsByTagName( '*' );
    
    foreach ( $all_elements as $element ) {
        foreach ( $dangerous_attributes as $attr ) {
            if ( $element->hasAttribute( $attr ) ) {
                $element->removeAttribute( $attr );
            }
        }
        
        // href prüfen
        $href_attrs = array( 'href', 'xlink:href' );
        foreach ( $href_attrs as $href_attr ) {
            if ( $element->hasAttribute( $href_attr ) ) {
                $href_value = $element->getAttribute( $href_attr );
                if ( preg_match( '/^\s*(javascript|data|vbscript):/i', $href_value ) ) {
                    $element->removeAttribute( $href_attr );
                }
            }
        }
        
        // style prüfen
        if ( $element->hasAttribute( 'style' ) ) {
            $style = $element->getAttribute( 'style' );
            if ( preg_match( '/(expression|javascript|vbscript|behavior|binding)/i', $style ) ) {
                $element->removeAttribute( 'style' );
            }
        }
    }
    
    $svg_output = $dom->saveXML( $dom->documentElement );
    $svg_output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $svg_output;
    
    return $svg_output;
}

/**
 * SVG Vorschau in der Mediathek
 */
function seowk_svg_media_preview( $response, $attachment, $meta ) {
    if ( $response['mime'] === 'image/svg+xml' ) {
        $svg_url = wp_get_attachment_url( $attachment->ID );
        
        if ( $svg_url ) {
            $response['image'] = array( 'src' => $svg_url );
            
            if ( empty( $response['width'] ) || empty( $response['height'] ) ) {
                $svg_path = get_attached_file( $attachment->ID );
                $dimensions = seowk_get_svg_dimensions( $svg_path );
                
                if ( $dimensions ) {
                    $response['width']  = $dimensions['width'];
                    $response['height'] = $dimensions['height'];
                }
            }
        }
    }
    
    return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'seowk_svg_media_preview', 10, 3 );

/**
 * SVG Dimensionen aus Datei lesen
 */
function seowk_get_svg_dimensions( $svg_path ) {
    if ( ! file_exists( $svg_path ) ) {
        return false;
    }
    
    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Lokale Datei
    $svg_content = file_get_contents( $svg_path );
    
    if ( empty( $svg_content ) ) {
        return false;
    }
    
    $width  = 0;
    $height = 0;
    
    if ( preg_match( '/\bwidth\s*=\s*["\']?(\d+(?:\.\d+)?)/i', $svg_content, $match ) ) {
        $width = floatval( $match[1] );
    }
    if ( preg_match( '/\bheight\s*=\s*["\']?(\d+(?:\.\d+)?)/i', $svg_content, $match ) ) {
        $height = floatval( $match[1] );
    }
    
    if ( ( ! $width || ! $height ) && preg_match( '/viewBox\s*=\s*["\']?\s*(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)/i', $svg_content, $match ) ) {
        $width  = floatval( $match[3] );
        $height = floatval( $match[4] );
    }
    
    if ( $width && $height ) {
        return array(
            'width'  => round( $width ),
            'height' => round( $height ),
        );
    }
    
    return false;
}

/**
 * SVG Thumbnail Filter
 */
function seowk_svg_thumbnail_filter( $image, $attachment_id, $size, $icon ) {
    if ( get_post_mime_type( $attachment_id ) === 'image/svg+xml' ) {
        $svg_url = wp_get_attachment_url( $attachment_id );
        $dimensions = seowk_get_svg_dimensions( get_attached_file( $attachment_id ) );
        
        $width  = $dimensions ? $dimensions['width'] : 100;
        $height = $dimensions ? $dimensions['height'] : 100;
        
        return array( $svg_url, $width, $height, false );
    }
    return $image;
}
add_filter( 'wp_get_attachment_image_src', 'seowk_svg_thumbnail_filter', 10, 4 );

/**
 * Admin-Hinweis zur SVG-Sicherheit
 */
function seowk_svg_security_notice() {
    $screen = get_current_screen();
    if ( ! $screen || $screen->id !== 'upload' ) {
        return;
    }
    
    if ( get_user_meta( get_current_user_id(), 'seowk_svg_notice_dismissed', true ) ) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible seowk-svg-notice">
        <p>
            <strong>🛡️ <?php esc_html_e( 'SVG Upload aktiviert (SEO Wunderkiste)', 'seo-wunderkiste' ); ?></strong><br>
            <?php esc_html_e( 'SVG-Dateien werden beim Upload automatisch bereinigt: Scripts, Event-Handler und potenziell gefährliche Elemente werden entfernt.', 'seo-wunderkiste' ); ?>
        </p>
    </div>
    <script>
    jQuery(document).on('click', '.seowk-svg-notice .notice-dismiss', function() {
        jQuery.post(ajaxurl, { action: 'seowk_dismiss_svg_notice' });
    });
    </script>
    <?php
}
add_action( 'admin_notices', 'seowk_svg_security_notice' );

/**
 * AJAX: Notice dismissal
 */
function seowk_dismiss_svg_notice() {
    update_user_meta( get_current_user_id(), 'seowk_svg_notice_dismissed', true );
    wp_die();
}
add_action( 'wp_ajax_seowk_dismiss_svg_notice', 'seowk_dismiss_svg_notice' );
