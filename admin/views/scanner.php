<?php if ( ! defined( 'ABSPATH' ) ) exit; 
$auto   = $stored['auto']   ?? array();
$manual = $stored['manual'] ?? array();
$all    = array_merge( $auto, $manual );
$settings = DCB_Cookie_Manager::get_settings();
$cats   = $settings['categories'];
?>
<div class="wrap dcb-admin-wrap">
    <h1>🔍 Cookie-Scanner</h1>
    <p>Scannt Ihre WordPress-Installation auf verwendete Cookies und ordnet diese den DSGVO-Kategorien zu.</p>

    <div class="dcb-scan-box">
        <button id="dcb-run-scan" class="button button-primary button-hero">🔍 Scan starten</button>
        <span id="dcb-scan-status"></span>
        <?php if ( isset( $stored['last_scan'] ) ) : ?>
            <p><small>Letzter Scan: <?php echo esc_html( $stored['last_scan'] ); ?></small></p>
        <?php endif; ?>
    </div>

    <h2>Erkannte Cookies (<?php echo count( $all ); ?>)</h2>

    <?php if ( empty( $all ) ) : ?>
        <p>Noch keine Cookies erkannt. Starten Sie zuerst einen Scan.</p>
    <?php else :
        $grouped = array();
        foreach ( $all as $k => $c ) {
            $grouped[ $c['category'] ][ $k ] = $c;
        }
        foreach ( $grouped as $cat_key => $group ) :
            $cat_label = $cats[ $cat_key ]['label'] ?? ucfirst( $cat_key );
    ?>
        <h3><?php echo esc_html( $cat_label ); ?> (<?php echo count( $group ); ?>)</h3>
        <table class="widefat striped">
            <thead><tr><th>Name</th><th>Anbieter</th><th>Zweck</th><th>Laufzeit</th><th>Quelle</th><th>Aktion</th></tr></thead>
            <tbody>
            <?php foreach ( $group as $k => $c ) : ?>
                <tr>
                    <td><code><?php echo esc_html( $c['name'] ); ?></code></td>
                    <td><?php echo esc_html( $c['provider'] ); ?></td>
                    <td><?php echo esc_html( $c['purpose'] ); ?></td>
                    <td><?php echo esc_html( $c['duration'] ); ?></td>
                    <td><?php echo isset( $manual[ str_replace( 'manual_', '', $k ) ] ) ? '✏️ Manuell' : '🤖 Auto'; ?></td>
                    <td><button class="button button-small dcb-delete-cookie" data-key="<?php echo esc_attr( $k ); ?>">Löschen</button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; endif; ?>

    <h2 style="margin-top:30px">Cookie manuell hinzufügen</h2>
    <div class="dcb-manual-form">
        <table class="form-table">
            <tr><th>Cookie-Name *</th><td><input type="text" id="mc-name" class="regular-text" placeholder="z.B. _my_cookie"></td></tr>
            <tr><th>Kategorie *</th>
                <td>
                    <select id="mc-category">
                        <?php foreach ( $cats as $k => $c ) : ?>
                            <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($c['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr><th>Anbieter</th><td><input type="text" id="mc-provider" class="regular-text"></td></tr>
            <tr><th>Zweck</th><td><textarea id="mc-purpose" rows="2" class="large-text"></textarea></td></tr>
            <tr><th>Laufzeit</th><td><input type="text" id="mc-duration" class="regular-text" placeholder="z.B. 1 Jahr"></td></tr>
        </table>
        <button id="dcb-add-manual" class="button button-secondary">Cookie hinzufügen</button>
        <span id="dcb-manual-status"></span>
    </div>

    <div class="dcb-shortcode-hint">
        <p>💡 Fügen Sie <code>[dcb_cookie_list]</code> in Ihre Datenschutzerklärung ein, um diese Liste automatisch anzuzeigen.</p>
    </div>
</div>
