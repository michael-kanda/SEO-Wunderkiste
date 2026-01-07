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
 * Entfernt Scripts, Event-Handler und potenziell gefährliche Elemente
 */
function seowk_sanitize_svg_upload( $file ) {
    if ( $file['type'] !== 'image/svg+xml' ) {
        return $file;
    }
    
    // Nur für Benutzer ohne unfiltered_html Berechtigung sanitizen
    // Admins können optional unsanitized uploaden (siehe Filter unten)
    $should_sanitize = apply_filters( 'seowk_sanitize_svg', true );
    
    if ( ! $should_sanitize && current_user_can( 'unfiltered_html' ) ) {
        return $file;
    }
    
    $file_path = $file['tmp_name'];
    
    if ( ! file_exists( $file_path ) ) {
        return $file;
    }
    
    $svg_content = file_get_contents( $file_path );
    
    if ( empty( $svg_content ) ) {
        return $file;
    }
    
    // SVG sanitizen
    $clean_svg = seowk_sanitize_svg_content( $svg_content );
    
    if ( $clean_svg === false ) {
        $file['error'] = __( 'Diese SVG-Datei enthält ungültigen oder unsicheren Code.', 'seo-wunderkiste' );
        return $file;
    }
    
    // Bereinigte SVG zurückschreiben
    file_put_contents( $file_path, $clean_svg );
    
    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'seowk_sanitize_svg_upload' );

/**
 * SVG-Inhalt bereinigen
 * Entfernt alle potenziell gefährlichen Elemente und Attribute
 */
function seowk_sanitize_svg_content( $svg_content ) {
    // Prüfen ob es valides XML ist
    $use_errors = libxml_use_internal_errors( true );
    
    $dom = new DOMDocument();
    $dom->formatOutput = false;
    $dom->preserveWhiteSpace = true;
    
    // SVG laden
    $loaded = $dom->loadXML( $svg_content, LIBXML_NONET | LIBXML_DTDLOAD | LIBXML_DTDATTR );
    
    if ( ! $loaded ) {
        libxml_clear_errors();
        libxml_use_internal_errors( $use_errors );
        return false;
    }
    
    libxml_clear_errors();
    libxml_use_internal_errors( $use_errors );
    
    // XPath für Suche
    $xpath = new DOMXPath( $dom );
    
    // === GEFÄHRLICHE ELEMENTE ENTFERNEN ===
    $dangerous_elements = array(
        'script',           // JavaScript
        'foreignObject',    // Kann HTML/JS einbetten
        'set',              // SMIL Animation (kann JS triggern)
        'animate',          // SMIL Animation
        'animateMotion',    // SMIL Animation
        'animateTransform', // SMIL Animation
        'handler',          // Event Handler Element
    );
    
    foreach ( $dangerous_elements as $tag ) {
        $elements = $xpath->query( '//' . $tag );
        if ( $elements ) {
            foreach ( $elements as $element ) {
                $element->parentNode->removeChild( $element );
            }
        }
        
        // Auch mit svg: Namespace
        $elements = $xpath->query( '//svg:' . $tag );
        if ( $elements ) {
            foreach ( $elements as $element ) {
                $element->parentNode->removeChild( $element );
            }
        }
    }
    
    // === GEFÄHRLICHE ATTRIBUTE ENTFERNEN ===
    $dangerous_attributes = array(
        // Event Handler
        'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate',
        'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus',
        'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onbegin',
        'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu',
        'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged',
        'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend',
        'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onend',
        'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin',
        'onfocusout', 'onhashchange', 'onhelp', 'oninput', 'onkeydown', 'onkeypress',
        'onkeyup', 'onlayoutcomplete', 'onload', 'onloadstart', 'onlosecapture',
        'onmessage', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove',
        'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend',
        'onmovestart', 'onoffline', 'ononline', 'onpagehide', 'onpageshow', 'onpaste',
        'onpopstate', 'onprogress', 'onpropertychange', 'onreadystatechange', 'onredo',
        'onrepeat', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter',
        'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onsearch', 'onselect',
        'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onstorage', 'onsubmit',
        'ontoggle', 'onundo', 'onunload', 'onzoom',
        // Andere gefährliche Attribute
        'formaction', 'xlink:href', 'href',
    );
    
    // Alle Elemente durchgehen
    $all_elements = $dom->getElementsByTagName( '*' );
    
    foreach ( $all_elements as $element ) {
        // Event-Handler Attribute entfernen
        foreach ( $dangerous_attributes as $attr ) {
            if ( $element->hasAttribute( $attr ) ) {
                $element->removeAttribute( $attr );
            }
        }
        
        // href/xlink:href nur erlauben wenn es kein javascript: ist
        $href_attrs = array( 'href', 'xlink:href' );
        foreach ( $href_attrs as $href_attr ) {
            if ( $element->hasAttribute( $href_attr ) ) {
                $href_value = $element->getAttribute( $href_attr );
                // Erlauben: #anchor, relative URLs, http(s)
                if ( preg_match( '/^\s*(javascript|data|vbscript):/i', $href_value ) ) {
                    $element->removeAttribute( $href_attr );
                }
            }
        }
        
        // style Attribut auf gefährliche Inhalte prüfen
        if ( $element->hasAttribute( 'style' ) ) {
            $style = $element->getAttribute( 'style' );
            // JavaScript in CSS entfernen (expression, url mit javascript:, etc.)
            if ( preg_match( '/(expression|javascript|vbscript|behavior|binding)/i', $style ) ) {
                $element->removeAttribute( 'style' );
            }
        }
    }
    
    // XML Deklaration und DOCTYPE entfernen (optional, für Inline-SVG)
    $svg_output = $dom->saveXML( $dom->documentElement );
    
    // XML-Deklaration hinzufügen für Standalone-Dateien
    $svg_output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $svg_output;
    
    return $svg_output;
}

/**
 * SVG Vorschau in der Mediathek ermöglichen
 */
function seowk_svg_media_preview( $response, $attachment, $meta ) {
    if ( $response['mime'] === 'image/svg+xml' ) {
        $svg_url = wp_get_attachment_url( $attachment->ID );
        
        if ( $svg_url ) {
            $response['image'] = array(
                'src' => $svg_url,
            );
            
            // Größe aus Datei lesen falls nicht in Meta
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
    
    $svg_content = file_get_contents( $svg_path );
    
    if ( empty( $svg_content ) ) {
        return false;
    }
    
    // Width und Height aus SVG-Tag extrahieren
    $width  = 0;
    $height = 0;
    
    // Versuche width/height Attribute
    if ( preg_match( '/\bwidth\s*=\s*["\']?(\d+(?:\.\d+)?)/i', $svg_content, $match ) ) {
        $width = floatval( $match[1] );
    }
    if ( preg_match( '/\bheight\s*=\s*["\']?(\d+(?:\.\d+)?)/i', $svg_content, $match ) ) {
        $height = floatval( $match[1] );
    }
    
    // Falls nicht gefunden, versuche viewBox
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
 * SVG Thumbnail in der Listenansicht
 */
function seowk_svg_thumbnail_in_list( $icon, $mime, $post_id ) {
    if ( $mime === 'image/svg+xml' ) {
        return wp_get_attachment_url( $post_id );
    }
    return $icon;
}
add_filter( 'wp_get_attachment_image_src', 'seowk_svg_thumbnail_filter', 10, 4 );

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

/**
 * Admin-Hinweis zur SVG-Sicherheit anzeigen (einmalig)
 */
function seowk_svg_security_notice() {
    // Nur auf der Mediathek-Seite anzeigen
    $screen = get_current_screen();
    if ( ! $screen || $screen->id !== 'upload' ) {
        return;
    }
    
    // Nur einmal anzeigen
    if ( get_user_meta( get_current_user_id(), 'seowk_svg_notice_dismissed', true ) ) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible seowk-svg-notice">
        <p>
            <strong>🛡️ SVG Upload aktiviert (SEO Wunderkiste)</strong><br>
            SVG-Dateien werden beim Upload automatisch bereinigt: Scripts, Event-Handler und potenziell gefährliche Elemente werden entfernt.
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
 * AJAX: Notice dismissal speichern
 */
function seowk_dismiss_svg_notice() {
    update_user_meta( get_current_user_id(), 'seowk_svg_notice_dismissed', true );
    wp_die();
}
add_action( 'wp_ajax_seowk_dismiss_svg_notice', 'seowk_dismiss_svg_notice' );

/**
 * Filter: Admins können SVG-Sanitization deaktivieren
 * 
 * Beispiel in functions.php:
 * add_filter( 'seowk_sanitize_svg', '__return_false' );
 * 
 * Oder nur für bestimmte Benutzer:
 * add_filter( 'seowk_sanitize_svg', function( $sanitize ) {
 *     return ! current_user_can( 'administrator' );
 * });
 */
