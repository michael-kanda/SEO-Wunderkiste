<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: SEMANTIC BLOCKS - SEO Wunderkiste
 * Semantische HTML5 Wrapper-Blöcke für bessere Struktur und SEO
 * ------------------------------------------------------------------------- */

/**
 * Registriert alle semantischen Blöcke
 */
function seowk_register_semantic_blocks() {
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // Scripts und Styles registrieren
    wp_register_script(
        'seowk-semantic-blocks-editor',
        false,
        array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
        SEOWK_VERSION
    );

    wp_register_style(
        'seowk-semantic-blocks-editor-style',
        false,
        array(),
        SEOWK_VERSION
    );

    wp_register_style(
        'seowk-semantic-blocks-frontend-style',
        false,
        array(),
        SEOWK_VERSION
    );

    // Inline Scripts und Styles
    wp_add_inline_script( 'seowk-semantic-blocks-editor', seowk_get_semantic_blocks_script() );
    wp_add_inline_style( 'seowk-semantic-blocks-editor-style', seowk_get_semantic_blocks_editor_style() );
    wp_add_inline_style( 'seowk-semantic-blocks-frontend-style', seowk_get_semantic_blocks_frontend_style() );

    // Block: Article
    register_block_type( 'seowk/article', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_article_block',
        'attributes'      => seowk_get_semantic_block_attributes(),
    ) );

    // Block: Section
    register_block_type( 'seowk/section', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_section_block',
        'attributes'      => seowk_get_semantic_block_attributes(),
    ) );

    // Block: Aside
    register_block_type( 'seowk/aside', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_aside_block',
        'attributes'      => seowk_get_semantic_block_attributes(),
    ) );

    // Block: Figure
    register_block_type( 'seowk/figure', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_figure_block',
        'attributes'      => array_merge( seowk_get_semantic_block_attributes(), array(
            'caption' => array( 'type' => 'string', 'default' => '' ),
        ) ),
    ) );

    // Block: Details (Akkordeon)
    register_block_type( 'seowk/details', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_details_block',
        'attributes'      => array_merge( seowk_get_semantic_block_attributes(), array(
            'summary' => array( 'type' => 'string', 'default' => 'Mehr anzeigen' ),
            'open'    => array( 'type' => 'boolean', 'default' => false ),
        ) ),
    ) );

    // Block: Address
    register_block_type( 'seowk/address', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_address_block',
        'attributes'      => seowk_get_semantic_block_attributes(),
    ) );

    // Block: Mark (Hervorhebung)
    register_block_type( 'seowk/mark', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_mark_block',
        'attributes'      => array_merge( seowk_get_semantic_block_attributes(), array(
            'text' => array( 'type' => 'string', 'default' => '' ),
        ) ),
    ) );

    // Block: Header
    register_block_type( 'seowk/header', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_header_block',
        'attributes'      => seowk_get_semantic_block_attributes(),
    ) );

    // Block: Footer
    register_block_type( 'seowk/footer', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_footer_block',
        'attributes'      => seowk_get_semantic_block_attributes(),
    ) );

    // Block: Main
    register_block_type( 'seowk/main', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-semantic-blocks-editor',
        'editor_style'    => 'seowk-semantic-blocks-editor-style',
        'style'           => 'seowk-semantic-blocks-frontend-style',
        'render_callback' => 'seowk_render_main_block',
        'attributes'      => seowk_get_semantic_block_attributes(),
    ) );
}
add_action( 'init', 'seowk_register_semantic_blocks' );

/**
 * Standard-Attribute für alle semantischen Blöcke
 */
function seowk_get_semantic_block_attributes() {
    return array(
        'cssClass' => array(
            'type'    => 'string',
            'default' => '',
        ),
        'cssId' => array(
            'type'    => 'string',
            'default' => '',
        ),
        'ariaLabel' => array(
            'type'    => 'string',
            'default' => '',
        ),
        'role' => array(
            'type'    => 'string',
            'default' => '',
        ),
    );
}

/**
 * Hilfsfunktion: Generiert HTML-Attribute
 */
function seowk_get_block_attributes_html( $attributes ) {
    $html = '';
    
    if ( ! empty( $attributes['cssClass'] ) ) {
        $html .= ' class="' . esc_attr( $attributes['cssClass'] ) . '"';
    }
    
    if ( ! empty( $attributes['cssId'] ) ) {
        $html .= ' id="' . esc_attr( $attributes['cssId'] ) . '"';
    }
    
    if ( ! empty( $attributes['ariaLabel'] ) ) {
        $html .= ' aria-label="' . esc_attr( $attributes['ariaLabel'] ) . '"';
    }
    
    if ( ! empty( $attributes['role'] ) ) {
        $html .= ' role="' . esc_attr( $attributes['role'] ) . '"';
    }
    
    return $html;
}

/**
 * Render Callbacks für alle Blöcke
 */
function seowk_render_article_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<article' . $attrs . '>' . $content . '</article>';
}

function seowk_render_section_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<section' . $attrs . '>' . $content . '</section>';
}

function seowk_render_aside_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<aside' . $attrs . '>' . $content . '</aside>';
}

function seowk_render_figure_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    $caption = ! empty( $attributes['caption'] ) ? '<figcaption>' . esc_html( $attributes['caption'] ) . '</figcaption>' : '';
    return '<figure' . $attrs . '>' . $content . $caption . '</figure>';
}

function seowk_render_details_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    $open = ! empty( $attributes['open'] ) ? ' open' : '';
    $summary = ! empty( $attributes['summary'] ) ? $attributes['summary'] : 'Mehr anzeigen';
    return '<details' . $attrs . $open . '><summary>' . esc_html( $summary ) . '</summary>' . $content . '</details>';
}

function seowk_render_address_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<address' . $attrs . '>' . $content . '</address>';
}

function seowk_render_mark_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    $text = ! empty( $attributes['text'] ) ? $attributes['text'] : $content;
    return '<mark' . $attrs . '>' . esc_html( $text ) . '</mark>';
}

function seowk_render_header_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<header' . $attrs . '>' . $content . '</header>';
}

function seowk_render_footer_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<footer' . $attrs . '>' . $content . '</footer>';
}

function seowk_render_main_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    return '<main' . $attrs . '>' . $content . '</main>';
}

/**
 * JavaScript für alle semantischen Blöcke
 */
function seowk_get_semantic_blocks_script() {
    return <<<'JS'
(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { createElement: el, Fragment } = wp.element;
    const { InspectorControls, InnerBlocks, useBlockProps, RichText } = wp.blockEditor;
    const { PanelBody, TextControl, ToggleControl } = wp.components;

    // Gemeinsame Attribute-Controls für alle Blöcke
    const AttributeControls = ({ attributes, setAttributes }) => {
        const { cssClass, cssId, ariaLabel, role } = attributes;
        
        return el(PanelBody, { title: 'HTML Attribute', initialOpen: false },
            el(TextControl, {
                label: 'CSS Klasse',
                value: cssClass || '',
                onChange: (value) => setAttributes({ cssClass: value }),
                placeholder: 'z.B. my-custom-class',
            }),
            el(TextControl, {
                label: 'ID',
                value: cssId || '',
                onChange: (value) => setAttributes({ cssId: value }),
                placeholder: 'z.B. intro-section',
            }),
            el(TextControl, {
                label: 'ARIA Label',
                value: ariaLabel || '',
                onChange: (value) => setAttributes({ ariaLabel: value }),
                help: 'Beschreibung für Screenreader',
            }),
            el(TextControl, {
                label: 'Role',
                value: role || '',
                onChange: (value) => setAttributes({ role: value }),
                placeholder: 'z.B. region, complementary',
            })
        );
    };

    // Block-Definitionen
    const blocks = [
        {
            name: 'seowk/article',
            title: 'Article',
            description: 'Eigenständiger, wiederverwendbarer Inhalt (Blogpost, News, Kommentar)',
            icon: 'media-text',
            tagInfo: '<article> – Für in sich geschlossene Inhalte',
            color: '#2271b1',
        },
        {
            name: 'seowk/section',
            title: 'Section',
            description: 'Thematischer Abschnitt mit eigenem Fokus',
            icon: 'align-wide',
            tagInfo: '<section> – Für thematische Gruppierungen',
            color: '#00a32a',
        },
        {
            name: 'seowk/aside',
            title: 'Aside',
            description: 'Ergänzende Information, Sidebar-Inhalte',
            icon: 'align-pull-right',
            tagInfo: '<aside> – Für ergänzende Inhalte',
            color: '#dba617',
        },
        {
            name: 'seowk/header',
            title: 'Header',
            description: 'Einleitender Bereich eines Abschnitts',
            icon: 'table-row-before',
            tagInfo: '<header> – Für einleitende Inhalte',
            color: '#8c5cb3',
        },
        {
            name: 'seowk/footer',
            title: 'Footer',
            description: 'Abschließender Bereich eines Abschnitts',
            icon: 'table-row-after',
            tagInfo: '<footer> – Für abschließende Inhalte',
            color: '#8c5cb3',
        },
        {
            name: 'seowk/main',
            title: 'Main',
            description: 'Hauptinhalt der Seite (nur einmal pro Seite verwenden)',
            icon: 'layout',
            tagInfo: '<main> – Für den Hauptinhalt',
            color: '#d63638',
        },
        {
            name: 'seowk/address',
            title: 'Address',
            description: 'Kontaktinformationen für Autor oder Organisation',
            icon: 'location',
            tagInfo: '<address> – Für Kontaktdaten',
            color: '#3582c4',
        },
    ];

    // Registriere Container-Blöcke
    blocks.forEach(block => {
        registerBlockType(block.name, {
            title: block.title,
            description: block.description,
            icon: block.icon,
            category: 'design',
            keywords: ['semantic', 'html5', 'seo', block.title.toLowerCase()],
            supports: {
                html: false,
                align: ['wide', 'full'],
            },
            
            edit: function(props) {
                const { attributes, setAttributes } = props;
                const blockProps = useBlockProps({
                    className: 'seowk-semantic-block seowk-semantic-' + block.name.split('/')[1],
                    style: { borderColor: block.color }
                });

                return el(Fragment, {},
                    el(InspectorControls, {},
                        el(PanelBody, { title: 'Info', initialOpen: true },
                            el('p', { style: { margin: 0 } }, block.tagInfo),
                            el('p', { 
                                style: { 
                                    fontSize: '12px', 
                                    color: '#757575',
                                    marginTop: '8px' 
                                } 
                            }, block.description)
                        ),
                        el(AttributeControls, { attributes, setAttributes })
                    ),
                    el('div', blockProps,
                        el('div', { className: 'seowk-semantic-label', style: { backgroundColor: block.color } },
                            el('span', { className: 'dashicons dashicons-' + block.icon }),
                            ' <' + block.name.split('/')[1] + '>'
                        ),
                        el('div', { className: 'seowk-semantic-content' },
                            el(InnerBlocks, {
                                templateLock: false,
                                renderAppender: InnerBlocks.ButtonBlockAppender,
                            })
                        )
                    )
                );
            },

            save: function() {
                return el(InnerBlocks.Content);
            },
        });
    });

    // Block: Figure (mit Caption)
    registerBlockType('seowk/figure', {
        title: 'Figure',
        description: 'Abbildung mit optionaler Beschriftung (Bild, Diagramm, Code)',
        icon: 'format-image',
        category: 'design',
        keywords: ['semantic', 'html5', 'seo', 'figure', 'bild', 'image', 'caption'],
        supports: {
            html: false,
            align: ['left', 'center', 'right', 'wide', 'full'],
        },

        edit: function(props) {
            const { attributes, setAttributes } = props;
            const { caption } = attributes;
            const blockProps = useBlockProps({
                className: 'seowk-semantic-block seowk-semantic-figure',
                style: { borderColor: '#9b59b6' }
            });

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Info', initialOpen: true },
                        el('p', { style: { margin: 0 } }, '<figure> + <figcaption> – Für Abbildungen'),
                    ),
                    el(PanelBody, { title: 'Beschriftung', initialOpen: true },
                        el(TextControl, {
                            label: 'Caption (Bildunterschrift)',
                            value: caption || '',
                            onChange: (value) => setAttributes({ caption: value }),
                            placeholder: 'Beschreibung der Abbildung...',
                        })
                    ),
                    el(AttributeControls, { attributes, setAttributes })
                ),
                el('div', blockProps,
                    el('div', { className: 'seowk-semantic-label', style: { backgroundColor: '#9b59b6' } },
                        el('span', { className: 'dashicons dashicons-format-image' }),
                        ' <figure>'
                    ),
                    el('div', { className: 'seowk-semantic-content' },
                        el(InnerBlocks, {
                            templateLock: false,
                            renderAppender: InnerBlocks.ButtonBlockAppender,
                        })
                    ),
                    caption && el('div', { className: 'seowk-figcaption-preview' },
                        el('span', { className: 'seowk-figcaption-tag' }, '<figcaption>'),
                        ' ' + caption
                    )
                )
            );
        },

        save: function() {
            return el(InnerBlocks.Content);
        },
    });

    // Block: Details (Akkordeon)
    registerBlockType('seowk/details', {
        title: 'Details / Akkordeon',
        description: 'Aufklappbarer Bereich mit Summary (nativ HTML5, kein JavaScript)',
        icon: 'arrow-down-alt2',
        category: 'design',
        keywords: ['semantic', 'html5', 'seo', 'details', 'akkordeon', 'accordion', 'toggle', 'faq'],
        supports: {
            html: false,
        },

        edit: function(props) {
            const { attributes, setAttributes } = props;
            const { summary, open } = attributes;
            const blockProps = useBlockProps({
                className: 'seowk-semantic-block seowk-semantic-details',
                style: { borderColor: '#e67e22' }
            });

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Info', initialOpen: true },
                        el('p', { style: { margin: 0 } }, '<details> + <summary> – Nativer Akkordeon'),
                        el('p', { 
                            style: { fontSize: '12px', color: '#757575', marginTop: '8px' } 
                        }, 'Funktioniert ohne JavaScript!')
                    ),
                    el(PanelBody, { title: 'Einstellungen', initialOpen: true },
                        el(TextControl, {
                            label: 'Summary (Überschrift)',
                            value: summary || '',
                            onChange: (value) => setAttributes({ summary: value }),
                            placeholder: 'Klicken zum Öffnen...',
                        }),
                        el(ToggleControl, {
                            label: 'Standardmäßig geöffnet',
                            checked: open || false,
                            onChange: (value) => setAttributes({ open: value }),
                        })
                    ),
                    el(AttributeControls, { attributes, setAttributes })
                ),
                el('div', blockProps,
                    el('div', { className: 'seowk-semantic-label', style: { backgroundColor: '#e67e22' } },
                        el('span', { className: 'dashicons dashicons-arrow-down-alt2' }),
                        ' <details>'
                    ),
                    el('div', { className: 'seowk-details-summary' },
                        el('span', { className: 'seowk-summary-icon' }, open ? '▼' : '▶'),
                        el(RichText, {
                            tagName: 'span',
                            value: summary || 'Mehr anzeigen',
                            onChange: (value) => setAttributes({ summary: value }),
                            placeholder: 'Summary eingeben...',
                            className: 'seowk-summary-text',
                        })
                    ),
                    el('div', { 
                        className: 'seowk-semantic-content seowk-details-content',
                        style: { display: open ? 'block' : 'block', opacity: open ? 1 : 0.7 }
                    },
                        el(InnerBlocks, {
                            templateLock: false,
                            renderAppender: InnerBlocks.ButtonBlockAppender,
                        })
                    )
                )
            );
        },

        save: function() {
            return el(InnerBlocks.Content);
        },
    });

    // Block: Mark (Inline-Hervorhebung)
    registerBlockType('seowk/mark', {
        title: 'Mark / Highlight',
        description: 'Hervorgehobener, relevanter Text (wie mit Textmarker)',
        icon: 'admin-customizer',
        category: 'design',
        keywords: ['semantic', 'html5', 'seo', 'mark', 'highlight', 'hervorheben', 'wichtig'],
        supports: {
            html: false,
        },

        edit: function(props) {
            const { attributes, setAttributes } = props;
            const { text } = attributes;
            const blockProps = useBlockProps({
                className: 'seowk-semantic-block seowk-semantic-mark',
                style: { borderColor: '#f1c40f' }
            });

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Info', initialOpen: true },
                        el('p', { style: { margin: 0 } }, '<mark> – Für relevante/hervorgehobene Textstellen'),
                        el('p', { 
                            style: { fontSize: '12px', color: '#757575', marginTop: '8px' } 
                        }, 'Semantisch: "Diese Stelle ist relevant im aktuellen Kontext"')
                    ),
                    el(AttributeControls, { attributes, setAttributes })
                ),
                el('div', blockProps,
                    el('div', { className: 'seowk-semantic-label', style: { backgroundColor: '#f1c40f', color: '#333' } },
                        el('span', { className: 'dashicons dashicons-admin-customizer' }),
                        ' <mark>'
                    ),
                    el('div', { className: 'seowk-mark-content' },
                        el(RichText, {
                            tagName: 'p',
                            value: text || '',
                            onChange: (value) => setAttributes({ text: value }),
                            placeholder: 'Hervorzuhebenden Text eingeben...',
                            className: 'seowk-mark-text',
                        })
                    )
                )
            );
        },

        save: function() {
            return null; // Server-side rendering
        },
    });

})(window.wp);
JS;
}

/**
 * Editor Styles
 */
function seowk_get_semantic_blocks_editor_style() {
    return <<<CSS
.seowk-semantic-block {
    border: 2px solid #ccc;
    border-radius: 4px;
    margin: 16px 0;
    background: #fff;
}

.seowk-semantic-label {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 600;
    font-family: monospace;
    color: #fff;
    border-radius: 2px 2px 0 0;
}

.seowk-semantic-label .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

.seowk-semantic-content {
    padding: 16px;
    min-height: 60px;
}

/* Figure */
.seowk-figcaption-preview {
    padding: 8px 16px 12px;
    font-size: 13px;
    color: #666;
    font-style: italic;
    border-top: 1px dashed #ddd;
}

.seowk-figcaption-tag {
    font-family: monospace;
    font-size: 11px;
    color: #9b59b6;
    font-style: normal;
}

/* Details */
.seowk-details-summary {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #f9f9f9;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

.seowk-summary-icon {
    font-size: 12px;
    color: #666;
}

.seowk-summary-text {
    font-weight: 600;
    flex: 1;
}

.seowk-details-content {
    border-top: 1px dashed #ddd;
}

/* Mark */
.seowk-mark-content {
    padding: 16px;
}

.seowk-mark-text {
    background: linear-gradient(120deg, #fff176 0%, #ffee58 100%);
    padding: 4px 8px;
    border-radius: 2px;
    display: inline;
    box-decoration-break: clone;
    -webkit-box-decoration-break: clone;
}

/* Block Kategorie Icon */
.block-editor-block-types-list__item[data-type^="seowk/"] .block-editor-block-types-list__item-icon {
    background: #f0f6fc;
}
CSS;
}

/**
 * Frontend Styles
 */
function seowk_get_semantic_blocks_frontend_style() {
    return <<<CSS
/* Minimale Frontend-Styles - semantische Tags brauchen kein spezielles Styling */

/* Details/Akkordeon natives Styling verbessern */
details.seowk-details {
    margin: 1em 0;
}

details.seowk-details summary {
    cursor: pointer;
    padding: 0.5em;
    font-weight: 600;
    list-style: none;
}

details.seowk-details summary::-webkit-details-marker {
    display: none;
}

details.seowk-details summary::before {
    content: '▶ ';
    display: inline-block;
    transition: transform 0.2s;
}

details.seowk-details[open] summary::before {
    transform: rotate(90deg);
}

details.seowk-details > *:not(summary) {
    padding: 0.5em;
}

/* Mark Standard-Styling */
mark {
    background: linear-gradient(120deg, #fff176 0%, #ffee58 100%);
    padding: 0.1em 0.3em;
    border-radius: 2px;
}

/* Address Styling */
address {
    font-style: normal;
}
CSS;
}

/**
 * Block-Kategorie hinzufügen
 */
function seowk_register_semantic_block_category( $categories ) {
    return array_merge(
        array(
            array(
                'slug'  => 'seowk-semantic',
                'title' => 'Semantische Elemente',
                'icon'  => 'editor-code',
            ),
        ),
        $categories
    );
}
add_filter( 'block_categories_all', 'seowk_register_semantic_block_category', 10, 1 );
