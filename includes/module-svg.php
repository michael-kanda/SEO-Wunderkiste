<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: SVG Upload Support (Sicher)
 * ------------------------------------------------------------------------- */

function seowk_add_svg_mime_type( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'seowk_add_svg_mime_type' );

function seowk_fix_svg_mime_type( $data, $file, $filename, $mimes ) {
    $ext = isset( $data['ext'] ) ? $data['ext'] : '';
    if ( strlen( $ext ) < 1 ) { $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ); }
    if ( $ext === 'svg' || $ext === 'svgz' ) { $data['type'] = 'image/svg+xml'; $data['ext'] = $ext; }
    return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'seowk_fix_svg_mime_type', 10, 4 );

function seowk_sanitize_svg_upload( $file ) {
    if ( $file['type'] !== 'image/svg+xml' ) { return $file; }
    $svg_content = file_get_contents( $file['tmp_name'] );
    if ( empty( $svg_content ) ) { return $file; }
    $clean_svg = seowk_sanitize_svg_content( $svg_content );
    if ( $clean_svg === false ) {
        $file['error'] = __( 'Diese SVG-Datei enthält unsicheren Code.', 'seo-wunderkiste' );
        return $file;
    }
    file_put_contents( $file['tmp_name'], $clean_svg );
    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'seowk_sanitize_svg_upload' );

function seowk_sanitize_svg_content( $svg_content ) {
    libxml_use_internal_errors( true );
    $dom = new DOMDocument();
    if ( ! $dom->loadXML( $svg_content, LIBXML_NONET ) ) { libxml_clear_errors(); return false; }
    libxml_clear_errors();
    $xpath = new DOMXPath( $dom );
    $dangerous = array( 'script', 'foreignObject', 'set', 'animate', 'animateMotion', 'animateTransform', 'handler' );
    foreach ( $dangerous as $tag ) {
        foreach ( $xpath->query( '//' . $tag ) as $el ) { $el->parentNode->removeChild( $el ); }
    }
    $events = array( 'onload', 'onclick', 'onerror', 'onmouseover', 'onfocus', 'onblur' );
    foreach ( $dom->getElementsByTagName( '*' ) as $el ) {
        foreach ( $events as $attr ) { if ( $el->hasAttribute( $attr ) ) { $el->removeAttribute( $attr ); } }
        foreach ( array( 'href', 'xlink:href' ) as $href ) {
            if ( $el->hasAttribute( $href ) && preg_match( '/^\s*(javascript|data|vbscript):/i', $el->getAttribute( $href ) ) ) {
                $el->removeAttribute( $href );
            }
        }
    }
    return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $dom->saveXML( $dom->documentElement );
}

function seowk_svg_media_preview( $response, $attachment, $meta ) {
    if ( $response['mime'] === 'image/svg+xml' ) {
        $svg_url = wp_get_attachment_url( $attachment->ID );
        if ( $svg_url ) { $response['image'] = array( 'src' => $svg_url ); }
    }
    return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'seowk_svg_media_preview', 10, 3 );

function seowk_svg_thumbnail_filter( $image, $attachment_id, $size, $icon ) {
    if ( get_post_mime_type( $attachment_id ) === 'image/svg+xml' ) {
        return array( wp_get_attachment_url( $attachment_id ), 100, 100, false );
    }
    return $image;
}
add_filter( 'wp_get_attachment_image_src', 'seowk_svg_thumbnail_filter', 10, 4 );
