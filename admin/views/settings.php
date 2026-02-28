<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap dcb-admin-wrap">
    <h1>🍪 DSGVO Cookie Banner – Einstellungen</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'dcb_options_group' ); ?>

        <div class="dcb-tabs">
            <button type="button" class="dcb-tab active" data-tab="banner">Banner-Text</button>
            <button type="button" class="dcb-tab" data-tab="design">Design</button>
            <button type="button" class="dcb-tab" data-tab="advanced">Erweitert</button>
            <button type="button" class="dcb-tab" data-tab="help">Hilfe</button>
        </div>

        <!-- Banner-Text -->
        <div class="dcb-tab-content active" id="tab-banner">
            <table class="form-table">
                <tr><th>Banner-Titel</th><td><input type="text" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[banner_title]" value="<?php echo esc_attr( $settings['banner_title'] ); ?>" class="regular-text"></td></tr>
                <tr><th>Banner-Text</th><td><textarea name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[banner_text]" rows="4" class="large-text"><?php echo esc_textarea( $settings['banner_text'] ); ?></textarea></td></tr>
                <tr><th>„Alle akzeptieren"</th><td><input type="text" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[accept_all_text]" value="<?php echo esc_attr( $settings['accept_all_text'] ); ?>" class="regular-text"></td></tr>
                <tr><th>„Nur notwendige"</th><td><input type="text" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[accept_necessary_text]" value="<?php echo esc_attr( $settings['accept_necessary_text'] ); ?>" class="regular-text"></td></tr>
                <tr><th>„Einstellungen"</th><td><input type="text" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[customize_text]" value="<?php echo esc_attr( $settings['customize_text'] ); ?>" class="regular-text"></td></tr>
                <tr><th>„Einstellungen speichern"</th><td><input type="text" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[save_settings_text]" value="<?php echo esc_attr( $settings['save_settings_text'] ); ?>" class="regular-text"></td></tr>
            </table>
        </div>

        <!-- Design -->
        <div class="dcb-tab-content" id="tab-design">
            <table class="form-table">
                <tr>
                    <th>Position</th>
                    <td>
                        <select name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[banner_position]">
                            <option value="bottom" <?php selected( $settings['banner_position'], 'bottom' ); ?>>Unten</option>
                            <option value="top"    <?php selected( $settings['banner_position'], 'top' ); ?>>Oben</option>
                            <option value="center" <?php selected( $settings['banner_position'], 'center' ); ?>>Mitte (Modal)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Layout</th>
                    <td>
                        <select name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[banner_layout]">
                            <option value="bar"  <?php selected( $settings['banner_layout'], 'bar' ); ?>>Leiste</option>
                            <option value="box"  <?php selected( $settings['banner_layout'], 'box' ); ?>>Box</option>
                        </select>
                    </td>
                </tr>
                <tr><th>Primärfarbe</th><td><input type="color" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[primary_color]" value="<?php echo esc_attr( $settings['primary_color'] ); ?>"></td></tr>
                <tr><th>Textfarbe</th><td><input type="color" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[text_color]" value="<?php echo esc_attr( $settings['text_color'] ); ?>"></td></tr>
                <tr><th>Hintergrundfarbe</th><td><input type="color" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[bg_color]" value="<?php echo esc_attr( $settings['bg_color'] ); ?>"></td></tr>
            </table>
        </div>

        <!-- Erweitert -->
        <div class="dcb-tab-content" id="tab-advanced">
            <table class="form-table">
                <tr>
                    <th>Datenschutzseite</th>
                    <td>
                        <select name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[privacy_page_id]">
                            <option value="0">-- bitte wählen --</option>
                            <?php foreach ( $pages as $page ) : ?>
                            <option value="<?php echo $page->ID; ?>" <?php selected( $settings['privacy_page_id'], $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Impressum-Seite</th>
                    <td>
                        <select name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[imprint_page_id]">
                            <option value="0">-- bitte wählen --</option>
                            <?php foreach ( $pages as $page ) : ?>
                            <option value="<?php echo $page->ID; ?>" <?php selected( $settings['imprint_page_id'], $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Cookie-Laufzeit</th>
                    <td><input type="number" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[cookie_lifetime]" value="<?php echo esc_attr( $settings['cookie_lifetime'] ); ?>" min="1" max="730"> Tage</td>
                </tr>
                <tr>
                    <th>Scripts automatisch blockieren</th>
                    <td>
                        <label><input type="checkbox" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[auto_block_scripts]" value="1" <?php checked( $settings['auto_block_scripts'] ); ?>> Aktivieren (blockiert Scripts bis Einwilligung)</label>
                    </td>
                </tr>
                <tr>
                    <th>Einwilligungen protokollieren</th>
                    <td>
                        <label><input type="checkbox" name="<?php echo DCB_Cookie_Manager::OPTION_SETTINGS; ?>[log_consents]" value="1" <?php checked( $settings['log_consents'] ); ?>> Aktivieren (DSGVO-Nachweispflicht)</label>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Hilfe -->
        <div class="dcb-tab-content" id="tab-help">
            <h3>Shortcodes</h3>
            <table class="widefat">
                <tr><td><code>[dcb_cookie_list]</code></td><td>Vollständige Cookie-Tabelle ausgeben (für Datenschutzerklärung / Impressum)</td></tr>
                <tr><td><code>[dcb_cookie_list category="statistics"]</code></td><td>Nur Statistik-Cookies anzeigen</td></tr>
                <tr><td><code>[dcb_privacy_settings]</code></td><td>Button zum Öffnen der Cookie-Einstellungen</td></tr>
            </table>
            <h3>Einbindung in Datenschutzerklärung</h3>
            <p>Fügen Sie auf Ihrer Datenschutzseite den Shortcode <code>[dcb_cookie_list]</code> ein. Die Liste wird automatisch aus dem letzten Scan befüllt.</p>
            <h3>DSGVO-Compliance Checkliste</h3>
            <ul style="list-style:disc;padding-left:20px">
                <li>✅ Kein Pre-Ticking (kein Standard-Häkchen außer "Notwendig")</li>
                <li>✅ Granulare Kategorien (nicht nur "Alle akzeptieren")</li>
                <li>✅ Einwilligungsprotokoll mit IP-Hash und Zeitstempel</li>
                <li>✅ Widerruf jederzeit möglich via <code>[dcb_privacy_settings]</code></li>
                <li>✅ Link zur Datenschutzerklärung im Banner</li>
                <li>✅ Keine Ablehnung erschwert (gleichwertige Buttons)</li>
            </ul>
        </div>

        <?php submit_button( 'Einstellungen speichern' ); ?>
    </form>
</div>
