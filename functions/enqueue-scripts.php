<?php
/**
 * Load scripts
 *
 * @param string $handle
 * @param string $rel_src
 * @param array  $deps
 * @param bool   $in_footer
 */
function zume_enqueue_script( $handle, $rel_src, $deps = array(), $in_footer = false ) {
    if ( $rel_src[0] === "/" ) {
        throw new Error( "zume_enqueue_script took \$rel_src argument which unexpectedly started with /" );
    }
    wp_enqueue_script( $handle, get_template_directory_uri() . "/$rel_src", $deps, filemtime( get_template_directory() . "/$rel_src" ), $in_footer );
}

/**
 * Load styles
 *
 * @param string $handle
 * @param string $rel_src
 * @param array  $deps
 * @param string $media
 */
function zume_enqueue_style( $handle, $rel_src, $deps = array(), $media = 'all' ) {
    if ( $rel_src[0] === "/" ) {
        throw new Error( "zume_enqueue_style took \$rel_src argument which unexpectedly started with /" );
    }
    wp_enqueue_style( $handle, get_template_directory_uri() . "/$rel_src", $deps, filemtime( get_template_directory() . "/$rel_src" ), $media );
}

function site_scripts() {
    global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
    $zume_user = wp_get_current_user();
    $zume_user_meta = zume_get_user_meta( $zume_user->ID );

    // main minimized scripts for loaded on all pages
    wp_enqueue_script( 'site-js', get_template_directory_uri() . '/assets/scripts/scripts.js', array( 'jquery', 'lodash', 'wp-i18n' ), filemtime( get_template_directory() . '/assets/scripts/scripts.js' ), true );

    wp_register_script( 'zume-core', get_template_directory_uri() . '/assets/scripts/api.js', array( 'jquery', 'lodash', 'wp-i18n' ), filemtime( get_theme_file_path() . '/assets/scripts/api.js' ), true );
    wp_enqueue_script( 'zume-core' );
    wp_localize_script(
        "zume-core", "zumeCore", array(
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'theme_uri' => get_stylesheet_directory_uri(),
            'map_key' => DT_Mapbox_API::get_key(),
            'logged_in' => is_user_logged_in(),
        )
    );

    // join community
    wp_enqueue_script( 'join', get_template_directory_uri() . '/assets/scripts/join.js', array( 'jquery', 'lodash', 'wp-i18n', 'zume-core' ), filemtime( get_template_directory() . '/assets/scripts/join.js' ), true );
    wp_localize_script(
        "join", "zumeJoin", array(
            'map_key' => DT_Mapbox_API::get_key(),
        )
    );

    // lodash load
    wp_register_script( 'lodash', 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js', false, '4.17.11' );
    wp_enqueue_script( 'lodash' );

    // main stylesheet
    wp_enqueue_style( 'site-css', get_template_directory_uri() . '/assets/styles/style.css', array(), filemtime( get_template_directory() . '/assets/styles/style.css' ), 'all' );
    wp_style_add_data( 'site-css', 'rtl', 'replace' );

    // script for threaded comments
    if ( is_singular() and comments_open() and ( get_option( 'thread_comments' ) == 1 )) {
        wp_enqueue_script( 'comment-reply' );
    }

    // foundation styles
    wp_enqueue_style( 'foundations-icons', get_template_directory_uri() .'/assets/styles/foundation-icons/foundation-icons.css', array(), '3' );


    /**
     * All Data Driven Pages
     */
    if ( 'template-maps' === substr( basename( get_page_template() ), 0, 13 )
        || 'template-statistics.php' === basename( get_page_template() )
    ) {
        wp_register_script( 'mapbox', 'https://api.tiles.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.js', [ 'jquery' ], '1.2.0' );
        wp_enqueue_script( 'mapbox' );
        wp_enqueue_style( 'mapbox-css', 'https://api.tiles.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.css', array(), '3' );
    }


    /**
     * Home Page
     */
    if ( 'template-home.php' === basename( get_page_template() ) || 'template-statistics.php' === basename( get_page_template() ) || is_single() || is_archive() ) {

        wp_enqueue_script( 'counters', get_template_directory_uri() . '/assets/scripts/counters.js', array( 'jquery', 'lodash', 'wp-i18n', 'zume-core' ), 1.1, true );
        wp_localize_script(
            "counters", "zumeCounters", array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_login' => wp_get_current_user()->user_login,
                'current_user_id' => get_current_user_id(),
                'theme_uri' => get_stylesheet_directory_uri(),
                'statistics' => Zume_Statistics::instance()->statistics(),
            )
        );
    }

    /**
     * Profile Page
     */
    if ( 'template-account.php' === basename( get_page_template() ) ) {
        wp_register_script( 'lodash', 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js', false, '4.17.11' );
        wp_enqueue_script( 'lodash' );

        wp_enqueue_script( 'zume-profile', get_template_directory_uri() . '/assets/scripts/profile.js', array( 'jquery', 'lodash', 'wp-i18n', 'zume-core' ), filemtime( get_theme_file_path() . '/assets/scripts/profile.js' ), true );
        wp_localize_script(
            "zume-profile", "zumeProfile", array(
                'root' => esc_url_raw( rest_url() ),
                'theme_uri' => get_stylesheet_directory_uri(),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_id' => get_current_user_id(),
                'user_profile_fields' => [
                    'id' => $zume_user->data->ID,
                    'name' => $zume_user_meta['zume_full_name'] ?? '',
                    'email' => $zume_user->data->user_email,
                    'phone' => $zume_user_meta['zume_phone_number'] ?? '',
                    'location_grid_meta' => maybe_unserialize( $zume_user_meta['location_grid_meta'] ) ?? '',
                    'affiliation_key' => $zume_user_meta['zume_affiliation_key'] ?? '',
                    'facebook_sso_email' => $zume_user_meta['facebook_sso_email'] ?? false,
                    'google_sso_email' => $zume_user_meta['google_sso_email'] ?? false,
                ],
                'logged_in' => is_user_logged_in(),
                'map_key' => DT_Mapbox_API::get_key(),
            )
        );
    }

    if ( 'template-join.php' === basename( get_page_template() ) ) {
        wp_register_script( 'lodash', 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js', false, '4.17.11' );
        wp_enqueue_script( 'lodash' );

        wp_enqueue_script( 'zume-join', get_template_directory_uri() . '/assets/scripts/profile.js', array( 'jquery', 'lodash', 'wp-i18n', 'zume-core' ), filemtime( get_theme_file_path() . '/assets/scripts/profile.js' ), true );
        wp_localize_script(
            "zume-join", "zumeJoin", array(
                'root' => esc_url_raw( rest_url() ),
                'theme_uri' => get_stylesheet_directory_uri(),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'map_key' => DT_Mapbox_API::get_key(),
            )
        );
    }

}
add_action( 'wp_enqueue_scripts', 'site_scripts', 999 );


function zume_login_css() {
    zume_enqueue_style( 'zume_login_css', 'assets/styles/login.css', array() );
}
add_action( 'login_enqueue_scripts', 'zume_login_css', 999 );


// calling it only on the login page
add_action( 'login_enqueue_scripts', 'zume_login_css', 10 );

