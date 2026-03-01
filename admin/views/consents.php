<?php if ( ! defined( 'ABSPATH' ) ) exit;
$i = 'DCB_I18n';
?>
<div class="wrap dcb-admin-wrap">
    <h1><?php echo esc_html( $i::t('admin_consents_title') ); ?></h1>
    <p><?php echo esc_html( $i::t('consents_intro') ); ?></p>
    <?php if ( empty( $consents ) ) : ?>
        <p><?php echo esc_html( $i::t('consents_none') ); ?></p>
    <?php else : ?>
    <table class="widefat striped">
        <thead>
            <tr>
                <th><?php echo esc_html( $i::t('col_consent_id') ); ?></th>
                <th><?php echo esc_html( $i::t('col_ip_hash') ); ?></th>
                <th><?php echo esc_html( $i::t('col_categories') ); ?></th>
                <th><?php echo esc_html( $i::t('col_timestamp') ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $consents as $c ) :
            $data = json_decode( $c->consent_data, true );
            $accepted = array_keys( array_filter( $data['categories'] ?? array() ) );
        ?>
            <tr>
                <td><code><?php echo esc_html( $c->consent_id ); ?></code></td>
                <td><code><?php echo esc_html( substr( $c->ip_hash, 0, 16 ) ); ?>…</code></td>
                <td><?php echo esc_html( implode( ', ', $accepted ) ); ?></td>
                <td><?php echo esc_html( $c->created_at ); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
