<?php if ( ! defined( 'ABSPATH' ) ) exit;
$i        = 'DCB_I18n';
$settings = DCB_Cookie_Manager::get_settings();
$cats     = $settings['categories'];
$defaults = DCB_Embeds::default_embed_types();
$lang     = DCB_I18n::get_lang();
?>
<div class="wrap dcb-admin-wrap">
    <h1>🖼️ <?php echo $lang === 'de' ? 'Einbettungs-Platzhalter' : 'Embed Placeholders'; ?></h1>
    <p><?php echo $lang === 'de'
        ? 'Verwalten Sie die Datenschutz-Platzhalter für YouTube, Google Maps und andere externe Einbettungen. Jeder Typ hat einen eigenen Shortcode.'
        : 'Manage the privacy placeholders for YouTube, Google Maps and other external embeds. Each type has its own shortcode.'; ?>
    </p>

    <!-- ── Shortcode Referenz ── -->
    <div class="dcb-embed-shortcode-ref">
        <h3><?php echo $lang === 'de' ? '📋 Shortcode-Übersicht' : '📋 Shortcode Reference'; ?></h3>
        <div class="dcb-sc-grid">
            <?php foreach ( $embeds as $id => $embed ) : if ( empty( $embed['enabled'] ) ) continue; ?>
            <div class="dcb-sc-chip">
                <span class="dcb-sc-icon"><?php echo esc_html( $embed['icon'] ); ?></span>
                <span class="dcb-sc-label"><?php echo esc_html( $embed['label'] ); ?></span>
                <code class="dcb-sc-tag">[dcb_<?php echo esc_html( $id ); ?> …]</code>
            </div>
            <?php endforeach; ?>
        </div>

        <details class="dcb-sc-examples">
            <summary><?php echo $lang === 'de' ? '📖 Beispiele anzeigen' : '📖 Show examples'; ?></summary>
            <div class="dcb-sc-examples-body">
                <table class="widefat">
                    <thead><tr>
                        <th><?php echo $lang === 'de' ? 'Typ' : 'Type'; ?></th>
                        <th><?php echo $lang === 'de' ? 'Shortcode-Beispiel' : 'Shortcode Example'; ?></th>
                        <th><?php echo $lang === 'de' ? 'Parameter' : 'Parameters'; ?></th>
                    </tr></thead>
                    <tbody>
                        <tr><td><strong>YouTube</strong></td><td><code>[dcb_youtube id="dQw4w9WgXcQ"]</code></td><td>id, width, height, title, thumbnail</td></tr>
                        <tr><td><strong>Vimeo</strong></td><td><code>[dcb_vimeo id="123456789"]</code></td><td>id, width, height</td></tr>
                        <tr><td><strong>Google Maps</strong></td><td><code>[dcb_googlemaps src="https://maps.google.com/maps?q=Berlin&output=embed"]</code></td><td>src, width, height</td></tr>
                        <tr><td><strong>OpenStreetMap</strong></td><td><code>[dcb_openstreetmap lat="52.52" lng="13.40" zoom="14"]</code></td><td>lat, lng, zoom, width, height</td></tr>
                        <tr><td><strong>Instagram</strong></td><td><code>[dcb_instagram url="https://www.instagram.com/p/ABC123/"]</code></td><td>url</td></tr>
                        <tr><td><strong>X / Twitter</strong></td><td><code>[dcb_twitter url="https://twitter.com/user/status/123"]</code></td><td>url</td></tr>
                        <tr><td><strong>Facebook</strong></td><td><code>[dcb_facebook url="https://www.facebook.com/post/123"]</code></td><td>url</td></tr>
                        <tr><td><strong><?php echo $lang === 'de' ? 'Generisch' : 'Generic'; ?></strong></td><td><code>[dcb_embed type="youtube" id="dQw4w9WgXcQ"]</code></td><td>type, id, src, url, width, height</td></tr>
                    </tbody>
                </table>
            </div>
        </details>
    </div>

    <!-- ── Einbettungs-Typen ── -->
    <div id="dcb-embed-list">
    <?php foreach ( $embeds as $id => $embed ) :
        $is_default  = isset( $defaults[ $id ] );
        $is_enabled  = ! empty( $embed['enabled'] );
        $lang_suffix = '_' . $lang;
    ?>
    <div class="dcb-embed-card <?php echo $is_enabled ? 'dcb-embed-enabled' : 'dcb-embed-disabled'; ?>"
         id="dcb-embed-card-<?php echo esc_attr( $id ); ?>"
         data-id="<?php echo esc_attr( $id ); ?>">

        <!-- Card header -->
        <div class="dcb-embed-card-header">
            <span class="dcb-embed-card-icon"><?php echo esc_html( $embed['icon'] ); ?></span>
            <div class="dcb-embed-card-title">
                <strong><?php echo esc_html( $embed['label'] ); ?></strong>
                <code class="dcb-embed-shortcode-badge">[dcb_<?php echo esc_html( $id ); ?>]</code>
            </div>
            <div class="dcb-embed-card-meta">
                <span class="dcb-cat-badge dcb-badge-<?php echo esc_attr( $embed['category'] ); ?>">
                    <?php echo esc_html( $cats[ $embed['category'] ]['label'] ?? $embed['category'] ); ?>
                </span>
                <?php if ( ! $is_enabled ) : ?>
                    <span class="dcb-disabled-badge"><?php echo $lang === 'de' ? 'Deaktiviert' : 'Disabled'; ?></span>
                <?php endif; ?>
            </div>
            <div class="dcb-embed-card-actions">
                <button type="button" class="button button-small dcb-embed-toggle"
                        data-id="<?php echo esc_attr( $id ); ?>"
                        data-enabled="<?php echo $is_enabled ? '1' : '0'; ?>">
                    <?php echo $is_enabled
                        ? ( $lang === 'de' ? 'Deaktivieren' : 'Disable' )
                        : ( $lang === 'de' ? 'Aktivieren'   : 'Enable'  ); ?>
                </button>
                <button type="button" class="button button-primary button-small dcb-embed-edit-btn"
                        data-id="<?php echo esc_attr( $id ); ?>">
                    ✏️ <?php echo $lang === 'de' ? 'Bearbeiten' : 'Edit'; ?>
                </button>
                <?php if ( ! $is_default ) : ?>
                <button type="button" class="button button-small dcb-embed-delete"
                        data-id="<?php echo esc_attr( $id ); ?>">
                    🗑️
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Preview strip -->
        <div class="dcb-embed-preview-strip"
             style="--dcb-embed-bg:<?php echo esc_attr($embed['bg_color']); ?>;--dcb-embed-accent:<?php echo esc_attr($embed['accent_color']); ?>;--dcb-embed-tc:<?php echo esc_attr($embed['text_color']); ?>">
            <span class="dcb-embed-prev-icon"><?php echo esc_html($embed['icon']); ?></span>
            <span class="dcb-embed-prev-title"><?php echo esc_html( $embed['placeholder_title_' . $lang] ?? $embed['placeholder_title_de'] ); ?></span>
            <span class="dcb-embed-prev-btn"><?php echo esc_html( $embed['btn_text_' . $lang] ?? $embed['btn_text_de'] ); ?></span>
        </div>

        <!-- Inline edit form (hidden by default) -->
        <div class="dcb-embed-edit-form" id="dcb-embed-form-<?php echo esc_attr( $id ); ?>" style="display:none">
            <div class="dcb-embed-form-inner">

                <div class="dcb-embed-form-grid">
                    <!-- Left col: basic settings -->
                    <div class="dcb-embed-form-col">
                        <h4><?php echo $lang === 'de' ? 'Allgemein' : 'General'; ?></h4>
                        <table class="form-table dcb-embed-form-table">
                            <tr>
                                <th><?php echo $lang === 'de' ? 'Bezeichnung' : 'Label'; ?></th>
                                <td><input type="text" class="regular-text ef-label" value="<?php echo esc_attr( $embed['label'] ); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang === 'de' ? 'Kategorie' : 'Category'; ?></th>
                                <td>
                                    <select class="ef-category">
                                        <?php foreach ( $cats as $ck => $cc ) : ?>
                                        <option value="<?php echo esc_attr($ck); ?>" <?php selected($embed['category'], $ck); ?>><?php echo esc_html($cc['label']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><?php echo $lang === 'de' ? 'Symbol / Icon' : 'Icon'; ?></th>
                                <td><input type="text" class="small-text ef-icon" value="<?php echo esc_attr( $embed['icon'] ); ?>"></td>
                            </tr>
                        </table>

                        <h4><?php echo $lang === 'de' ? 'Farben' : 'Colors'; ?></h4>
                        <table class="form-table dcb-embed-form-table">
                            <tr>
                                <th><?php echo $lang === 'de' ? 'Hintergrund' : 'Background'; ?></th>
                                <td><input type="color" class="ef-bg-color" value="<?php echo esc_attr( $embed['bg_color'] ); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang === 'de' ? 'Akzentfarbe' : 'Accent'; ?></th>
                                <td><input type="color" class="ef-accent-color" value="<?php echo esc_attr( $embed['accent_color'] ); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang === 'de' ? 'Textfarbe' : 'Text Color'; ?></th>
                                <td><input type="color" class="ef-text-color" value="<?php echo esc_attr( $embed['text_color'] ); ?>"></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Right col: texts DE + EN -->
                    <div class="dcb-embed-form-col">
                        <h4>🇩🇪 Deutsch</h4>
                        <table class="form-table dcb-embed-form-table">
                            <tr><th><?php echo $lang === 'de' ? 'Titel' : 'Title'; ?></th>
                                <td><input type="text" class="regular-text ef-title-de" value="<?php echo esc_attr( $embed['placeholder_title_de'] ); ?>"></td></tr>
                            <tr><th><?php echo $lang === 'de' ? 'Beschreibung' : 'Description'; ?></th>
                                <td><textarea class="large-text ef-text-de" rows="3"><?php echo esc_textarea( $embed['placeholder_text_de'] ); ?></textarea></td></tr>
                            <tr><th><?php echo $lang === 'de' ? 'Button-Text' : 'Button Text'; ?></th>
                                <td><input type="text" class="regular-text ef-btn-de" value="<?php echo esc_attr( $embed['btn_text_de'] ); ?>"></td></tr>
                            <tr><th><?php echo $lang === 'de' ? '"Immer erlauben"-Text' : '"Always allow" text'; ?></th>
                                <td><input type="text" class="regular-text ef-always-de" value="<?php echo esc_attr( $embed['always_text_de'] ); ?>"></td></tr>
                        </table>

                        <h4>🇬🇧 English</h4>
                        <table class="form-table dcb-embed-form-table">
                            <tr><th>Title</th>
                                <td><input type="text" class="regular-text ef-title-en" value="<?php echo esc_attr( $embed['placeholder_title_en'] ); ?>"></td></tr>
                            <tr><th>Description</th>
                                <td><textarea class="large-text ef-text-en" rows="3"><?php echo esc_textarea( $embed['placeholder_text_en'] ); ?></textarea></td></tr>
                            <tr><th>Button Text</th>
                                <td><input type="text" class="regular-text ef-btn-en" value="<?php echo esc_attr( $embed['btn_text_en'] ); ?>"></td></tr>
                            <tr><th>"Always allow" text</th>
                                <td><input type="text" class="regular-text ef-always-en" value="<?php echo esc_attr( $embed['always_text_en'] ); ?>"></td></tr>
                        </table>
                    </div>
                </div>

                <!-- Live preview -->
                <div class="dcb-embed-live-preview">
                    <strong><?php echo $lang === 'de' ? '👁 Vorschau' : '👁 Preview'; ?></strong>
                    <div class="dcb-embed-placeholder dcb-embed-preview-box"
                         style="--dcb-embed-bg:<?php echo esc_attr($embed['bg_color']); ?>;--dcb-embed-accent:<?php echo esc_attr($embed['accent_color']); ?>;--dcb-embed-tc:<?php echo esc_attr($embed['text_color']); ?>">
                        <div class="dcb-embed-icon dcb-prev-icon"><?php echo esc_html($embed['icon']); ?></div>
                        <div class="dcb-embed-info">
                            <strong class="dcb-embed-title dcb-prev-title"><?php echo esc_html( $embed['placeholder_title_' . $lang] ?? $embed['placeholder_title_de'] ); ?></strong>
                            <p class="dcb-embed-text dcb-prev-text"><?php echo esc_html( $embed['placeholder_text_' . $lang] ?? $embed['placeholder_text_de'] ); ?></p>
                        </div>
                        <div class="dcb-embed-actions">
                            <button type="button" class="dcb-embed-load-btn dcb-embed-load-once dcb-prev-btn"><?php echo esc_html( $embed['btn_text_' . $lang] ?? $embed['btn_text_de'] ); ?></button>
                            <button type="button" class="dcb-embed-load-btn dcb-embed-load-always dcb-prev-always"><?php echo esc_html( $embed['always_text_' . $lang] ?? $embed['always_text_de'] ); ?></button>
                        </div>
                    </div>
                </div>

                <div class="dcb-embed-form-footer">
                    <button type="button" class="button button-primary dcb-embed-save-btn" data-id="<?php echo esc_attr( $id ); ?>">
                        💾 <?php echo $lang === 'de' ? 'Speichern' : 'Save'; ?>
                    </button>
                    <button type="button" class="button dcb-embed-cancel-btn" data-id="<?php echo esc_attr( $id ); ?>">
                        <?php echo $lang === 'de' ? 'Abbrechen' : 'Cancel'; ?>
                    </button>
                    <?php if ( $is_default ) : ?>
                    <button type="button" class="button dcb-embed-reset-btn" data-id="<?php echo esc_attr( $id ); ?>">
                        ↺ <?php echo $lang === 'de' ? 'Zurücksetzen' : 'Reset to default'; ?>
                    </button>
                    <?php endif; ?>
                    <span class="dcb-embed-save-status"></span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>

    <!-- ── Neuen Typ hinzufügen ── -->
    <div class="dcb-embed-add-section">
        <h3>➕ <?php echo $lang === 'de' ? 'Neuen Einbettungs-Typ erstellen' : 'Create new embed type'; ?></h3>
        <div class="dcb-embed-add-form" id="dcb-embed-add-form" style="display:none">
            <table class="form-table">
                <tr><th><?php echo $lang === 'de' ? 'ID (Schlüssel) *' : 'ID (key) *'; ?></th>
                    <td><input type="text" id="new-embed-id" class="regular-text" placeholder="<?php echo $lang === 'de' ? 'z.B. tiktok' : 'e.g. tiktok'; ?>">
                    <p class="description"><?php echo $lang === 'de' ? 'Nur Kleinbuchstaben, Zahlen und Unterstriche. Wird für den Shortcode verwendet: [dcb_tiktok]' : 'Lowercase letters, numbers and underscores only. Used for the shortcode: [dcb_tiktok]'; ?></p></td></tr>
                <tr><th><?php echo $lang === 'de' ? 'Bezeichnung *' : 'Label *'; ?></th>
                    <td><input type="text" id="new-embed-label" class="regular-text" placeholder="TikTok"></td></tr>
                <tr><th><?php echo $lang === 'de' ? 'Kategorie' : 'Category'; ?></th>
                    <td>
                        <select id="new-embed-category">
                            <?php foreach ( $cats as $ck => $cc ) : ?>
                            <option value="<?php echo esc_attr($ck); ?>"><?php echo esc_html($cc['label']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td></tr>
                <tr><th><?php echo $lang === 'de' ? 'Icon/Emoji' : 'Icon/Emoji'; ?></th>
                    <td><input type="text" id="new-embed-icon" class="small-text" value="▶" placeholder="▶"></td></tr>
            </table>
            <div style="display:flex;gap:8px;align-items:center">
                <button type="button" id="dcb-embed-create-btn" class="button button-primary">
                    ✅ <?php echo $lang === 'de' ? 'Erstellen' : 'Create'; ?>
                </button>
                <button type="button" id="dcb-embed-add-cancel" class="button">
                    <?php echo $lang === 'de' ? 'Abbrechen' : 'Cancel'; ?>
                </button>
                <span id="dcb-embed-add-status"></span>
            </div>
        </div>
        <button type="button" id="dcb-embed-add-btn" class="button button-secondary">
            ➕ <?php echo $lang === 'de' ? 'Neuen Typ hinzufügen' : 'Add new type'; ?>
        </button>
    </div>
</div>
