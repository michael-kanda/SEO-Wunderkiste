<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: SEO Meta Settings
 * Erweiterte Meta-Tags für Title, Description, Open Graph, Twitter Cards
 * und weitere SEO-relevante Meta-Informationen pro Seite/Beitrag
 * ------------------------------------------------------------------------- */

// 1. Meta-Box im Editor hinzufügen (Posts und Pages)
function seowk_meta_add_meta_box() {
    $screens = array( 'post', 'page' );
    
    foreach ( $screens as $screen ) {
        add_meta_box(
            'seowk_meta_box',
            '🔍 SEO Meta Einstellungen',
            'seowk_meta_render_meta_box',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'seowk_meta_add_meta_box' );

// 2. Meta-Box HTML ausgeben
function seowk_meta_render_meta_box( $post ) {
    // Nonce für Sicherheit
    wp_nonce_field( 'seowk_meta_save', 'seowk_meta_nonce' );
    
    // Gespeicherte Werte abrufen
    $meta_title = get_post_meta( $post->ID, '_seowk_meta_title', true );
    $meta_description = get_post_meta( $post->ID, '_seowk_meta_description', true );
    $meta_robots = get_post_meta( $post->ID, '_seowk_meta_robots', true );
    $meta_canonical = get_post_meta( $post->ID, '_seowk_meta_canonical', true );
    
    // Open Graph
    $og_title = get_post_meta( $post->ID, '_seowk_og_title', true );
    $og_description = get_post_meta( $post->ID, '_seowk_og_description', true );
    $og_image = get_post_meta( $post->ID, '_seowk_og_image', true );
    $og_image_alt = get_post_meta( $post->ID, '_seowk_og_image_alt', true );
    $og_type = get_post_meta( $post->ID, '_seowk_og_type', true );
    
    // Twitter
    $twitter_card = get_post_meta( $post->ID, '_seowk_twitter_card', true );
    $twitter_title = get_post_meta( $post->ID, '_seowk_twitter_title', true );
    $twitter_description = get_post_meta( $post->ID, '_seowk_twitter_description', true );
    $twitter_image = get_post_meta( $post->ID, '_seowk_twitter_image', true );
    
    // Zusätzliche Meta
    $meta_author = get_post_meta( $post->ID, '_seowk_meta_author', true );
    $meta_copyright = get_post_meta( $post->ID, '_seowk_meta_copyright', true );
    
    // Standard-Werte
    if ( empty( $meta_robots ) ) $meta_robots = 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    if ( empty( $og_type ) ) $og_type = 'website';
    if ( empty( $twitter_card ) ) $twitter_card = 'summary_large_image';
    
    ?>
    <div class="seowk-meta-box" style="padding: 15px 0;">
        
        <!-- TABS NAVIGATION -->
        <div class="seowk-tabs" style="border-bottom: 1px solid #ccc; margin-bottom: 20px;">
            <button type="button" class="seowk-tab-btn active" data-tab="basic" style="padding: 10px 20px; border: none; background: #f0f0f0; cursor: pointer; font-weight: 600;">📝 Basis SEO</button>
            <button type="button" class="seowk-tab-btn" data-tab="opengraph" style="padding: 10px 20px; border: none; background: transparent; cursor: pointer;">📘 Open Graph</button>
            <button type="button" class="seowk-tab-btn" data-tab="twitter" style="padding: 10px 20px; border: none; background: transparent; cursor: pointer;">𝕏 X (Twitter)</button>
            <button type="button" class="seowk-tab-btn" data-tab="advanced" style="padding: 10px 20px; border: none; background: transparent; cursor: pointer;">⚙️ Erweitert</button>
        </div>
        
        <!-- TAB: BASIC SEO -->
        <div class="seowk-tab-content active" data-tab="basic">
            <h4 style="margin-top: 0; color: #1d2327;">Basis SEO Einstellungen</h4>
            
            <!-- SONDERZEICHEN BOX -->
            <div style="background: #f9f9f9; border: 1px solid #ddd; padding: 12px 15px; margin-bottom: 20px; border-radius: 4px;">
                <strong style="display: block; margin-bottom: 8px;">📋 Sonderzeichen zum Kopieren <span style="font-weight: normal; color: #666;">(Klick = Kopieren)</span>:</strong>
                <div class="seowk-special-chars" style="font-size: 18px; line-height: 2.2;">
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Bullet">•</span>
                        <span class="seowk-char" title="Triangular Bullet">‣</span>
                        <span class="seowk-char" title="White Bullet">◦</span>
                        <span class="seowk-char" title="Black Square">▪</span>
                        <span class="seowk-char" title="Triangle Right">▸</span>
                        <span class="seowk-char" title="Triangle Right Filled">►</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Checkmark">✓</span>
                        <span class="seowk-char" title="Heavy Checkmark">✔</span>
                        <span class="seowk-char" title="Checkbox Checked">☑</span>
                        <span class="seowk-char" title="Cross">✗</span>
                        <span class="seowk-char" title="Heavy Cross">✘</span>
                        <span class="seowk-char" title="Checkbox X">☒</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Arrow Right">→</span>
                        <span class="seowk-char" title="Arrow Left">←</span>
                        <span class="seowk-char" title="Arrow Up">↑</span>
                        <span class="seowk-char" title="Arrow Down">↓</span>
                        <span class="seowk-char" title="Double Arrow Right">⇒</span>
                        <span class="seowk-char" title="Double Arrow Left">⇐</span>
                        <span class="seowk-char" title="Heavy Arrow">➔</span>
                        <span class="seowk-char" title="Heavy Arrow 2">➜</span>
                        <span class="seowk-char" title="Triangle Arrow">➤</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Star Filled">★</span>
                        <span class="seowk-char" title="Star Empty">☆</span>
                        <span class="seowk-char" title="Four Star">✦</span>
                        <span class="seowk-char" title="Four Star Empty">✧</span>
                        <span class="seowk-char" title="Star Emoji">⭐</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Heart Filled">♥</span>
                        <span class="seowk-char" title="Heart Empty">♡</span>
                        <span class="seowk-char" title="Heart Emoji">❤</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Phone">☎</span>
                        <span class="seowk-char" title="Phone 2">✆</span>
                        <span class="seowk-char" title="Envelope">✉</span>
                        <span class="seowk-char" title="Phone Emoji">📞</span>
                        <span class="seowk-char" title="Email Emoji">📧</span>
                        <span class="seowk-char" title="Location">📍</span>
                        <span class="seowk-char" title="House">🏠</span>
                        <span class="seowk-char" title="Building">🏢</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Euro">€</span>
                        <span class="seowk-char" title="Dollar">$</span>
                        <span class="seowk-char" title="Pound">£</span>
                        <span class="seowk-char" title="Yen">¥</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Copyright">©</span>
                        <span class="seowk-char" title="Registered">®</span>
                        <span class="seowk-char" title="Trademark">™</span>
                        <span class="seowk-char" title="Paragraph">§</span>
                        <span class="seowk-char" title="Pilcrow">¶</span>
                        <span class="seowk-char" title="Degree">°</span>
                        <span class="seowk-char" title="Numero">№</span>
                        <span class="seowk-char" title="Per Mille">‰</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="Pipe">|</span>
                        <span class="seowk-char" title="Middle Dot">·</span>
                        <span class="seowk-char" title="Em Dash">—</span>
                        <span class="seowk-char" title="En Dash">–</span>
                        <span class="seowk-char" title="Bullet Operator">∙</span>
                    </span>
                    <span class="seowk-char-sep">|</span>
                    <span class="seowk-char-group">
                        <span class="seowk-char" title="German Open Quote">„</span>
                        <span class="seowk-char" title="German Close Quote">"</span>
                        <span class="seowk-char" title="Single Open Quote">‚</span>
                        <span class="seowk-char" title="Single Close Quote">'</span>
                        <span class="seowk-char" title="Guillemet Left">»</span>
                        <span class="seowk-char" title="Guillemet Right">«</span>
                        <span class="seowk-char" title="Single Guillemet Left">›</span>
                        <span class="seowk-char" title="Single Guillemet Right">‹</span>
                    </span>
                </div>
                <p class="seowk-copy-feedback" style="font-size: 12px; color: #00a32a; margin: 8px 0 0 0; display: none; font-weight: 600;">
                    ✓ Kopiert!
                </p>
            </div>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    SEO Title <span style="color: #999; font-weight: normal;">(max. 60 Zeichen empfohlen)</span>
                </label>
                <input type="text" name="seowk_meta_title" value="<?php echo esc_attr( $meta_title ); ?>" class="large-text seowk-char-count" data-max="60" placeholder="Dein optimierter Seitentitel | Firmenname" />
                <span class="seowk-char-counter" style="font-size: 12px; color: #666;"></span>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    Meta Description <span style="color: #999; font-weight: normal;">(max. 160 Zeichen empfohlen)</span>
                </label>
                <textarea name="seowk_meta_description" rows="3" class="large-text seowk-char-count" data-max="160" placeholder="Beschreibe den Inhalt dieser Seite für Suchmaschinen..."><?php echo esc_textarea( $meta_description ); ?></textarea>
                <span class="seowk-char-counter" style="font-size: 12px; color: #666;"></span>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    Robots Meta Tag
                </label>
                <input type="text" name="seowk_meta_robots" value="<?php echo esc_attr( $meta_robots ); ?>" class="large-text" />
                <span style="font-size: 12px; color: #666; display: block; margin-top: 3px;">
                    Standard: <code>index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1</code>
                </span>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    Canonical URL <span style="color: #999; font-weight: normal;">(leer = automatisch)</span>
                </label>
                <input type="url" name="seowk_meta_canonical" value="<?php echo esc_attr( $meta_canonical ); ?>" class="large-text" placeholder="https://www.example.com/seite/" />
                <span style="font-size: 12px; color: #666; display: block; margin-top: 3px;">
                    Nur ausfüllen, wenn eine andere URL als Hauptversion gelten soll.
                </span>
            </p>
        </div>
        
        <!-- TAB: OPEN GRAPH -->
        <div class="seowk-tab-content" data-tab="opengraph" style="display: none;">
            <h4 style="margin-top: 0; color: #1d2327;">Open Graph (Facebook, LinkedIn, etc.)</h4>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    OG Title <span style="color: #999; font-weight: normal;">(leer = SEO Title verwenden)</span>
                </label>
                <input type="text" name="seowk_og_title" value="<?php echo esc_attr( $og_title ); ?>" class="large-text" placeholder="Titel für Social Media Shares" />
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    OG Description <span style="color: #999; font-weight: normal;">(leer = Meta Description verwenden)</span>
                </label>
                <textarea name="seowk_og_description" rows="2" class="large-text" placeholder="Beschreibung für Social Media Shares"><?php echo esc_textarea( $og_description ); ?></textarea>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    OG Type
                </label>
                <select name="seowk_og_type" class="regular-text">
                    <option value="website" <?php selected( $og_type, 'website' ); ?>>website</option>
                    <option value="article" <?php selected( $og_type, 'article' ); ?>>article</option>
                    <option value="product" <?php selected( $og_type, 'product' ); ?>>product</option>
                    <option value="profile" <?php selected( $og_type, 'profile' ); ?>>profile</option>
                    <option value="video" <?php selected( $og_type, 'video' ); ?>>video</option>
                </select>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    OG Image URL <span style="color: #999; font-weight: normal;">(1200x630px empfohlen)</span>
                </label>
                <input type="url" name="seowk_og_image" id="seowk_og_image" value="<?php echo esc_attr( $og_image ); ?>" class="large-text" placeholder="https://www.example.com/bild.jpg" />
                <button type="button" class="button seowk-media-upload" data-target="seowk_og_image" style="margin-top: 5px;">Bild auswählen</button>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    OG Image Alt Text
                </label>
                <input type="text" name="seowk_og_image_alt" value="<?php echo esc_attr( $og_image_alt ); ?>" class="large-text" placeholder="Beschreibung des Bildes" />
            </p>
        </div>
        
        <!-- TAB: TWITTER -->
        <div class="seowk-tab-content" data-tab="twitter" style="display: none;">
            <h4 style="margin-top: 0; color: #1d2327;">𝕏 X (Twitter) Card Einstellungen</h4>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    X Card Type
                </label>
                <select name="seowk_twitter_card" class="regular-text">
                    <option value="summary_large_image" <?php selected( $twitter_card, 'summary_large_image' ); ?>>summary_large_image (Großes Bild)</option>
                    <option value="summary" <?php selected( $twitter_card, 'summary' ); ?>>summary (Kleines Bild)</option>
                </select>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    X Title <span style="color: #999; font-weight: normal;">(leer = OG/SEO Title verwenden)</span>
                </label>
                <input type="text" name="seowk_twitter_title" value="<?php echo esc_attr( $twitter_title ); ?>" class="large-text" placeholder="Titel für X" />
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    X Description <span style="color: #999; font-weight: normal;">(leer = OG/Meta Description verwenden)</span>
                </label>
                <textarea name="seowk_twitter_description" rows="2" class="large-text" placeholder="Beschreibung für X"><?php echo esc_textarea( $twitter_description ); ?></textarea>
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    X Image URL <span style="color: #999; font-weight: normal;">(leer = OG Image verwenden)</span>
                </label>
                <input type="url" name="seowk_twitter_image" id="seowk_twitter_image" value="<?php echo esc_attr( $twitter_image ); ?>" class="large-text" placeholder="https://www.example.com/twitter-bild.jpg" />
                <button type="button" class="button seowk-media-upload" data-target="seowk_twitter_image" style="margin-top: 5px;">Bild auswählen</button>
            </p>
        </div>
        
        <!-- TAB: ADVANCED -->
        <div class="seowk-tab-content" data-tab="advanced" style="display: none;">
            <h4 style="margin-top: 0; color: #1d2327;">Erweiterte Meta-Tags</h4>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    Author <span style="color: #999; font-weight: normal;">(optional)</span>
                </label>
                <input type="text" name="seowk_meta_author" value="<?php echo esc_attr( $meta_author ); ?>" class="large-text" placeholder="Firmenname oder Autorname" />
            </p>
            
            <p style="margin: 15px 0 5px 0;">
                <label style="font-weight: 600; display: block; margin-bottom: 5px;">
                    Copyright <span style="color: #999; font-weight: normal;">(optional)</span>
                </label>
                <input type="text" name="seowk_meta_copyright" value="<?php echo esc_attr( $meta_copyright ); ?>" class="large-text" placeholder="Firmenname" />
            </p>
            
            <div style="background: #f0f6fc; border-left: 3px solid #2271b1; padding: 12px; margin-top: 20px;">
                <strong>💡 Hinweis:</strong> Die folgenden Meta-Tags werden automatisch generiert:
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li><code>og:url</code> - Aktuelle Seiten-URL</li>
                    <li><code>og:site_name</code> - WordPress Seitenname</li>
                    <li><code>og:locale</code> - Spracheinstellung (de_DE)</li>
                </ul>
            </div>
        </div>
        
        <!-- PREVIEW BOX -->
        <div style="background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-top: 20px; border-radius: 4px;">
            <h4 style="margin: 0 0 10px 0;">👁️ Google Vorschau</h4>
            <div id="seowk-preview" style="font-family: Arial, sans-serif;">
                <div style="color: #1a0dab; font-size: 18px; line-height: 1.3;" id="preview-title"><?php echo esc_html( $meta_title ?: get_the_title( $post->ID ) ); ?></div>
                <div style="color: #006621; font-size: 14px; margin: 3px 0;"><?php echo esc_url( get_permalink( $post->ID ) ); ?></div>
                <div style="color: #545454; font-size: 13px; line-height: 1.4;" id="preview-desc"><?php echo esc_html( $meta_description ?: 'Füge eine Meta Description hinzu...' ); ?></div>
            </div>
        </div>
        
    </div>
    
    <style>
    .seowk-tab-btn.active {
        background: #f0f0f0 !important;
        border-bottom: 2px solid #2271b1 !important;
    }
    .seowk-char-counter.warning { color: #d63638 !important; font-weight: bold; }
    .seowk-char-counter.ok { color: #00a32a !important; }
    
    /* Sonderzeichen Styles */
    .seowk-special-chars { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
    .seowk-char-group { display: inline-flex; gap: 2px; }
    .seowk-char-sep { color: #ccc; margin: 0 6px; font-size: 14px; }
    .seowk-char {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.15s ease;
        background: #fff;
        border: 1px solid #ddd;
    }
    .seowk-char:hover {
        background: #2271b1;
        color: #fff;
        border-color: #2271b1;
        transform: scale(1.15);
    }
    .seowk-char:active {
        transform: scale(0.95);
        background: #135e96;
    }
    .seowk-char.copied {
        background: #00a32a !important;
        border-color: #00a32a !important;
        color: #fff !important;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab Navigation
        $('.seowk-tab-btn').on('click', function() {
            var tab = $(this).data('tab');
            $('.seowk-tab-btn').removeClass('active').css('background', 'transparent');
            $(this).addClass('active').css('background', '#f0f0f0');
            $('.seowk-tab-content').hide();
            $('.seowk-tab-content[data-tab="' + tab + '"]').show();
        });
        
        // Character Counter
        $('.seowk-char-count').each(function() {
            var $input = $(this);
            var $counter = $input.siblings('.seowk-char-counter');
            var max = parseInt($input.data('max'));
            
            function updateCounter() {
                var len = $input.val().length;
                $counter.text(len + '/' + max + ' Zeichen');
                if (len > max) {
                    $counter.addClass('warning').removeClass('ok');
                } else if (len > 0) {
                    $counter.addClass('ok').removeClass('warning');
                } else {
                    $counter.removeClass('ok warning');
                }
            }
            
            $input.on('input', updateCounter);
            updateCounter();
        });
        
        // Live Preview
        $('input[name="seowk_meta_title"]').on('input', function() {
            var title = $(this).val() || '<?php echo esc_js( get_the_title( $post->ID ) ); ?>';
            $('#preview-title').text(title);
        });
        
        $('textarea[name="seowk_meta_description"]').on('input', function() {
            var desc = $(this).val() || 'Füge eine Meta Description hinzu...';
            $('#preview-desc').text(desc);
        });
        
        // Media Upload
        $('.seowk-media-upload').on('click', function(e) {
            e.preventDefault();
            var targetId = $(this).data('target');
            var frame = wp.media({
                title: 'Bild auswählen',
                button: { text: 'Bild verwenden' },
                multiple: false
            });
            
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#' + targetId).val(attachment.url);
            });
            
            frame.open();
        });
        
        // Sonderzeichen Copy-to-Clipboard
        $('.seowk-char').on('click', function() {
            var $char = $(this);
            var char = $char.text();
            
            // In Zwischenablage kopieren
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(char).then(function() {
                    showCopyFeedback($char);
                });
            } else {
                // Fallback für ältere Browser
                var tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(char).select();
                document.execCommand('copy');
                tempInput.remove();
                showCopyFeedback($char);
            }
        });
        
        function showCopyFeedback($char) {
            // Visuelles Feedback am Zeichen
            $char.addClass('copied');
            setTimeout(function() {
                $char.removeClass('copied');
            }, 300);
            
            // Feedback-Text anzeigen
            var $feedback = $('.seowk-copy-feedback');
            $feedback.text('✓ „' + $char.text() + '" kopiert!').fadeIn(100);
            setTimeout(function() {
                $feedback.fadeOut(300);
            }, 1200);
        }
    });
    </script>
    <?php
}

// 3. Media Upload Script laden
function seowk_meta_enqueue_media() {
    global $pagenow;
    if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'seowk_meta_enqueue_media' );

// 4. Meta-Daten speichern
function seowk_meta_save_data( $post_id ) {
    // Security Checks
    if ( ! isset( $_POST['seowk_meta_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['seowk_meta_nonce'], 'seowk_meta_save' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Felder definieren
    $fields = array(
        'seowk_meta_title',
        'seowk_meta_description',
        'seowk_meta_robots',
        'seowk_meta_canonical',
        'seowk_og_title',
        'seowk_og_description',
        'seowk_og_image',
        'seowk_og_image_alt',
        'seowk_og_type',
        'seowk_twitter_card',
        'seowk_twitter_title',
        'seowk_twitter_description',
        'seowk_twitter_image',
        'seowk_meta_author',
        'seowk_meta_copyright',
    );
    
    // Alle Felder speichern
    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = sanitize_text_field( $_POST[ $field ] );
            if ( ! empty( $value ) ) {
                update_post_meta( $post_id, '_' . $field, $value );
            } else {
                delete_post_meta( $post_id, '_' . $field );
            }
        }
    }
}
add_action( 'save_post', 'seowk_meta_save_data' );

// 5. Meta-Tags im Frontend ausgeben
function seowk_meta_output_head() {
    if ( ! is_singular() ) {
        return;
    }
    
    $post_id = get_the_ID();
    
    // Werte abrufen
    $meta_title = get_post_meta( $post_id, '_seowk_meta_title', true );
    $meta_description = get_post_meta( $post_id, '_seowk_meta_description', true );
    $meta_robots = get_post_meta( $post_id, '_seowk_meta_robots', true );
    $meta_canonical = get_post_meta( $post_id, '_seowk_meta_canonical', true );
    
    $og_title = get_post_meta( $post_id, '_seowk_og_title', true );
    $og_description = get_post_meta( $post_id, '_seowk_og_description', true );
    $og_image = get_post_meta( $post_id, '_seowk_og_image', true );
    $og_image_alt = get_post_meta( $post_id, '_seowk_og_image_alt', true );
    $og_type = get_post_meta( $post_id, '_seowk_og_type', true );
    
    $twitter_card = get_post_meta( $post_id, '_seowk_twitter_card', true );
    $twitter_title = get_post_meta( $post_id, '_seowk_twitter_title', true );
    $twitter_description = get_post_meta( $post_id, '_seowk_twitter_description', true );
    $twitter_image = get_post_meta( $post_id, '_seowk_twitter_image', true );
    
    $meta_author = get_post_meta( $post_id, '_seowk_meta_author', true );
    $meta_copyright = get_post_meta( $post_id, '_seowk_meta_copyright', true );
    
    // Fallbacks
    $site_name = get_bloginfo( 'name' );
    $current_url = get_permalink( $post_id );
    $page_title = get_the_title( $post_id );
    
    // Thumbnail als Fallback für OG Image
    if ( empty( $og_image ) && has_post_thumbnail( $post_id ) ) {
        $og_image = get_the_post_thumbnail_url( $post_id, 'large' );
    }
    
    echo "\n<!-- SEO Wunderkiste - Meta Settings -->\n";
    
    // Meta Description
    if ( ! empty( $meta_description ) ) {
        echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
    }
    
    // Robots
    if ( ! empty( $meta_robots ) ) {
        echo '<meta name="robots" content="' . esc_attr( $meta_robots ) . '">' . "\n";
    }
    
    // Canonical
    $canonical = ! empty( $meta_canonical ) ? $meta_canonical : $current_url;
    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
    
    // Open Graph
    echo '<meta property="og:locale" content="de_DE">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $og_type ?: 'website' ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $og_title ?: $meta_title ?: $page_title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $og_description ?: $meta_description ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $current_url ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
    
    if ( ! empty( $og_image ) ) {
        echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
        if ( ! empty( $og_image_alt ) ) {
            echo '<meta property="og:image:alt" content="' . esc_attr( $og_image_alt ) . '">' . "\n";
        }
    }
    
    // Twitter Card
    echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card ?: 'summary_large_image' ) . '">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $twitter_title ?: $og_title ?: $meta_title ?: $page_title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $twitter_description ?: $og_description ?: $meta_description ) . '">' . "\n";
    
    $tw_image = $twitter_image ?: $og_image;
    if ( ! empty( $tw_image ) ) {
        echo '<meta name="twitter:image" content="' . esc_url( $tw_image ) . '">' . "\n";
    }
    
    // Author & Copyright
    if ( ! empty( $meta_author ) ) {
        echo '<meta name="author" content="' . esc_attr( $meta_author ) . '">' . "\n";
    }
    
    if ( ! empty( $meta_copyright ) ) {
        echo '<meta name="copyright" content="' . esc_attr( $meta_copyright ) . '">' . "\n";
    }
    
    // Zusätzliche Standard-Tags
    echo '<meta name="format-detection" content="telephone=yes">' . "\n";
    
    echo "<!-- /SEO Wunderkiste -->\n\n";
}
add_action( 'wp_head', 'seowk_meta_output_head', 1 );

// 6. Title-Tag Filter (optional - überschreibt WordPress Title)
function seowk_meta_document_title( $title ) {
    if ( ! is_singular() ) {
        return $title;
    }
    
    $post_id = get_the_ID();
    $meta_title = get_post_meta( $post_id, '_seowk_meta_title', true );
    
    if ( ! empty( $meta_title ) ) {
        return $meta_title;
    }
    
    return $title;
}
add_filter( 'pre_get_document_title', 'seowk_meta_document_title', 999 );

// 7. Admin-Spalte: Zeigt SEO-Status an
function seowk_meta_add_admin_column( $columns ) {
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        
        if ( $key === 'title' ) {
            $new_columns['seowk_seo_status'] = '🔍 SEO';
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_posts_columns', 'seowk_meta_add_admin_column' );
add_filter( 'manage_pages_columns', 'seowk_meta_add_admin_column' );

// 8. Admin-Spalte füllen
function seowk_meta_fill_admin_column( $column_name, $post_id ) {
    if ( 'seowk_seo_status' !== $column_name ) {
        return;
    }
    
    $has_title = get_post_meta( $post_id, '_seowk_meta_title', true );
    $has_desc = get_post_meta( $post_id, '_seowk_meta_description', true );
    $has_og = get_post_meta( $post_id, '_seowk_og_image', true );
    
    $score = 0;
    if ( ! empty( $has_title ) ) $score++;
    if ( ! empty( $has_desc ) ) $score++;
    if ( ! empty( $has_og ) ) $score++;
    
    if ( $score === 3 ) {
        echo '<span style="color: #00a32a;" title="Vollständig optimiert">✓✓✓</span>';
    } elseif ( $score === 2 ) {
        echo '<span style="color: #dba617;" title="Teilweise optimiert">✓✓</span>';
    } elseif ( $score === 1 ) {
        echo '<span style="color: #d63638;" title="Minimal optimiert">✓</span>';
    } else {
        echo '<span style="color: #ccc;" title="Nicht optimiert">—</span>';
    }
}
add_action( 'manage_posts_custom_column', 'seowk_meta_fill_admin_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'seowk_meta_fill_admin_column', 10, 2 );

// 9. CSS für Admin-Spalte
function seowk_meta_admin_css() {
    echo '<style>
        .column-seowk_seo_status { 
            width: 60px; 
            text-align: center;
        }
    </style>';
}
add_action( 'admin_head', 'seowk_meta_admin_css' );
