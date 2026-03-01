<?php if ( ! defined( 'ABSPATH' ) ) exit;
$auto     = $stored['auto']   ?? array();
$manual   = $stored['manual'] ?? array();
$all      = array_merge( $auto, $manual );
$settings = DCB_Cookie_Manager::get_settings();
$cats     = $settings['categories'];
$i        = 'DCB_I18n';
?>
<div class="wrap dcb-admin-wrap">
    <h1><?php echo esc_html( $i::t('admin_scanner_title') ); ?></h1>
    <p><?php echo esc_html( $i::t('scanner_intro') ); ?></p>

    <div class="dcb-scan-box">
        <button id="dcb-run-scan" class="button button-primary button-hero"><?php echo esc_html( $i::t('scan_start_btn') ); ?></button>
        <span id="dcb-scan-status"></span>
        <?php if ( isset( $stored['last_scan'] ) ) : ?>
            <small style="display:block;margin-top:6px;color:#666"><?php echo esc_html( $i::t('last_scan') ); ?> <?php echo esc_html( $stored['last_scan'] ); ?></small>
        <?php endif; ?>
    </div>

    <div class="dcb-table-header">
        <h2 style="margin:0"><?php echo esc_html( $i::t('cookie_list_title') ); ?> (<?php echo count( $all ); ?> <?php echo esc_html( $i::t('cookie_list_entries') ); ?>)</h2>
        <button id="dcb-add-row-btn" class="button button-secondary"><?php echo esc_html( $i::t('add_cookie_btn') ); ?></button>
    </div>

    <?php if ( empty( $all ) ) : ?>
        <p class="dcb-empty-notice"><?php echo esc_html( $i::t('no_cookies_yet') ); ?></p>
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
                    <th style="width:17%"><?php echo esc_html( $i::t('col_cookie_name') ); ?></th>
                    <th style="width:14%"><?php echo esc_html( $i::t('col_provider') ); ?></th>
                    <th style="width:31%"><?php echo esc_html( $i::t('col_purpose') ); ?></th>
                    <th style="width:10%"><?php echo esc_html( $i::t('col_duration') ); ?></th>
                    <th style="width:12%"><?php echo esc_html( $i::t('col_category') ); ?></th>
                    <th style="width:7%"><?php echo esc_html( $i::t('col_source') ); ?></th>
                    <th style="width:9%"><?php echo esc_html( $i::t('col_actions') ); ?></th>
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
                    <td class="dcb-view"><?php echo $is_manual
                        ? '<span title="' . esc_attr( $i::t('source_manual') ) . '">✏️</span>'
                        : '<span title="' . esc_attr( $i::t('source_auto') )   . '">🤖</span>'; ?>
                    </td>
                    <td class="dcb-view dcb-row-actions">
                        <button class="button button-small dcb-edit-btn"><?php echo esc_html( $i::t('btn_edit') ); ?></button>
                        <button class="button button-small dcb-delete-cookie" data-key="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $i::t('btn_delete') ); ?></button>
                    </td>

                    <td class="dcb-edit" style="display:none">
                        <input type="text" class="dcb-in-name regular-text" value="<?php echo esc_attr( $c['name'] ); ?>" placeholder="<?php echo esc_attr( $i::t('field_cookie_name_ph') ); ?>">
                    </td>
                    <td class="dcb-edit" style="display:none">
                        <input type="text" class="dcb-in-provider regular-text" value="<?php echo esc_attr( $c['provider'] ); ?>" placeholder="<?php echo esc_attr( $i::t('field_provider_ph') ); ?>">
                    </td>
                    <td class="dcb-edit" style="display:none">
                        <textarea class="dcb-in-purpose large-text" rows="2" placeholder="<?php echo esc_attr( $i::t('field_purpose_ph') ); ?>"><?php echo esc_textarea( $c['purpose'] ); ?></textarea>
                    </td>
                    <td class="dcb-edit" style="display:none">
                        <input type="text" class="dcb-in-duration" value="<?php echo esc_attr( $c['duration'] ); ?>" placeholder="<?php echo esc_attr( $i::t('field_duration_ph') ); ?>" style="width:90px">
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
                        <button class="button button-primary button-small dcb-save-btn"><?php echo esc_html( $i::t('btn_save') ); ?></button><br><br>
                        <button class="button button-small dcb-cancel-btn"><?php echo esc_html( $i::t('btn_cancel') ); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; endif; ?>

    <!-- Add new cookie -->
    <div id="dcb-add-row-form" style="display:none">
        <h2><?php echo esc_html( $i::t('add_cookie_title') ); ?></h2>
        <div class="dcb-manual-form">
            <table class="form-table">
                <tr>
                    <th><?php echo esc_html( $i::t('field_cookie_name') ); ?></th>
                    <td><input type="text" id="mc-name" class="regular-text" placeholder="<?php echo esc_attr( $i::t('field_cookie_name_ph') ); ?>">
                    <p class="description"><?php echo esc_html( $i::t('field_cookie_name_desc') ); ?></p></td>
                </tr>
                <tr>
                    <th><?php echo esc_html( $i::t('field_category') ); ?></th>
                    <td>
                        <select id="mc-category">
                            <?php foreach ( $cats as $k => $c ) : ?>
                                <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($c['label']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr><th><?php echo esc_html( $i::t('field_provider') ); ?></th><td><input type="text" id="mc-provider" class="regular-text" placeholder="<?php echo esc_attr( $i::t('field_provider_ph') ); ?>"></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_purpose') ); ?></th><td><textarea id="mc-purpose" rows="3" class="large-text" placeholder="<?php echo esc_attr( $i::t('field_purpose_ph') ); ?>"></textarea></td></tr>
                <tr><th><?php echo esc_html( $i::t('field_duration') ); ?></th><td><input type="text" id="mc-duration" class="regular-text" placeholder="<?php echo esc_attr( $i::t('field_duration_ph') ); ?>"></td></tr>
            </table>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                <button id="dcb-add-manual" class="button button-primary"><?php echo esc_html( $i::t('btn_save_cookie') ); ?></button>
                <button id="dcb-cancel-add" class="button"><?php echo esc_html( $i::t('btn_cancel') ); ?></button>
                <span id="dcb-manual-status" style="color:green;font-weight:500"></span>
            </div>
        </div>
    </div>

    <div class="dcb-shortcode-hint">
        <?php echo esc_html( $i::t('shortcode_hint') ); ?>
    </div>
</div>
