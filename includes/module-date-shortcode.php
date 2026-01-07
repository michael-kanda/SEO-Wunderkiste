<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: DATE SHORTCODE - SEO Wunderkiste
 * Fügt aktuelles Datum via Shortcode ein mit flexiblen Formatierungsoptionen
 * Inklusive Gutenberg Block mit Live-Vorschau
 * ------------------------------------------------------------------------- */

/**
 * Shortcode: [seowk_date]
 * 
 * Attribute:
 * - format: PHP Datumsformat oder vordefinierte Presets (siehe unten)
 * - timezone: Zeitzone (z.B. "Europe/Vienna", "America/New_York")
 * - prefix: Text vor dem Datum
 * - suffix: Text nach dem Datum
 * - wrapper: HTML-Tag für Wrapper (z.B. "span", "time")
 * - class: CSS-Klasse für den Wrapper
 * 
 * Vordefinierte Formate (format="preset_name"):
 * - numeric:       01.02.2026
 * - numeric_short: 1.2.2026
 * - full:          1. Februar 2026
 * - full_day:      Montag, 1. Februar 2026
 * - month_year:    Februar 2026
 * - year:          2026
 * - month:         Februar
 * - day:           Montag
 * - iso:           2026-02-01
 * - us:            02/01/2026
 * - time:          14:30
 * - datetime:      01.02.2026 14:30
 * 
 * Beispiele:
 * [seowk_date]                                    → 01.02.2026 (Standard)
 * [seowk_date format="full"]                      → 1. Februar 2026
 * [seowk_date format="year"]                      → 2026
 * [seowk_date format="full_day" timezone="America/New_York"] → Sonntag, 1. Februar 2026
 * [seowk_date format="d.m.Y"]                     → 01.02.2026 (eigenes Format)
 * [seowk_date format="full" prefix="Stand: "]    → Stand: 1. Februar 2026
 * [seowk_date format="year" wrapper="time" class="current-year"] → <time class="current-year">2026</time>
 */

// Deutsche Monatsnamen
function seowk_get_german_months() {
    return array(
        1  => 'Januar',
        2  => 'Februar',
        3  => 'März',
        4  => 'April',
        5  => 'Mai',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'August',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Dezember'
    );
}

// Deutsche Wochentage
function seowk_get_german_weekdays() {
    return array(
        'Monday'    => 'Montag',
        'Tuesday'   => 'Dienstag',
        'Wednesday' => 'Mittwoch',
        'Thursday'  => 'Donnerstag',
        'Friday'    => 'Freitag',
        'Saturday'  => 'Samstag',
        'Sunday'    => 'Sonntag'
    );
}

// Vordefinierte Datums-Presets
function seowk_get_date_presets() {
    return array(
        'numeric'       => 'd.m.Y',           // 01.02.2026
        'numeric_short' => 'j.n.Y',           // 1.2.2026
        'full'          => 'j. F Y',          // 1. Februar 2026
        'full_day'      => 'l, j. F Y',       // Montag, 1. Februar 2026
        'month_year'    => 'F Y',             // Februar 2026
        'year'          => 'Y',               // 2026
        'month'         => 'F',               // Februar
        'day'           => 'l',               // Montag
        'iso'           => 'Y-m-d',           // 2026-02-01
        'us'            => 'm/d/Y',           // 02/01/2026
        'time'          => 'H:i',             // 14:30
        'datetime'      => 'd.m.Y H:i',       // 01.02.2026 14:30
        'datetime_full' => 'j. F Y, H:i',     // 1. Februar 2026, 14:30
    );
}

// Verfügbare Zeitzonen für Admin-UI
function seowk_get_timezone_options() {
    return array(
        ''                    => 'WordPress Standard',
        'Europe/Vienna'       => 'Wien (Europe/Vienna)',
        'Europe/Berlin'       => 'Berlin (Europe/Berlin)',
        'Europe/Zurich'       => 'Zürich (Europe/Zurich)',
        'Europe/London'       => 'London (Europe/London)',
        'Europe/Paris'        => 'Paris (Europe/Paris)',
        'Europe/Rome'         => 'Rom (Europe/Rome)',
        'Europe/Madrid'       => 'Madrid (Europe/Madrid)',
        'Europe/Amsterdam'    => 'Amsterdam (Europe/Amsterdam)',
        'America/New_York'    => 'New York (America/New_York)',
        'America/Los_Angeles' => 'Los Angeles (America/Los_Angeles)',
        'America/Chicago'     => 'Chicago (America/Chicago)',
        'Asia/Tokyo'          => 'Tokio (Asia/Tokyo)',
        'Asia/Shanghai'       => 'Shanghai (Asia/Shanghai)',
        'Asia/Dubai'          => 'Dubai (Asia/Dubai)',
        'Australia/Sydney'    => 'Sydney (Australia/Sydney)',
        'UTC'                 => 'UTC',
    );
}

/**
 * Generiert das formatierte Datum (wird von Shortcode und Block genutzt)
 */
function seowk_generate_date_output( $atts ) {
    // Attribute mit Defaults
    $atts = wp_parse_args( $atts, array(
        'format'   => 'numeric',
        'timezone' => '',
        'prefix'   => '',
        'suffix'   => '',
        'wrapper'  => '',
        'class'    => '',
        'id'       => '',
        'lang'     => 'de',
    ) );

    // Zeitzone setzen
    if ( ! empty( $atts['timezone'] ) ) {
        try {
            $tz = new DateTimeZone( $atts['timezone'] );
            $datetime = new DateTime( 'now', $tz );
        } catch ( Exception $e ) {
            $datetime = new DateTime( 'now', wp_timezone() );
        }
    } else {
        $datetime = new DateTime( 'now', wp_timezone() );
    }

    // Format ermitteln (Preset oder eigenes Format)
    $presets = seowk_get_date_presets();
    $format = isset( $presets[ $atts['format'] ] ) ? $presets[ $atts['format'] ] : $atts['format'];

    // Datum formatieren
    $date_output = $datetime->format( $format );

    // Deutsche Übersetzung (wenn lang=de)
    if ( $atts['lang'] === 'de' ) {
        $months = seowk_get_german_months();
        $english_months = array( 'January', 'February', 'March', 'April', 'May', 'June', 
                                  'July', 'August', 'September', 'October', 'November', 'December' );
        $date_output = str_replace( $english_months, array_values( $months ), $date_output );

        $weekdays = seowk_get_german_weekdays();
        $date_output = str_replace( array_keys( $weekdays ), array_values( $weekdays ), $date_output );
    }

    // Prefix und Suffix hinzufügen
    $date_output = esc_html( $atts['prefix'] ) . $date_output . esc_html( $atts['suffix'] );

    // Wrapper-Element
    if ( ! empty( $atts['wrapper'] ) ) {
        $allowed_tags = array( 'span', 'time', 'div', 'p', 'strong', 'em', 'b', 'i' );
        $tag = in_array( strtolower( $atts['wrapper'] ), $allowed_tags ) ? strtolower( $atts['wrapper'] ) : 'span';
        
        $attributes = '';
        
        if ( ! empty( $atts['class'] ) ) {
            $attributes .= ' class="' . esc_attr( $atts['class'] ) . '"';
        }
        
        if ( ! empty( $atts['id'] ) ) {
            $attributes .= ' id="' . esc_attr( $atts['id'] ) . '"';
        }
        
        if ( $tag === 'time' ) {
            $attributes .= ' datetime="' . $datetime->format( 'c' ) . '"';
        }
        
        $date_output = '<' . $tag . $attributes . '>' . $date_output . '</' . $tag . '>';
    }

    return $date_output;
}

/**
 * Hauptfunktion: Shortcode Handler
 */
function seowk_date_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'format'   => 'numeric',
            'timezone' => '',
            'prefix'   => '',
            'suffix'   => '',
            'wrapper'  => '',
            'class'    => '',
            'id'       => '',
            'lang'     => 'de',
        ),
        $atts,
        'seowk_date'
    );

    return seowk_generate_date_output( $atts );
}
add_shortcode( 'seowk_date', 'seowk_date_shortcode' );

/**
 * Alternative kürzere Shortcodes für häufige Anwendungen
 */
function seowk_datum_shortcode( $atts ) {
    return seowk_date_shortcode( $atts );
}
add_shortcode( 'datum', 'seowk_datum_shortcode' );

function seowk_jahr_shortcode( $atts ) {
    $atts = is_array( $atts ) ? $atts : array();
    $atts['format'] = 'year';
    return seowk_date_shortcode( $atts );
}
add_shortcode( 'jahr', 'seowk_jahr_shortcode' );

function seowk_monat_shortcode( $atts ) {
    $atts = is_array( $atts ) ? $atts : array();
    $atts['format'] = 'month';
    return seowk_date_shortcode( $atts );
}
add_shortcode( 'monat', 'seowk_monat_shortcode' );


/* ------------------------------------------------------------------------- *
 * GUTENBERG BLOCK - Dynamisches Datum
 * ------------------------------------------------------------------------- */

/**
 * Registriert den Gutenberg Block
 */
function seowk_register_date_block() {
    // Prüfe ob Gutenberg verfügbar ist
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // Block registrieren
    register_block_type( 'seowk/date', array(
        'api_version'     => 2,
        'editor_script'   => 'seowk-date-block-editor',
        'editor_style'    => 'seowk-date-block-editor-style',
        'render_callback' => 'seowk_render_date_block',
        'attributes'      => array(
            'format' => array(
                'type'    => 'string',
                'default' => 'numeric',
            ),
            'timezone' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'prefix' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'suffix' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'wrapper' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'cssClass' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'align' => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ) );

    // Editor Script registrieren
    wp_register_script(
        'seowk-date-block-editor',
        false, // Inline Script
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor' ),
        SEOWK_VERSION
    );

    // Editor Style registrieren
    wp_register_style(
        'seowk-date-block-editor-style',
        false,
        array(),
        SEOWK_VERSION
    );

    // Inline Script für den Block
    wp_add_inline_script( 'seowk-date-block-editor', seowk_get_date_block_script() );
    
    // Inline Style für den Block
    wp_add_inline_style( 'seowk-date-block-editor-style', seowk_get_date_block_style() );
}
add_action( 'init', 'seowk_register_date_block' );

/**
 * Render Callback für den Block (Server-Side Rendering)
 */
function seowk_render_date_block( $attributes ) {
    $atts = array(
        'format'   => isset( $attributes['format'] ) ? $attributes['format'] : 'numeric',
        'timezone' => isset( $attributes['timezone'] ) ? $attributes['timezone'] : '',
        'prefix'   => isset( $attributes['prefix'] ) ? $attributes['prefix'] : '',
        'suffix'   => isset( $attributes['suffix'] ) ? $attributes['suffix'] : '',
        'wrapper'  => isset( $attributes['wrapper'] ) ? $attributes['wrapper'] : '',
        'class'    => isset( $attributes['cssClass'] ) ? $attributes['cssClass'] : '',
        'lang'     => 'de',
    );

    $output = seowk_generate_date_output( $atts );
    
    // Alignment-Wrapper hinzufügen
    $align_class = '';
    if ( ! empty( $attributes['align'] ) ) {
        $align_class = ' has-text-align-' . esc_attr( $attributes['align'] );
    }

    return '<p class="seowk-date-block' . $align_class . '">' . $output . '</p>';
}

/**
 * JavaScript für den Gutenberg Block
 */
function seowk_get_date_block_script() {
    // Aktuelle Vorschau-Daten generieren
    $months = seowk_get_german_months();
    $weekdays = seowk_get_german_weekdays();
    $current_month = $months[ (int) date( 'n' ) ];
    $current_weekday = $weekdays[ date( 'l' ) ];
    
    $preview_data = array(
        'numeric'       => date( 'd.m.Y' ),
        'numeric_short' => date( 'j.n.Y' ),
        'full'          => date( 'j' ) . '. ' . $current_month . ' ' . date( 'Y' ),
        'full_day'      => $current_weekday . ', ' . date( 'j' ) . '. ' . $current_month . ' ' . date( 'Y' ),
        'month_year'    => $current_month . ' ' . date( 'Y' ),
        'year'          => date( 'Y' ),
        'month'         => $current_month,
        'day'           => $current_weekday,
        'iso'           => date( 'Y-m-d' ),
        'us'            => date( 'm/d/Y' ),
        'time'          => date( 'H:i' ),
        'datetime'      => date( 'd.m.Y H:i' ),
        'datetime_full' => date( 'j' ) . '. ' . $current_month . ' ' . date( 'Y' ) . ', ' . date( 'H:i' ),
    );

    $preview_json = json_encode( $preview_data );

    return <<<JS
(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { createElement: el, Fragment } = wp.element;
    const { InspectorControls, BlockControls, AlignmentToolbar } = wp.blockEditor;
    const { PanelBody, SelectControl, TextControl, ToggleControl } = wp.components;
    const { useBlockProps } = wp.blockEditor;

    const previewData = {$preview_json};

    const formatOptions = [
        { label: '01.02.2026 (numerisch)', value: 'numeric' },
        { label: '1.2.2026 (numerisch kurz)', value: 'numeric_short' },
        { label: '1. Februar 2026 (ausgeschrieben)', value: 'full' },
        { label: 'Montag, 1. Februar 2026 (mit Wochentag)', value: 'full_day' },
        { label: 'Februar 2026 (Monat + Jahr)', value: 'month_year' },
        { label: '2026 (nur Jahr)', value: 'year' },
        { label: 'Februar (nur Monat)', value: 'month' },
        { label: 'Montag (nur Wochentag)', value: 'day' },
        { label: '2026-02-01 (ISO Format)', value: 'iso' },
        { label: '02/01/2026 (US Format)', value: 'us' },
        { label: '14:30 (nur Uhrzeit)', value: 'time' },
        { label: '01.02.2026 14:30 (Datum + Zeit)', value: 'datetime' },
        { label: '1. Februar 2026, 14:30 (ausführlich)', value: 'datetime_full' },
    ];

    const timezoneOptions = [
        { label: 'WordPress Standard', value: '' },
        { label: 'Wien (Europe/Vienna)', value: 'Europe/Vienna' },
        { label: 'Berlin (Europe/Berlin)', value: 'Europe/Berlin' },
        { label: 'Zürich (Europe/Zurich)', value: 'Europe/Zurich' },
        { label: 'London (Europe/London)', value: 'Europe/London' },
        { label: 'Paris (Europe/Paris)', value: 'Europe/Paris' },
        { label: 'Rom (Europe/Rome)', value: 'Europe/Rome' },
        { label: 'Madrid (Europe/Madrid)', value: 'Europe/Madrid' },
        { label: 'Amsterdam (Europe/Amsterdam)', value: 'Europe/Amsterdam' },
        { label: 'New York (America/New_York)', value: 'America/New_York' },
        { label: 'Los Angeles (America/Los_Angeles)', value: 'America/Los_Angeles' },
        { label: 'Chicago (America/Chicago)', value: 'America/Chicago' },
        { label: 'Tokio (Asia/Tokyo)', value: 'Asia/Tokyo' },
        { label: 'Shanghai (Asia/Shanghai)', value: 'Asia/Shanghai' },
        { label: 'Dubai (Asia/Dubai)', value: 'Asia/Dubai' },
        { label: 'Sydney (Australia/Sydney)', value: 'Australia/Sydney' },
        { label: 'UTC', value: 'UTC' },
    ];

    const wrapperOptions = [
        { label: 'Kein Wrapper', value: '' },
        { label: '<time> (semantisch)', value: 'time' },
        { label: '<span>', value: 'span' },
        { label: '<strong> (fett)', value: 'strong' },
        { label: '<em> (kursiv)', value: 'em' },
    ];

    registerBlockType('seowk/date', {
        title: 'Dynamisches Datum',
        description: 'Fügt das aktuelle Datum mit verschiedenen Formatierungsoptionen ein.',
        icon: 'calendar-alt',
        category: 'widgets',
        keywords: ['datum', 'date', 'zeit', 'time', 'jahr', 'year', 'monat', 'month'],
        supports: {
            html: false,
            align: ['left', 'center', 'right'],
        },
        attributes: {
            format: { type: 'string', default: 'numeric' },
            timezone: { type: 'string', default: '' },
            prefix: { type: 'string', default: '' },
            suffix: { type: 'string', default: '' },
            wrapper: { type: 'string', default: '' },
            cssClass: { type: 'string', default: '' },
            align: { type: 'string', default: '' },
        },

        edit: function(props) {
            const { attributes, setAttributes } = props;
            const { format, timezone, prefix, suffix, wrapper, cssClass, align } = attributes;
            const blockProps = useBlockProps();

            // Vorschau generieren
            let preview = previewData[format] || previewData.numeric;
            if (timezone) {
                preview += ' *';
            }
            const fullPreview = (prefix || '') + preview + (suffix || '');

            // Wrapper für Vorschau
            let previewElement;
            switch(wrapper) {
                case 'strong':
                    previewElement = el('strong', {}, fullPreview);
                    break;
                case 'em':
                    previewElement = el('em', {}, fullPreview);
                    break;
                case 'time':
                case 'span':
                default:
                    previewElement = fullPreview;
            }

            return el(Fragment, {},
                // Block Controls (Alignment)
                el(BlockControls, {},
                    el(AlignmentToolbar, {
                        value: align,
                        onChange: (newAlign) => setAttributes({ align: newAlign }),
                    })
                ),

                // Inspector Controls (Sidebar)
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Datum-Format', initialOpen: true },
                        el(SelectControl, {
                            label: 'Format',
                            value: format,
                            options: formatOptions,
                            onChange: (value) => setAttributes({ format: value }),
                        }),
                        el(SelectControl, {
                            label: 'Zeitzone',
                            value: timezone,
                            options: timezoneOptions,
                            onChange: (value) => setAttributes({ timezone: value }),
                            help: timezone ? '* Zeitzone kann die angezeigte Zeit beeinflussen' : '',
                        })
                    ),
                    el(PanelBody, { title: 'Text-Optionen', initialOpen: false },
                        el(TextControl, {
                            label: 'Prefix',
                            value: prefix,
                            onChange: (value) => setAttributes({ prefix: value }),
                            placeholder: 'z.B. Stand: ',
                            help: 'Text vor dem Datum',
                        }),
                        el(TextControl, {
                            label: 'Suffix',
                            value: suffix,
                            onChange: (value) => setAttributes({ suffix: value }),
                            placeholder: 'z.B.  Uhr',
                            help: 'Text nach dem Datum',
                        })
                    ),
                    el(PanelBody, { title: 'HTML-Optionen', initialOpen: false },
                        el(SelectControl, {
                            label: 'HTML-Wrapper',
                            value: wrapper,
                            options: wrapperOptions,
                            onChange: (value) => setAttributes({ wrapper: value }),
                            help: '<time> ist semantisch optimal für Datumsangaben',
                        }),
                        el(TextControl, {
                            label: 'CSS-Klasse',
                            value: cssClass,
                            onChange: (value) => setAttributes({ cssClass: value }),
                            placeholder: 'z.B. my-date-class',
                        })
                    )
                ),

                // Block Preview
                el('div', Object.assign({}, blockProps, {
                    className: 'seowk-date-block-preview' + (align ? ' has-text-align-' + align : ''),
                }),
                    el('div', { className: 'seowk-date-icon' }, el('span', { className: 'dashicons dashicons-calendar-alt' })),
                    el('div', { className: 'seowk-date-content' },
                        el('div', { className: 'seowk-date-label' }, 'Dynamisches Datum'),
                        el('div', { className: 'seowk-date-preview' }, previewElement)
                    )
                )
            );
        },

        save: function() {
            // Server-Side Rendering - nothing saved in frontend
            return null;
        },
    });
})(window.wp);
JS;
}

/**
 * CSS für den Gutenberg Block Editor
 */
function seowk_get_date_block_style() {
    return <<<CSS
.seowk-date-block-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: linear-gradient(135deg, #f0f6fc 0%, #e8f4f8 100%);
    border: 1px solid #c3c4c7;
    border-left: 4px solid #2271b1;
    border-radius: 4px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
}

.seowk-date-block-preview.has-text-align-center {
    justify-content: center;
}

.seowk-date-block-preview.has-text-align-right {
    justify-content: flex-end;
}

.seowk-date-icon {
    font-size: 28px;
    line-height: 1;
    color: #2271b1;
}

.seowk-date-icon .dashicons {
    font-size: 28px;
    width: 28px;
    height: 28px;
}

.seowk-date-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.seowk-date-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #1e1e1e;
    opacity: 0.6;
}

.seowk-date-preview {
    font-size: 18px;
    font-weight: 600;
    color: #1e1e1e;
}

.seowk-date-preview strong {
    font-weight: 700;
}

.seowk-date-preview em {
    font-style: italic;
}

/* Frontend Styles */
.seowk-date-block {
    margin: 0;
}
CSS;
}


/* ------------------------------------------------------------------------- *
 * CLASSIC EDITOR SUPPORT (Button + Modal)
 * ------------------------------------------------------------------------- */

/**
 * Fügt einen Shortcode-Generator Button im Classic Editor hinzu
 */
function seowk_date_add_editor_button() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }
    
    if ( get_user_option( 'rich_editing' ) !== 'true' ) {
        return;
    }
    
    add_action( 'media_buttons', 'seowk_date_media_button' );
}
add_action( 'admin_init', 'seowk_date_add_editor_button' );

/**
 * Media Button für Shortcode-Einfügung (Classic Editor)
 */
function seowk_date_media_button() {
    $screen = get_current_screen();
    if ( $screen && ( $screen->base === 'post' || $screen->base === 'page' ) ) {
        ?>
        <button type="button" id="seowk-date-button" class="button" title="Datum Shortcode einfügen" style="padding-left: 6px;">
            <span class="dashicons dashicons-calendar-alt" style="vertical-align: middle; margin-right: 3px;"></span>
            Datum
        </button>
        <?php
    }
}

/**
 * Admin Scripts und Modal für Shortcode-Generator (Classic Editor)
 */
function seowk_date_admin_scripts( $hook ) {
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
        return;
    }
    
    $presets = seowk_get_date_presets();
    $timezones = seowk_get_timezone_options();
    
    $months = seowk_get_german_months();
    $weekdays = seowk_get_german_weekdays();
    $current_month = $months[ (int) date( 'n' ) ];
    $current_weekday = $weekdays[ date( 'l' ) ];
    
    ?>
    <style>
    #seowk-date-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.6);
        z-index: 100100;
    }
    #seowk-date-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 5px 30px rgba(0,0,0,0.3);
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }
    #seowk-date-modal h2 {
        margin-top: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #seowk-date-modal .seowk-field {
        margin-bottom: 15px;
    }
    #seowk-date-modal label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
    }
    #seowk-date-modal select,
    #seowk-date-modal input[type="text"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    #seowk-date-modal .seowk-preview {
        background: #f5f5f5;
        padding: 15px;
        border-radius: 4px;
        margin: 15px 0;
        border-left: 4px solid #2271b1;
    }
    #seowk-date-modal .seowk-preview-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    #seowk-date-modal .seowk-preview-output {
        font-size: 16px;
        font-weight: 600;
    }
    #seowk-date-modal .seowk-shortcode {
        background: #1e1e1e;
        color: #9cdcfe;
        padding: 10px 15px;
        border-radius: 4px;
        font-family: monospace;
        margin: 10px 0;
        word-break: break-all;
    }
    #seowk-date-modal .seowk-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    #seowk-date-modal .seowk-help {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }
    </style>
    
    <div id="seowk-date-modal">
        <div id="seowk-date-modal-content">
            <h2><span class="dashicons dashicons-calendar-alt"></span> Datum Shortcode</h2>
            
            <div class="seowk-field">
                <label for="seowk-date-format">Format</label>
                <select id="seowk-date-format">
                    <option value="numeric">01.02.2026 (numerisch)</option>
                    <option value="numeric_short">1.2.2026 (numerisch kurz)</option>
                    <option value="full">1. Februar 2026 (ausgeschrieben)</option>
                    <option value="full_day">Montag, 1. Februar 2026 (mit Wochentag)</option>
                    <option value="month_year">Februar 2026 (Monat + Jahr)</option>
                    <option value="year">2026 (nur Jahr)</option>
                    <option value="month">Februar (nur Monat)</option>
                    <option value="day">Montag (nur Wochentag)</option>
                    <option value="iso">2026-02-01 (ISO Format)</option>
                    <option value="us">02/01/2026 (US Format)</option>
                    <option value="time">14:30 (nur Uhrzeit)</option>
                    <option value="datetime">01.02.2026 14:30 (Datum + Zeit)</option>
                    <option value="custom">Eigenes Format...</option>
                </select>
            </div>
            
            <div class="seowk-field" id="seowk-custom-format-field" style="display: none;">
                <label for="seowk-custom-format">Eigenes PHP-Datumsformat</label>
                <input type="text" id="seowk-custom-format" placeholder="z.B. d.m.Y H:i:s">
                <p class="seowk-help">PHP Datumsformat. Beispiele: d=Tag, m=Monat, Y=Jahr, H=Stunde, i=Minute</p>
            </div>
            
            <div class="seowk-field">
                <label for="seowk-date-timezone">Zeitzone (optional)</label>
                <select id="seowk-date-timezone">
                    <?php foreach ( $timezones as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="seowk-field">
                <label for="seowk-date-prefix">Prefix (optional)</label>
                <input type="text" id="seowk-date-prefix" placeholder="z.B. Stand: ">
            </div>
            
            <div class="seowk-field">
                <label for="seowk-date-suffix">Suffix (optional)</label>
                <input type="text" id="seowk-date-suffix" placeholder="z.B.  Uhr">
            </div>
            
            <div class="seowk-preview">
                <div class="seowk-preview-label">Vorschau:</div>
                <div class="seowk-preview-output" id="seowk-date-preview">01.01.2026</div>
            </div>
            
            <div class="seowk-shortcode" id="seowk-shortcode-output">[seowk_date]</div>
            
            <div class="seowk-buttons">
                <button type="button" class="button" id="seowk-date-cancel">Abbrechen</button>
                <button type="button" class="button button-primary" id="seowk-date-insert">Einfügen</button>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var previewData = <?php echo json_encode( array(
            'numeric'       => date( 'd.m.Y' ),
            'numeric_short' => date( 'j.n.Y' ),
            'full'          => date( 'j' ) . '. ' . $current_month . ' ' . date( 'Y' ),
            'full_day'      => $current_weekday . ', ' . date( 'j' ) . '. ' . $current_month . ' ' . date( 'Y' ),
            'month_year'    => $current_month . ' ' . date( 'Y' ),
            'year'          => date( 'Y' ),
            'month'         => $current_month,
            'day'           => $current_weekday,
            'iso'           => date( 'Y-m-d' ),
            'us'            => date( 'm/d/Y' ),
            'time'          => date( 'H:i' ),
            'datetime'      => date( 'd.m.Y H:i' ),
        ) ); ?>;
        
        // Modal öffnen
        $('#seowk-date-button').on('click', function(e) {
            e.preventDefault();
            $('#seowk-date-modal').fadeIn(200);
            updatePreview();
        });
        
        // Modal schließen
        $('#seowk-date-cancel, #seowk-date-modal').on('click', function(e) {
            if (e.target === this) {
                $('#seowk-date-modal').fadeOut(200);
            }
        });
        
        // Format-Änderung
        $('#seowk-date-format').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#seowk-custom-format-field').show();
            } else {
                $('#seowk-custom-format-field').hide();
            }
            updatePreview();
        });
        
        // Vorschau aktualisieren
        $('#seowk-date-format, #seowk-date-timezone, #seowk-date-prefix, #seowk-date-suffix, #seowk-custom-format').on('change keyup', updatePreview);
        
        function updatePreview() {
            var format = $('#seowk-date-format').val();
            var timezone = $('#seowk-date-timezone').val();
            var prefix = $('#seowk-date-prefix').val();
            var suffix = $('#seowk-date-suffix').val();
            var customFormat = $('#seowk-custom-format').val();
            
            // Vorschau-Text
            var preview = format === 'custom' ? '[Eigenes Format]' : (previewData[format] || format);
            $('#seowk-date-preview').text(prefix + preview + suffix);
            
            // Shortcode generieren
            var shortcode = '[seowk_date';
            
            if (format !== 'numeric') {
                if (format === 'custom' && customFormat) {
                    shortcode += ' format="' + customFormat + '"';
                } else if (format !== 'custom') {
                    shortcode += ' format="' + format + '"';
                }
            }
            
            if (timezone) {
                shortcode += ' timezone="' + timezone + '"';
            }
            
            if (prefix) {
                shortcode += ' prefix="' + prefix + '"';
            }
            
            if (suffix) {
                shortcode += ' suffix="' + suffix + '"';
            }
            
            shortcode += ']';
            
            $('#seowk-shortcode-output').text(shortcode);
        }
        
        // Shortcode einfügen (nur Classic Editor)
        $('#seowk-date-insert').on('click', function() {
            var shortcode = $('#seowk-shortcode-output').text();
            
            // Classic Editor (TinyMCE)
            if (typeof tinymce !== 'undefined' && tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
                tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode);
            }
            // Text Editor
            else {
                var textarea = document.getElementById('content');
                if (textarea) {
                    var pos = textarea.selectionStart;
                    textarea.value = textarea.value.substring(0, pos) + shortcode + textarea.value.substring(pos);
                }
            }
            
            $('#seowk-date-modal').fadeOut(200);
        });
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'seowk_date_admin_scripts' );


/* ------------------------------------------------------------------------- *
 * DOKUMENTATION IM ADMIN-BEREICH
 * ------------------------------------------------------------------------- */

/**
 * Hilfe-Tab für Datum Shortcode
 */
function seowk_date_add_help_tab() {
    $screen = get_current_screen();
    
    if ( ! $screen || ! in_array( $screen->base, array( 'post', 'page' ) ) ) {
        return;
    }
    
    $screen->add_help_tab( array(
        'id'      => 'seowk_date_help',
        'title'   => '📅 Datum Shortcode',
        'content' => '
            <h3>Datum Shortcode & Block (SEO Wunderkiste)</h3>
            <p>Füge das aktuelle Datum dynamisch in deine Inhalte ein.</p>
            
            <h4>Gutenberg Block:</h4>
            <p>Suche nach "Dynamisches Datum" oder "Datum" in der Block-Bibliothek. Der Block bietet eine visuelle Oberfläche mit Live-Vorschau.</p>
            
            <h4>Basis-Shortcodes:</h4>
            <ul>
                <li><code>[seowk_date]</code> - Datum im Standardformat (01.02.2026)</li>
                <li><code>[datum]</code> - Alias für seowk_date</li>
                <li><code>[jahr]</code> - Nur das Jahr (2026)</li>
                <li><code>[monat]</code> - Nur der Monat (Februar)</li>
            </ul>
            
            <h4>Format-Optionen:</h4>
            <ul>
                <li><code>numeric</code> - 01.02.2026</li>
                <li><code>numeric_short</code> - 1.2.2026</li>
                <li><code>full</code> - 1. Februar 2026</li>
                <li><code>full_day</code> - Montag, 1. Februar 2026</li>
                <li><code>month_year</code> - Februar 2026</li>
                <li><code>year</code> - 2026</li>
                <li><code>iso</code> - 2026-02-01</li>
            </ul>
            
            <h4>Beispiele:</h4>
            <ul>
                <li><code>[seowk_date format="full"]</code> → 1. Februar 2026</li>
                <li><code>[seowk_date format="year" prefix="© "]</code> → © 2026</li>
                <li><code>[seowk_date timezone="America/New_York"]</code> → New Yorker Zeit</li>
            </ul>
        ',
    ) );
}
add_action( 'admin_head', 'seowk_date_add_help_tab' );
