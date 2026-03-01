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
            <h3><?php echo esc_html( $i::t('help_shortcodes_title') ); ?></h3>
            <table class="widefat">
                <tr><td><code>[dcb_cookie_list]</code></td><td><?php echo esc_html( $i::t('help_shortcode_list_desc') ); ?></td></tr>
                <tr><td><code>[dcb_cookie_list category="statistics"]</code></td><td><?php echo esc_html( $i::t('help_shortcode_cat_desc') ); ?></td></tr>
                <tr><td><code>[dcb_privacy_settings]</code></td><td><?php echo esc_html( $i::t('help_shortcode_btn_desc') ); ?></td></tr>
            </table>
            <h3><?php echo esc_html( $i::t('help_privacy_title') ); ?></h3>
            <p><?php echo esc_html( $i::t('help_privacy_text') ); ?></p>
            <h3><?php echo esc_html( $i::t('help_compliance_title') ); ?></h3>
            <ul style="list-style:disc;padding-left:20px">
                <li>✅ <?php echo esc_html( $i::t('help_check_1') ); ?></li>
                <li>✅ <?php echo esc_html( $i::t('help_check_2') ); ?></li>
                <li>✅ <?php echo esc_html( $i::t('help_check_3') ); ?></li>
                <li>✅ <?php echo esc_html( $i::t('help_check_4') ); ?></li>
                <li>✅ <?php echo esc_html( $i::t('help_check_5') ); ?></li>
                <li>✅ <?php echo esc_html( $i::t('help_check_6') ); ?></li>
            </ul>
        </div>

        <?php submit_button( $i::t('save_changes') ); ?>
    </form>
</div>
