<?php if ( ! defined( 'ABSPATH' ) ) exit;

$settings = DCB_Cookie_Manager::get_settings();
$lang     = $settings['language'] ?? 'de';
$i        = 'DCB_I18n';
?>
<div class="wrap dcb-admin-wrap">
    <h1><?php echo esc_html( $i::t('admin_submenu_help') ); ?></h1>

    <div class="dcb-help-wrap">

    <!-- ── Cookie-Scanner ── -->
    <div class="dcb-help-section">
        <h3><?php echo esc_html( $i::t('help_scanner_title') ); ?></h3>
        <p><?php echo esc_html( $i::t('help_scanner_intro') ); ?></p>
        <table class="widefat dcb-help-table">
            <thead><tr>
                <th style="width:22%"><?php echo $lang === 'de' ? 'Phase' : 'Phase'; ?></th>
                <th><?php echo $lang === 'de' ? 'Beschreibung' : 'Description'; ?></th>
            </tr></thead>
            <tbody>
                <tr>
                    <td><strong>1 – <?php echo $lang === 'de' ? 'Server-Scan' : 'Server Scan'; ?></strong></td>
                    <td><?php echo esc_html( $i::t('help_scanner_phase1') ); ?></td>
                </tr>
                <tr>
                    <td><strong>2 – <?php echo $lang === 'de' ? 'Browser-Scan' : 'Browser Scan'; ?></strong></td>
                    <td><?php echo esc_html( $i::t('help_scanner_phase2') ); ?></td>
                </tr>
            </tbody>
        </table>
        <table class="widefat dcb-help-table" style="margin-top:12px">
            <thead><tr>
                <th style="width:22%"><?php echo $lang === 'de' ? 'Funktion' : 'Feature'; ?></th>
                <th><?php echo $lang === 'de' ? 'Erklärung' : 'Explanation'; ?></th>
            </tr></thead>
            <tbody>
                <tr>
                    <td><span style="color:#e67e00;font-weight:700">⚠ <?php echo $lang === 'de' ? 'Unvollständig' : 'Incomplete'; ?></span></td>
                    <td><?php echo esc_html( $i::t('help_scanner_incomplete') ); ?></td>
                </tr>
                <tr>
                    <td><strong>🗑 <?php echo $lang === 'de' ? 'Reset' : 'Reset'; ?></strong></td>
                    <td><?php echo esc_html( $i::t('help_scanner_reset') ); ?></td>
                </tr>
                <tr>
                    <td><strong>🤖 <?php echo $lang === 'de' ? 'Quelle' : 'Source'; ?></strong></td>
                    <td><?php echo esc_html( $i::t('help_scanner_source') ); ?></td>
                </tr>
            </tbody>
        </table>
        <p class="description"><a href="<?php echo esc_url( admin_url('admin.php?page=dcb-scanner') ); ?>"><?php echo $lang === 'de' ? 'Zum Cookie-Scanner →' : 'Go to Cookie Scanner →'; ?></a></p>
    </div>

    <!-- ── Shortcodes ── -->
    <div class="dcb-help-section">
        <h3><?php echo esc_html( $i::t('help_shortcodes_title') ); ?></h3>
        <table class="widefat dcb-help-table">
            <thead><tr>
                <th><?php echo $lang === 'de' ? 'Shortcode' : 'Shortcode'; ?></th>
                <th><?php echo $lang === 'de' ? 'Beschreibung' : 'Description'; ?></th>
            </tr></thead>
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
            <thead><tr>
                <th>Shortcode</th>
                <th><?php echo $lang === 'de' ? 'Beispiel' : 'Example'; ?></th>
            </tr></thead>
            <tbody>
                <tr><td><code>[dcb_youtube]</code></td><td><code>[dcb_youtube id="dQw4w9WgXcQ" width="100%" height="400"]</code></td></tr>
                <tr><td><code>[dcb_vimeo]</code></td><td><code>[dcb_vimeo id="123456789"]</code></td></tr>
                <tr><td><code>[dcb_googlemaps]</code></td><td><code>[dcb_googlemaps src="https://maps.google.com/maps?q=Berlin&amp;output=embed" height="450"]</code></td></tr>
                <tr><td><code>[dcb_openstreetmap]</code></td><td><code>[dcb_openstreetmap lat="52.52" lng="13.40" zoom="14"]</code></td></tr>
                <tr><td><code>[dcb_instagram]</code></td><td><code>[dcb_instagram url="https://www.instagram.com/p/ABC123/"]</code></td></tr>
                <tr><td><code>[dcb_twitter]</code></td><td><code>[dcb_twitter url="https://twitter.com/user/status/123"]</code></td></tr>
                <tr><td><code>[dcb_facebook]</code></td><td><code>[dcb_facebook url="https://www.facebook.com/post/123"]</code></td></tr>
                <tr><td><code>[dcb_embed]</code></td><td><code>[dcb_embed type="youtube" id="dQw4w9WgXcQ"]</code></td></tr>
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
        <?php $cats = $settings['categories'] ?? array();
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
            <?php for ( $n = 1; $n <= 9; $n++ ) : ?>
            <li><?php echo esc_html( $i::t( 'help_check_' . $n ) ); ?></li>
            <?php endfor; ?>
        </ul>
    </div>

    <!-- ── Version / Links ── -->
    <div class="dcb-help-section dcb-help-footer">
        <strong><?php echo $lang === 'de' ? 'DSGVO Cookie Banner' : 'GDPR Cookie Banner'; ?> v<?php echo esc_html( DCB_VERSION ); ?></strong>
        &nbsp;·&nbsp; PHP <?php echo esc_html( phpversion() ); ?>
        &nbsp;·&nbsp; WordPress <?php global $wp_version; echo esc_html( $wp_version ); ?>
        &nbsp;·&nbsp; <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-settings') ); ?>"><?php echo $lang === 'de' ? 'Einstellungen' : 'Settings'; ?></a>
        &nbsp;·&nbsp; <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-scanner') ); ?>"><?php echo $lang === 'de' ? 'Cookie-Scanner' : 'Cookie Scanner'; ?></a>
        &nbsp;·&nbsp; <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-embeds') ); ?>"><?php echo $lang === 'de' ? 'Einbettungen' : 'Embeds'; ?></a>
        &nbsp;·&nbsp; <a href="<?php echo esc_url( admin_url('admin.php?page=dcb-consents') ); ?>"><?php echo $lang === 'de' ? 'Einwilligungsprotokoll' : 'Consent Log'; ?></a>
    </div>

    </div><!-- .dcb-help-wrap -->
</div>
