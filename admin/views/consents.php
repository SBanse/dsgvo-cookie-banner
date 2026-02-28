<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap dcb-admin-wrap">
    <h1>📋 Einwilligungsprotokoll</h1>
    <p>Protokollierte Nutzer-Einwilligungen (IP-Adressen werden als Hash gespeichert – DSGVO-konform).</p>

    <?php if ( empty( $consents ) ) : ?>
        <p>Noch keine Einwilligungen protokolliert.</p>
    <?php else : ?>
    <table class="widefat striped">
        <thead>
            <tr>
                <th>Einwilligungs-ID</th>
                <th>IP-Hash</th>
                <th>Kategorien</th>
                <th>Zeitpunkt</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $consents as $c ) :
            $data = json_decode( $c->consent_data, true );
            $cats = implode( ', ', array_keys( array_filter( $data['categories'] ?? array() ) ) );
        ?>
            <tr>
                <td><code><?php echo esc_html( $c->consent_id ); ?></code></td>
                <td><code><?php echo esc_html( substr( $c->ip_hash, 0, 16 ) ); ?>…</code></td>
                <td><?php echo esc_html( $cats ); ?></td>
                <td><?php echo esc_html( $c->created_at ); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
