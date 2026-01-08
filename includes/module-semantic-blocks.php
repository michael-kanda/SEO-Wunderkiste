<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: SEMANTIC BLOCKS - SEO Wunderkiste
 * Semantische HTML5 Wrapper-Blöcke
 * ------------------------------------------------------------------------- */

function seowk_register_semantic_blocks() {
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    $blocks = array(
        'article' => __( 'Article', 'seo-wunderkiste' ),
        'section' => __( 'Section', 'seo-wunderkiste' ),
        'aside'   => __( 'Aside', 'seo-wunderkiste' ),
        'header'  => __( 'Header', 'seo-wunderkiste' ),
        'footer'  => __( 'Footer', 'seo-wunderkiste' ),
        'main'    => __( 'Main', 'seo-wunderkiste' ),
        'figure'  => __( 'Figure', 'seo-wunderkiste' ),
        'address' => __( 'Address', 'seo-wunderkiste' ),
    );

    foreach ( $blocks as $tag => $title ) {
        register_block_type( 'seowk/' . $tag, array(
            'api_version'     => 2,
            'render_callback' => 'seowk_render_semantic_block_' . $tag,
            'attributes'      => array(
                'cssClass' => array( 'type' => 'string', 'default' => '' ),
                'cssId'    => array( 'type' => 'string', 'default' => '' ),
            ),
        ) );
    }

    // Details block mit extra attributen
    register_block_type( 'seowk/details', array(
        'api_version'     => 2,
        'render_callback' => 'seowk_render_details_block',
        'attributes'      => array(
            'cssClass' => array( 'type' => 'string', 'default' => '' ),
            'cssId'    => array( 'type' => 'string', 'default' => '' ),
            'summary'  => array( 'type' => 'string', 'default' => 'Mehr anzeigen' ),
            'open'     => array( 'type' => 'boolean', 'default' => false ),
        ),
    ) );

    // Mark block
    register_block_type( 'seowk/mark', array(
        'api_version'     => 2,
        'render_callback' => 'seowk_render_mark_block',
        'attributes'      => array(
            'cssClass' => array( 'type' => 'string', 'default' => '' ),
            'text'     => array( 'type' => 'string', 'default' => '' ),
        ),
    ) );
}
add_action( 'init', 'seowk_register_semantic_blocks' );

function seowk_get_block_attributes_html( $attributes ) {
    $html = '';
    
    if ( ! empty( $attributes['cssClass'] ) ) {
        $html .= ' class="' . esc_attr( $attributes['cssClass'] ) . '"';
    }
    
    if ( ! empty( $attributes['cssId'] ) ) {
        $html .= ' id="' . esc_attr( $attributes['cssId'] ) . '"';
    }
    
    return $html;
}

function seowk_render_semantic_block_article( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<article' . $attrs . '>' . $content . '</article>';
}

function seowk_render_semantic_block_section( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<section' . $attrs . '>' . $content . '</section>';
}

function seowk_render_semantic_block_aside( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<aside' . $attrs . '>' . $content . '</aside>';
}

function seowk_render_semantic_block_header( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<header' . $attrs . '>' . $content . '</header>';
}

function seowk_render_semantic_block_footer( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<footer' . $attrs . '>' . $content . '</footer>';
}

function seowk_render_semantic_block_main( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<main' . $attrs . '>' . $content . '</main>';
}

function seowk_render_semantic_block_figure( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    $caption = ! empty( $attributes['caption'] ) ? '<figcaption>' . esc_html( $attributes['caption'] ) . '</figcaption>' : '';
    return '<figure' . $attrs . '>' . $content . $caption . '</figure>';
}

function seowk_render_semantic_block_address( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<address' . $attrs . '>' . $content . '</address>';
}

function seowk_render_details_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    $open = ! empty( $attributes['open'] ) ? ' open' : '';
    $summary = ! empty( $attributes['summary'] ) ? $attributes['summary'] : __( 'Mehr anzeigen', 'seo-wunderkiste' );
    return '<details' . $attrs . $open . '><summary>' . esc_html( $summary ) . '</summary>' . $content . '</details>';
}

function seowk_render_mark_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    $text = ! empty( $attributes['text'] ) ? $attributes['text'] : $content;
    return '<mark' . $attrs . '>' . esc_html( $text ) . '</mark>';
}

function seowk_semantic_blocks_frontend_style() {
    $css = '
        details.seowk-details { margin: 1em 0; }
        details.seowk-details summary { cursor: pointer; padding: 0.5em; font-weight: 600; }
        details.seowk-details > *:not(summary) { padding: 0.5em; }
        mark { background: linear-gradient(120deg, #fff176 0%, #ffee58 100%); padding: 0.1em 0.3em; border-radius: 2px; }
        address { font-style: normal; }
    ';
    
    wp_add_inline_style( 'wp-block-library', $css );
}
add_action( 'wp_enqueue_scripts', 'seowk_semantic_blocks_frontend_style' );
