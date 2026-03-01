<?php if ( ! defined( 'ABSPATH' ) ) exit;
$S    = DCB_Cookie_Manager::OPTION_SETTINGS;
$lang = DCB_I18n::get_lang();
$i    = 'DCB_I18n'; // shorthand
?>
<div class="wrap dcb-admin-wrap">
    <h1><?php echo esc_html( $i::t('admin_settings_title') ); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'dcb_options_group' ); ?>

        <div class="dcb-tabs">
            <button type="button" class="dcb-tab active" data-tab="banner"><?php echo esc_html( $i::t('tab_banner') ); ?></button>
            <button type="button" class="dcb-tab" data-tab="design"><?php echo esc_html( $i::t('tab_design') ); ?></button>
            <button type="button" class="dcb-tab" data-tab="categories">🗂️ <?php echo esc_html( $i::t('tab_categories') ); ?></button>
            <button type="button" class="dcb-tab" data-tab="advanced"><?php echo esc_html( $i::t('tab_advanced') ); ?></button>
            <button type="button" class="dcb-tab dcb-tab-lang" data-tab="language">🌐 <?php echo esc_html( $i::t('tab_language') ); ?></button>
            <button type="button" class="dcb-tab" data-tab="help"><?php echo esc_html( $i::t('tab_help') ); ?></button>
        </div>

        <!-- Banner Text -->
        <div class="dcb-tab-content active" id="tab-banner">
            <table class="form-table">
                <tr><th><?php echo esc_html( $i::t('field_banner_title') ); ?></th><td><input type="text" name="<?php echo $S; ?>[banner_title]" value="<?php echo esc_attr( $settings['banner_title'] ); ?>" class="regular-text"></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_banner_text') ); ?></th><td><textarea name="<?php echo $S; ?>[banner_text]" rows="4" class="large-text"><?php echo esc_textarea( $settings['banner_text'] ); ?></textarea></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_accept_all') ); ?></th><td><input type="text" name="<?php echo $S; ?>[accept_all_text]" value="<?php echo esc_attr( $settings['accept_all_text'] ); ?>" class="regular-text"></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_accept_necessary') ); ?></th><td><input type="text" name="<?php echo $S; ?>[accept_necessary_text]" value="<?php echo esc_attr( $settings['accept_necessary_text'] ); ?>" class="regular-text"></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_customize') ); ?></th><td><input type="text" name="<?php echo $S; ?>[customize_text]" value="<?php echo esc_attr( $settings['customize_text'] ); ?>" class="regular-text"></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_save_settings') ); ?></th><td><input type="text" name="<?php echo $S; ?>[save_settings_text]" value="<?php echo esc_attr( $settings['save_settings_text'] ); ?>" class="regular-text"></td></tr>
            </table>
        </div>

        <!-- Design -->
        <div class="dcb-tab-content" id="tab-design">
            <table class="form-table">
                <tr>
                    <th><?php echo esc_html( $i::t('field_position') ); ?></th>
                    <td>
                        <select name="<?php echo $S; ?>[banner_position]">
                            <option value="bottom" <?php selected( $settings['banner_position'], 'bottom' ); ?>><?php echo esc_html( $i::t('field_position_bottom') ); ?></option>
                            <option value="top"    <?php selected( $settings['banner_position'], 'top' ); ?>><?php echo esc_html( $i::t('field_position_top') ); ?></option>
                            <option value="center" <?php selected( $settings['banner_position'], 'center' ); ?>><?php echo esc_html( $i::t('field_position_center') ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php echo esc_html( $i::t('field_layout') ); ?></th>
                    <td>
                        <select name="<?php echo $S; ?>[banner_layout]">
                            <option value="bar" <?php selected( $settings['banner_layout'], 'bar' ); ?>><?php echo esc_html( $i::t('field_layout_bar') ); ?></option>
                            <option value="box" <?php selected( $settings['banner_layout'], 'box' ); ?>><?php echo esc_html( $i::t('field_layout_box') ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr><th><?php echo esc_html( $i::t('field_primary_color') ); ?></th><td><input type="color" name="<?php echo $S; ?>[primary_color]" value="<?php echo esc_attr( $settings['primary_color'] ); ?>"></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_text_color') ); ?></th><td><input type="color" name="<?php echo $S; ?>[text_color]" value="<?php echo esc_attr( $settings['text_color'] ); ?>"></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_bg_color') ); ?></th><td><input type="color" name="<?php echo $S; ?>[bg_color]" value="<?php echo esc_attr( $settings['bg_color'] ); ?>"></td></tr>
            </table>
        </div>

        <!-- Categories -->
        <div class="dcb-tab-content" id="tab-categories">
            <p class="description" style="margin-bottom:20px"><?php echo esc_html( $i::t('categories_tab_intro') ); ?></p>

            <?php
            $cats_ordered = array_keys( $settings['categories'] );
            // Always show necessary first
            usort( $cats_ordered, function($a) { return $a === 'necessary' ? -1 : 0; });
            foreach ( $cats_ordered as $cat_key ) :
                $cat         = $settings['categories'][ $cat_key ];
                $is_required = ! empty( $cat['required'] );
                $sk          = $cat['shortcode_key'] ?? $cat_key;
                $bk          = $cat['block_key']     ?? $cat_key;
            ?>
            <div class="dcb-cat-edit-card<?php echo $is_required ? ' dcb-cat-required-card' : ''; ?>">
                <div class="dcb-cat-edit-header">
                    <span class="dcb-cat-badge dcb-badge-<?php echo esc_attr( $cat_key ); ?>"><?php echo esc_html( $cat['label'] ); ?></span>
                    <code class="dcb-cat-internal-key"><?php echo esc_html( $cat_key ); ?></code>
                    <?php if ( $is_required ) : ?>
                        <span class="dcb-req-badge">🔒 <?php echo esc_html( $i::t('cat_required_field') ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="dcb-cat-edit-body">
                    <div class="dcb-cat-edit-row">
                        <label><?php echo esc_html( $i::t('cat_label_field') ); ?></label>
                        <input type="text"
                               name="<?php echo $S; ?>[categories][<?php echo esc_attr($cat_key); ?>][label]"
                               value="<?php echo esc_attr( $cat['label'] ); ?>"
                               class="regular-text">
                    </div>
                    <div class="dcb-cat-edit-row">
                        <label><?php echo esc_html( $i::t('cat_description_field') ); ?></label>
                        <textarea name="<?php echo $S; ?>[categories][<?php echo esc_attr($cat_key); ?>][description]"
                                  rows="3" class="large-text"><?php echo esc_textarea( $cat['description'] ); ?></textarea>
                    </div>
                    <div class="dcb-cat-edit-row dcb-cat-keys-row">
                        <div class="dcb-cat-key-group">
                            <label><?php echo esc_html( $i::t('cat_shortcode_key_field') ); ?></label>
                            <input type="text"
                                   name="<?php echo $S; ?>[categories][<?php echo esc_attr($cat_key); ?>][shortcode_key]"
                                   value="<?php echo esc_attr( $sk ); ?>"
                                   class="dcb-key-input"
                                   <?php echo $is_required ? 'readonly' : ''; ?>>
                            <span class="description"><?php echo esc_html( $i::t('cat_shortcode_key_desc') ); ?></span>
                            <div class="dcb-usage-example">
                                <?php echo esc_html( $i::t('cat_shortcode_example') ); ?>
                                <code>[dcb_cookie_list category="<span class="dcb-key-preview-sc"><?php echo esc_html($sk); ?></span>"]</code>
                            </div>
                        </div>
                        <div class="dcb-cat-key-group">
                            <label><?php echo esc_html( $i::t('cat_block_key_field') ); ?></label>
                            <input type="text"
                                   name="<?php echo $S; ?>[categories][<?php echo esc_attr($cat_key); ?>][block_key]"
                                   value="<?php echo esc_attr( $bk ); ?>"
                                   class="dcb-key-input"
                                   <?php echo $is_required ? 'readonly' : ''; ?>>
                            <span class="description"><?php echo esc_html( $i::t('cat_block_key_desc') ); ?></span>
                            <div class="dcb-usage-example">
                                <?php echo esc_html( $i::t('cat_block_example') ); ?>
                                <code>data-dcb-category="<span class="dcb-key-preview-bk"><?php echo esc_html($bk); ?></span>"</code>
                            </div>
                        </div>
                    </div>
                    <?php if ( $is_required ) : ?>
                    <p class="dcb-locked-note"><?php echo esc_html( $i::t('cat_required_locked') ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Advanced -->
        <div class="dcb-tab-content" id="tab-advanced">
            <table class="form-table">
                <tr>
                    <th><?php echo esc_html( $i::t('field_privacy_page') ); ?></th>
                    <td>
                        <select name="<?php echo $S; ?>[privacy_page_id]">
                            <option value="0"><?php echo esc_html( $i::t('field_please_select') ); ?></option>
                            <?php foreach ( $pages as $page ) : ?>
                            <option value="<?php echo $page->ID; ?>" <?php selected( $settings['privacy_page_id'], $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php echo esc_html( $i::t('field_imprint_page') ); ?></th>
                    <td>
                        <select name="<?php echo $S; ?>[imprint_page_id]">
                            <option value="0"><?php echo esc_html( $i::t('field_please_select') ); ?></option>
                            <?php foreach ( $pages as $page ) : ?>
                            <option value="<?php echo $page->ID; ?>" <?php selected( $settings['imprint_page_id'], $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php echo esc_html( $i::t('field_cookie_lifetime') ); ?></th>
                    <td><input type="number" name="<?php echo $S; ?>[cookie_lifetime]" value="<?php echo esc_attr( $settings['cookie_lifetime'] ); ?>" min="1" max="730"> <?php echo esc_html( $i::t('field_lifetime_days') ); ?></td>
                </tr>
                <tr>
                    <th><?php echo esc_html( $i::t('field_auto_block') ); ?></th>
                    <td><label><input type="checkbox" name="<?php echo $S; ?>[auto_block_scripts]" value="1" <?php checked( $settings['auto_block_scripts'] ); ?>> <?php echo esc_html( $i::t('field_auto_block_desc') ); ?></label></td>
                </tr>
                <tr>
                    <th><?php echo esc_html( $i::t('field_log_consents') ); ?></th>
                    <td><label><input type="checkbox" name="<?php echo $S; ?>[log_consents]" value="1" <?php checked( $settings['log_consents'] ); ?>> <?php echo esc_html( $i::t('field_log_consents_desc') ); ?></label></td>
                </tr>
            </table>
        </div>

        <!-- Language -->
        <div class="dcb-tab-content" id="tab-language">
            <div class="dcb-lang-box">
                <h3>🌐 <?php echo esc_html( $i::t('field_plugin_language') ); ?></h3>
                <p><?php echo esc_html( $i::t('lang_desc') ); ?></p>
                <div class="dcb-lang-cards">
                    <?php foreach ( DCB_I18n::available_languages() as $code => $label ) : ?>
                    <label class="dcb-lang-card <?php echo $lang === $code ? 'dcb-lang-active' : ''; ?>">
                        <input type="radio" name="<?php echo $S; ?>[plugin_language]"
                               value="<?php echo esc_attr( $code ); ?>"
                               <?php checked( $settings['plugin_language'] ?? 'de', $code ); ?>>
                        <span class="dcb-lang-flag"><?php echo $code === 'de' ? '🇩🇪' : '🇬🇧'; ?></span>
                        <span class="dcb-lang-label"><?php echo esc_html( $label ); ?></span>
                        <?php if ( $lang === $code ) : ?>
                            <span class="dcb-lang-current">✓ <?php echo $code === 'de' ? 'Aktiv' : 'Active'; ?></span>
                        <?php endif; ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <p class="description" style="margin-top:14px">⚠️ <?php echo esc_html( $i::t('lang_note') ); ?></p>
            </div>
        </div>

        <!-- Help -->
        <div class="dcb-tab-content" id="tab-help">
            <div class="dcb-help-wrap">

            <!-- ── Shortcodes ── -->
            <div class="dcb-help-section">
                <h3><?php echo esc_html( $i::t('help_shortcodes_title') ); ?></h3>
                <table class="widefat dcb-help-table">
                    <thead><tr><th><?php echo $lang === 'de' ? 'Shortcode' : 'Shortcode'; ?></th><th><?php echo $lang === 'de' ? 'Beschreibung' : 'Description'; ?></th></tr></thead>
                    <tbody>
                        <tr><td><code>[dcb_cookie_list]</code></td><td><?php echo esc_html( $i::t('help_shortcode_list_desc') ); ?></td></tr>
                        <tr><td><code>[dcb_cookie_list category="statistics"]</code></td><td><?php echo esc_html( $i::t('help_shortcode_cat_desc') ); ?></td></tr>
                        <tr><td><code>[dcb_privacy_settings]</code></td><td><?php echo esc_html( $i::t('help_shortcode_btn_desc') ); ?></td></tr>
                        <tr><td><code>[dcb_privacy_settings text="<?php echo $lang === 'de' ? 'Einstellungen' : 'Settings'; ?>"]</code></td><td><?php echo esc_html( $i::t('help_shortcode_banner_desc') ); ?></td></tr>
                    </tbody>
                </table>
            </div>

            <!-- ── Embed Placeholders ── -->
            <div class="dcb-help-section">
                <h3><?php echo esc_html( $i::t('help_embeds_title') ); ?></h3>
                <p><?php echo esc_html( $i::t('help_embeds_intro') ); ?></p>
                <table class="widefat dcb-help-table">
                    <thead><tr><th>Shortcode</th><th><?php echo $lang === 'de' ? 'Beispiel' : 'Example'; ?></th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code>[dcb_youtube]</code></td>
                            <td><code>[dcb_youtube id="dQw4w9WgXcQ" width="100%" height="400"]</code></td>
                        </tr>
                        <tr>
                            <td><code>[dcb_vimeo]</code></td>
                            <td><code>[dcb_vimeo id="123456789"]</code></td>
                        </tr>
                        <tr>
                            <td><code>[dcb_googlemaps]</code></td>
                            <td><code>[dcb_googlemaps src="https://maps.google.com/maps?q=Berlin&amp;output=embed" height="450"]</code></td>
                        </tr>
                        <tr>
                            <td><code>[dcb_openstreetmap]</code></td>
                            <td><code>[dcb_openstreetmap lat="52.52" lng="13.40" zoom="14"]</code></td>
                        </tr>
                        <tr>
                            <td><code>[dcb_instagram]</code></td>
                            <td><code>[dcb_instagram url="https://www.instagram.com/p/ABC123/"]</code></td>
                        </tr>
                        <tr>
                            <td><code>[dcb_twitter]</code></td>
                            <td><code>[dcb_twitter url="https://twitter.com/user/status/123"]</code></td>
                        </tr>
                        <tr>
                            <td><code>[dcb_facebook]</code></td>
                            <td><code>[dcb_facebook url="https://www.facebook.com/post/123"]</code></td>
                        </tr>
                        <tr>
                            <td><code>[dcb_embed]</code></td>
                            <td><code>[dcb_embed type="youtube" id="dQw4w9WgXcQ"]</code></td>
                        </tr>
                    </tbody>
                </table>
                <p class="description"><?php echo esc_html( $i::t('help_embed_note') ); ?> → <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-embeds') ); ?>"><?php echo $lang === 'de' ? 'Einbettungen verwalten' : 'Manage Embeds'; ?></a></p>
            </div>

            <!-- ── Script Blocking ── -->
            <div class="dcb-help-section">
                <h3><?php echo esc_html( $i::t('help_blocking_title') ); ?></h3>
                <p><?php echo esc_html( $i::t('help_blocking_intro') ); ?></p>
                <div class="dcb-help-code-pair">
                    <div class="dcb-help-code-block dcb-code-before">
                        <span class="dcb-code-label"><?php echo $lang === 'de' ? 'Vorher' : 'Before'; ?></span>
<pre>&lt;script src="https://example.com/analytics.js"&gt;&lt;/script&gt;</pre>
                    </div>
                    <div class="dcb-help-code-block dcb-code-after">
                        <span class="dcb-code-label"><?php echo $lang === 'de' ? 'Nachher' : 'After'; ?></span>
<pre>&lt;script
  type="text/plain"
  data-dcb-category="statistics"
  src="https://example.com/analytics.js"&gt;
&lt;/script&gt;</pre>
                    </div>
                </div>
                <p class="description"><?php echo esc_html( $i::t('help_blocking_cats') ); ?></p>
            </div>

            <!-- ── Categories ── -->
            <div class="dcb-help-section">
                <h3><?php echo esc_html( $i::t('help_categories_title') ); ?></h3>
                <p><?php echo esc_html( $i::t('help_categories_intro') ); ?></p>
                <?php
                $cats = $settings['categories'] ?? array();
                if ( $cats ) : ?>
                <table class="widefat dcb-help-table">
                    <thead><tr>
                        <th><?php echo $lang === 'de' ? 'Kategorie' : 'Category'; ?></th>
                        <th><?php echo $lang === 'de' ? 'Shortcode-Schlüssel' : 'Shortcode Key'; ?></th>
                        <th><?php echo $lang === 'de' ? 'Blockierungs-Schlüssel' : 'Block Key'; ?></th>
                        <th><?php echo $lang === 'de' ? 'Pflicht' : 'Required'; ?></th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ( $cats as $id => $cat ) : ?>
                        <tr>
                            <td><strong><?php echo esc_html( $cat['label'] ); ?></strong></td>
                            <td><code>category="<?php echo esc_html( $cat['shortcode_key'] ?? $id ); ?>"</code></td>
                            <td><code>data-dcb-category="<?php echo esc_html( $cat['block_key'] ?? $id ); ?>"</code></td>
                            <td><?php echo ! empty( $cat['required'] ) ? '🔒 ' . ( $lang === 'de' ? 'Ja' : 'Yes' ) : '—'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
                <p class="description"><a href="<?php echo esc_url( admin_url('admin.php?page=dcb-settings&tab=categories') ); ?>"><?php echo $lang === 'de' ? 'Kategorien bearbeiten →' : 'Edit categories →'; ?></a></p>
            </div>

            <!-- ── JS Event ── -->
            <div class="dcb-help-section">
                <h3><?php echo esc_html( $i::t('help_event_title') ); ?></h3>
                <p><?php echo esc_html( $i::t('help_event_intro') ); ?></p>
                <div class="dcb-help-code-block">
<pre>document.addEventListener('dcb:consent', function(e) {
  var consent = e.detail; // { categories: { statistics: true, … } }
  if (consent.categories.statistics) {
    // Analytics initialisieren
  }
});</pre>
                </div>
            </div>

            <!-- ── Privacy Policy ── -->
            <div class="dcb-help-section">
                <h3><?php echo esc_html( $i::t('help_privacy_title') ); ?></h3>
                <p><?php echo esc_html( $i::t('help_privacy_text') ); ?></p>
            </div>

            <!-- ── Compliance Checklist ── -->
            <div class="dcb-help-section">
                <h3><?php echo esc_html( $i::t('help_compliance_title') ); ?></h3>
                <ul class="dcb-help-checklist">
                    <?php for ( $n = 1; $n <= 8; $n++ ) : ?>
                    <li><?php echo esc_html( $i::t( 'help_check_' . $n ) ); ?></li>
                    <?php endfor; ?>
                </ul>
            </div>

            <!-- ── Version / Links ── -->
            <div class="dcb-help-section dcb-help-footer">
                <strong><?php echo $lang === 'de' ? 'DSGVO Cookie Banner' : 'GDPR Cookie Banner'; ?> v<?php echo esc_html( DCB_VERSION ); ?></strong>
                &nbsp;·&nbsp; PHP <?php echo esc_html( phpversion() ); ?>
                &nbsp;·&nbsp; WordPress <?php global $wp_version; echo esc_html( $wp_version ); ?>
                &nbsp;·&nbsp; <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-scanner') ); ?>"><?php echo $lang === 'de' ? 'Cookie-Scanner' : 'Cookie Scanner'; ?></a>
                &nbsp;·&nbsp; <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-embeds') ); ?>"><?php echo $lang === 'de' ? 'Einbettungen' : 'Embeds'; ?></a>
                &nbsp;·&nbsp; <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-consents') ); ?>"><?php echo $lang === 'de' ? 'Einwilligungsprotokoll' : 'Consent Log'; ?></a>
            </div>

            </div><!-- .dcb-help-wrap -->
        </div>

        <?php submit_button( $i::t('save_changes') ); ?>
    </form>
</div>
