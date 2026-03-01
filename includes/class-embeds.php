<?php
/**
 * DCB_Embeds – Datenschutzkonforme Einbettungs-Platzhalter
 *
 * Verwaltet vordefinierte und benutzerdefinierte Platzhalter für externe Einbettungen
 * (YouTube, Google Maps, Vimeo, etc.) die erst nach Einwilligung geladen werden.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Embeds {

    const OPTION_EMBEDS = 'dcb_embeds';

    /* ── Voreinstellungen ──────────────────────────────────────────────────── */

    public static function default_embed_types(): array {
        return array(
            'youtube' => array(
                'id'           => 'youtube',
                'label'        => 'YouTube',
                'category'     => 'marketing',
                'icon'         => '▶',
                'preview_type' => 'thumbnail', // thumbnail | color | image
                'placeholder_title_de' => 'YouTube-Video',
                'placeholder_title_en' => 'YouTube Video',
                'placeholder_text_de'  => 'Dieses Video wird von YouTube bereitgestellt. Mit dem Laden stimmen Sie den Datenschutzbestimmungen von YouTube (Google) zu.',
                'placeholder_text_en'  => 'This video is provided by YouTube. By loading it, you agree to YouTube\'s (Google) privacy policy.',
                'btn_text_de'          => 'Video laden',
                'btn_text_en'          => 'Load video',
                'always_text_de'       => 'Immer für YouTube erlauben',
                'always_text_en'       => 'Always allow YouTube',
                'bg_color'             => '#1a1a1a',
                'accent_color'         => '#ff0000',
                'text_color'           => '#ffffff',
                'enabled'              => true,
            ),
            'googlemaps' => array(
                'id'           => 'googlemaps',
                'label'        => 'Google Maps',
                'category'     => 'marketing',
                'icon'         => '📍',
                'preview_type' => 'color',
                'placeholder_title_de' => 'Google Maps',
                'placeholder_title_en' => 'Google Maps',
                'placeholder_text_de'  => 'Diese Karte wird von Google Maps bereitgestellt. Mit dem Laden stimmen Sie der Datenschutzerklärung von Google zu.',
                'placeholder_text_en'  => 'This map is provided by Google Maps. By loading it, you agree to Google\'s privacy policy.',
                'btn_text_de'          => 'Karte laden',
                'btn_text_en'          => 'Load map',
                'always_text_de'       => 'Immer für Google Maps erlauben',
                'always_text_en'       => 'Always allow Google Maps',
                'bg_color'             => '#e8f0fe',
                'accent_color'         => '#4285f4',
                'text_color'           => '#1a1a1a',
                'enabled'              => true,
            ),
            'vimeo' => array(
                'id'           => 'vimeo',
                'label'        => 'Vimeo',
                'category'     => 'marketing',
                'icon'         => '▶',
                'preview_type' => 'color',
                'placeholder_title_de' => 'Vimeo-Video',
                'placeholder_title_en' => 'Vimeo Video',
                'placeholder_text_de'  => 'Dieses Video wird von Vimeo bereitgestellt. Mit dem Laden stimmen Sie der Datenschutzerklärung von Vimeo zu.',
                'placeholder_text_en'  => 'This video is provided by Vimeo. By loading it, you agree to Vimeo\'s privacy policy.',
                'btn_text_de'          => 'Video laden',
                'btn_text_en'          => 'Load video',
                'always_text_de'       => 'Immer für Vimeo erlauben',
                'always_text_en'       => 'Always allow Vimeo',
                'bg_color'             => '#1ab7ea',
                'accent_color'         => '#ffffff',
                'text_color'           => '#ffffff',
                'enabled'              => true,
            ),
            'googlemaps_iframe' => array(
                'id'           => 'googlemaps_iframe',
                'label'        => 'Google Maps (iFrame)',
                'category'     => 'marketing',
                'icon'         => '🗺',
                'preview_type' => 'color',
                'placeholder_title_de' => 'Google Maps',
                'placeholder_title_en' => 'Google Maps',
                'placeholder_text_de'  => 'Diese Karte wird von Google Maps bereitgestellt.',
                'placeholder_text_en'  => 'This map is provided by Google Maps.',
                'btn_text_de'          => 'Karte laden',
                'btn_text_en'          => 'Load map',
                'always_text_de'       => 'Immer für Google Maps erlauben',
                'always_text_en'       => 'Always allow Google Maps',
                'bg_color'             => '#e8f4ea',
                'accent_color'         => '#34a853',
                'text_color'           => '#1a1a1a',
                'enabled'              => true,
            ),
            'instagram' => array(
                'id'           => 'instagram',
                'label'        => 'Instagram',
                'category'     => 'marketing',
                'icon'         => '📷',
                'preview_type' => 'color',
                'placeholder_title_de' => 'Instagram-Beitrag',
                'placeholder_title_en' => 'Instagram Post',
                'placeholder_text_de'  => 'Dieser Beitrag stammt von Instagram. Mit dem Laden stimmen Sie den Datenschutzbestimmungen von Meta zu.',
                'placeholder_text_en'  => 'This post is from Instagram. By loading it, you agree to Meta\'s privacy policy.',
                'btn_text_de'          => 'Beitrag laden',
                'btn_text_en'          => 'Load post',
                'always_text_de'       => 'Immer für Instagram erlauben',
                'always_text_en'       => 'Always allow Instagram',
                'bg_color'             => '#f9f9f9',
                'accent_color'         => '#e1306c',
                'text_color'           => '#1a1a1a',
                'enabled'              => true,
            ),
            'twitter' => array(
                'id'           => 'twitter',
                'label'        => 'X / Twitter',
                'category'     => 'marketing',
                'icon'         => '𝕏',
                'preview_type' => 'color',
                'placeholder_title_de' => 'X-Beitrag (Twitter)',
                'placeholder_title_en' => 'X Post (Twitter)',
                'placeholder_text_de'  => 'Dieser Beitrag stammt von X (ehemals Twitter). Mit dem Laden stimmen Sie den Datenschutzbestimmungen von X zu.',
                'placeholder_text_en'  => 'This post is from X (formerly Twitter). By loading it, you agree to X\'s privacy policy.',
                'btn_text_de'          => 'Beitrag laden',
                'btn_text_en'          => 'Load post',
                'always_text_de'       => 'Immer für X erlauben',
                'always_text_en'       => 'Always allow X',
                'bg_color'             => '#000000',
                'accent_color'         => '#ffffff',
                'text_color'           => '#ffffff',
                'enabled'              => true,
            ),
            'facebook' => array(
                'id'           => 'facebook',
                'label'        => 'Facebook',
                'category'     => 'marketing',
                'icon'         => 'f',
                'preview_type' => 'color',
                'placeholder_title_de' => 'Facebook-Beitrag',
                'placeholder_title_en' => 'Facebook Post',
                'placeholder_text_de'  => 'Dieser Beitrag stammt von Facebook. Mit dem Laden stimmen Sie den Datenschutzbestimmungen von Meta zu.',
                'placeholder_text_en'  => 'This post is from Facebook. By loading it, you agree to Meta\'s privacy policy.',
                'btn_text_de'          => 'Beitrag laden',
                'btn_text_en'          => 'Load post',
                'always_text_de'       => 'Immer für Facebook erlauben',
                'always_text_en'       => 'Always allow Facebook',
                'bg_color'             => '#f0f2f5',
                'accent_color'         => '#1877f2',
                'text_color'           => '#1a1a1a',
                'enabled'              => true,
            ),
            'openstreetmap' => array(
                'id'           => 'openstreetmap',
                'label'        => 'OpenStreetMap',
                'category'     => 'statistics',
                'icon'         => '🗺',
                'preview_type' => 'color',
                'placeholder_title_de' => 'OpenStreetMap',
                'placeholder_title_en' => 'OpenStreetMap',
                'placeholder_text_de'  => 'Diese Karte wird von OpenStreetMap bereitgestellt.',
                'placeholder_text_en'  => 'This map is provided by OpenStreetMap.',
                'btn_text_de'          => 'Karte laden',
                'btn_text_en'          => 'Load map',
                'always_text_de'       => 'Immer für OpenStreetMap erlauben',
                'always_text_en'       => 'Always allow OpenStreetMap',
                'bg_color'             => '#f2efe9',
                'accent_color'         => '#7ebc6f',
                'text_color'           => '#333333',
                'enabled'              => true,
            ),
        );
    }

    /* ── CRUD ──────────────────────────────────────────────────────────────── */

    public static function get_embeds(): array {
        $saved    = get_option( self::OPTION_EMBEDS, array() );
        $defaults = self::default_embed_types();
        // Merge: saved overrides defaults, custom types are appended
        $result = array();
        foreach ( $defaults as $id => $def ) {
            $result[ $id ] = isset( $saved[ $id ] ) ? array_merge( $def, $saved[ $id ] ) : $def;
        }
        // Custom types (not in defaults)
        foreach ( $saved as $id => $embed ) {
            if ( ! isset( $result[ $id ] ) ) {
                $result[ $id ] = $embed;
            }
        }
        return $result;
    }

    public static function get_embed( string $id ): ?array {
        $embeds = self::get_embeds();
        return $embeds[ $id ] ?? null;
    }

    public static function save_embeds( array $embeds ): void {
        update_option( self::OPTION_EMBEDS, $embeds );
    }

    public static function save_embed( string $id, array $data ): bool {
        $saved = get_option( self::OPTION_EMBEDS, array() );
        $saved[ $id ] = $data;
        return (bool) update_option( self::OPTION_EMBEDS, $saved );
    }

    public static function delete_embed( string $id ): void {
        $defaults = self::default_embed_types();
        $saved    = get_option( self::OPTION_EMBEDS, array() );
        if ( isset( $defaults[ $id ] ) ) {
            // Reset to default by removing from saved
            unset( $saved[ $id ] );
        } else {
            // Custom type: remove entirely
            unset( $saved[ $id ] );
        }
        update_option( self::OPTION_EMBEDS, $saved );
    }

    /* ── Helper: localised text ────────────────────────────────────────────── */

    public static function get_text( array $embed, string $field ): string {
        $lang = DCB_I18n::get_lang();
        $key  = $field . '_' . $lang;
        return $embed[ $key ] ?? $embed[ $field . '_de' ] ?? '';
    }

    /* ── Sanitise one embed array ──────────────────────────────────────────── */

    public static function sanitise_embed( array $input ): array {
        $cats = array_keys( DCB_Cookie_Manager::get_settings()['categories'] ?? array() );

        return array(
            'id'                   => sanitize_key(         $input['id']            ?? '' ),
            'label'                => sanitize_text_field(  $input['label']         ?? '' ),
            'category'             => in_array( $input['category'] ?? '', $cats, true )
                                        ? $input['category']
                                        : 'marketing',
            'icon'                 => sanitize_text_field(  $input['icon']          ?? '▶' ),
            'preview_type'         => in_array( $input['preview_type'] ?? '', array('thumbnail','color','image'), true )
                                        ? $input['preview_type']
                                        : 'color',
            'placeholder_title_de' => sanitize_text_field(  $input['placeholder_title_de'] ?? '' ),
            'placeholder_title_en' => sanitize_text_field(  $input['placeholder_title_en'] ?? '' ),
            'placeholder_text_de'  => sanitize_textarea_field( $input['placeholder_text_de']  ?? '' ),
            'placeholder_text_en'  => sanitize_textarea_field( $input['placeholder_text_en']  ?? '' ),
            'btn_text_de'          => sanitize_text_field(  $input['btn_text_de']   ?? '' ),
            'btn_text_en'          => sanitize_text_field(  $input['btn_text_en']   ?? '' ),
            'always_text_de'       => sanitize_text_field(  $input['always_text_de'] ?? '' ),
            'always_text_en'       => sanitize_text_field(  $input['always_text_en'] ?? '' ),
            'bg_color'             => sanitize_hex_color(   $input['bg_color']      ?? '#1a1a1a' ),
            'accent_color'         => sanitize_hex_color(   $input['accent_color']  ?? '#ffffff' ),
            'text_color'           => sanitize_hex_color(   $input['text_color']    ?? '#ffffff' ),
            'enabled'              => ! empty( $input['enabled'] ),
            'custom_image_url'     => esc_url_raw(          $input['custom_image_url'] ?? '' ),
        );
    }
}
