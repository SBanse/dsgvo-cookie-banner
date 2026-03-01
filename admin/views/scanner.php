<?php if ( ! defined( 'ABSPATH' ) ) exit;
$auto     = $stored['auto']   ?? array();
$manual   = $stored['manual'] ?? array();
$all      = array_merge( $auto, $manual );
$settings = DCB_Cookie_Manager::get_settings();
$cats     = $settings['categories'];
?>
<div class="wrap dcb-admin-wrap">
    <h1>🔍 Cookie-Verwaltung</h1>
    <p>Scannt Ihre WordPress-Installation auf verwendete Cookies. Alle Felder können direkt in der Tabelle bearbeitet werden – klicken Sie dazu auf den ✏️-Button in der jeweiligen Zeile.</p>

    <div class="dcb-scan-box">
        <button id="dcb-run-scan" class="button button-primary button-hero">🔍 Scan starten</button>
        <span id="dcb-scan-status"></span>
        <?php if ( isset( $stored['last_scan'] ) ) : ?>
            <small style="display:block;margin-top:6px;color:#666">Letzter Scan: <?php echo esc_html( $stored['last_scan'] ); ?></small>
        <?php endif; ?>
    </div>

    <div class="dcb-table-header">
        <h2 style="margin:0">Cookie-Liste (<?php echo count( $all ); ?> Einträge)</h2>
        <button id="dcb-add-row-btn" class="button button-secondary">+ Cookie hinzufügen</button>
    </div>

    <?php if ( empty( $all ) ) : ?>
        <p class="dcb-empty-notice">Noch keine Cookies vorhanden. Starten Sie einen Scan oder fügen Sie Cookies manuell hinzu.</p>
    <?php else :
        $grouped = array();
        foreach ( $all as $k => $c ) {
            $grouped[ $c['category'] ?? 'necessary' ][ $k ] = $c;
        }
        $ordered = array();
        foreach ( array_keys( $cats ) as $cat_key ) {
            if ( isset( $grouped[ $cat_key ] ) ) $ordered[ $cat_key ] = $grouped[ $cat_key ];
        }
        foreach ( $grouped as $cat_key => $group ) {
            if ( ! isset( $ordered[ $cat_key ] ) ) $ordered[ $cat_key ] = $group;
        }
    ?>
    <?php foreach ( $ordered as $cat_key => $group ) :
        $cat_label = $cats[ $cat_key ]['label'] ?? ucfirst( $cat_key );
        $cat_desc  = $cats[ $cat_key ]['description'] ?? '';
    ?>
    <div class="dcb-cat-section">
        <div class="dcb-cat-heading">
            <span class="dcb-cat-badge dcb-badge-<?php echo esc_attr( $cat_key ); ?>"><?php echo esc_html( $cat_label ); ?></span>
            <span class="dcb-cat-count"><?php echo count( $group ); ?> Cookie<?php echo count( $group ) !== 1 ? 's' : ''; ?></span>
            <?php if ( $cat_desc ) : ?>
                <span class="dcb-cat-desc-inline"><?php echo esc_html( $cat_desc ); ?></span>
            <?php endif; ?>
        </div>
        <table class="widefat dcb-cookie-edit-table">
            <thead>
                <tr>
                    <th style="width:17%">Cookie-Name</th>
                    <th style="width:14%">Anbieter</th>
                    <th style="width:31%">Zweck / Beschreibung</th>
                    <th style="width:10%">Laufzeit</th>
                    <th style="width:12%">Kategorie</th>
                    <th style="width:7%">Quelle</th>
                    <th style="width:9%">Aktionen</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ( $group as $k => $c ) :
                $is_manual = isset( $manual[ $k ] );
            ?>
                <tr class="dcb-cookie-row" data-key="<?php echo esc_attr( $k ); ?>">
                    <td class="dcb-view dcb-view-name"><code><?php echo esc_html( $c['name'] ); ?></code></td>
                    <td class="dcb-view dcb-view-provider"><?php echo esc_html( $c['provider'] ); ?></td>
                    <td class="dcb-view dcb-view-purpose"><?php echo esc_html( $c['purpose'] ); ?></td>
                    <td class="dcb-view dcb-view-duration"><?php echo esc_html( $c['duration'] ); ?></td>
                    <td class="dcb-view dcb-view-category">
                        <span class="dcb-cat-badge dcb-badge-<?php echo esc_attr( $c['category'] ?? '' ); ?>">
                            <?php echo esc_html( $cats[ $c['category'] ?? '' ]['label'] ?? ucfirst( $c['category'] ?? '' ) ); ?>
                        </span>
                    </td>
                    <td class="dcb-view"><?php echo $is_manual ? '<span title="Manuell / bearbeitet">✏️</span>' : '<span title="Automatisch erkannt">🤖</span>'; ?></td>
                    <td class="dcb-view dcb-row-actions">
                        <button class="button button-small dcb-edit-btn">✏️ Bearbeiten</button>
                        <button class="button button-small dcb-delete-cookie" data-key="<?php echo esc_attr( $k ); ?>">🗑️</button>
                    </td>

                    <td class="dcb-edit" style="display:none">
                        <input type="text" class="dcb-in-name regular-text" value="<?php echo esc_attr( $c['name'] ); ?>" placeholder="Cookie-Name">
                    </td>
                    <td class="dcb-edit" style="display:none">
                        <input type="text" class="dcb-in-provider regular-text" value="<?php echo esc_attr( $c['provider'] ); ?>" placeholder="Anbieter">
                    </td>
                    <td class="dcb-edit" style="display:none">
                        <textarea class="dcb-in-purpose large-text" rows="2" placeholder="Zweck"><?php echo esc_textarea( $c['purpose'] ); ?></textarea>
                    </td>
                    <td class="dcb-edit" style="display:none">
                        <input type="text" class="dcb-in-duration" value="<?php echo esc_attr( $c['duration'] ); ?>" placeholder="Laufzeit" style="width:90px">
                    </td>
                    <td class="dcb-edit" style="display:none">
                        <select class="dcb-in-category">
                            <?php foreach ( $cats as $ck => $cc ) : ?>
                                <option value="<?php echo esc_attr( $ck ); ?>" <?php selected( $c['category'] ?? '', $ck ); ?>><?php echo esc_html( $cc['label'] ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="dcb-edit" style="display:none"></td>
                    <td class="dcb-edit" style="display:none">
                        <button class="button button-primary button-small dcb-save-btn">💾 Speichern</button><br><br>
                        <button class="button button-small dcb-cancel-btn">Abbrechen</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; endif; ?>

    <!-- Neuen Cookie hinzufügen -->
    <div id="dcb-add-row-form" style="display:none">
        <h2>Neuen Cookie hinzufügen</h2>
        <div class="dcb-manual-form">
            <table class="form-table">
                <tr><th>Cookie-Name *</th><td><input type="text" id="mc-name" class="regular-text" placeholder="z.B. _my_cookie"><p class="description">Exakter Name des Cookies im Browser.</p></td></tr>
                <tr><th>Kategorie *</th><td>
                    <select id="mc-category">
                        <?php foreach ( $cats as $k => $c ) : ?>
                            <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($c['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td></tr>
                <tr><th>Anbieter</th><td><input type="text" id="mc-provider" class="regular-text" placeholder="z.B. Google LLC"></td></tr>
                <tr><th>Zweck / Beschreibung</th><td><textarea id="mc-purpose" rows="3" class="large-text" placeholder="Wofür wird dieser Cookie verwendet?"></textarea></td></tr>
                <tr><th>Laufzeit</th><td><input type="text" id="mc-duration" class="regular-text" placeholder="z.B. 1 Jahr, Session, 30 Tage"></td></tr>
            </table>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                <button id="dcb-add-manual" class="button button-primary">✅ Cookie speichern</button>
                <button id="dcb-cancel-add" class="button">Abbrechen</button>
                <span id="dcb-manual-status" style="color:green;font-weight:500"></span>
            </div>
        </div>
    </div>

    <div class="dcb-shortcode-hint">
        <strong>💡 Shortcode:</strong> Fügen Sie <code>[dcb_cookie_list]</code> in Ihre Datenschutzerklärung ein, um diese Liste automatisch anzuzeigen.
    </div>
</div>
