<?php


// Add permission for mapping system
//$current_user = wp_get_current_user();
//$current_user->add_cap( 'view_mapping' );
//if ( ! class_exists( 'DT_Mapping_Module' ) ) {
//    require_once( 'dt-mapping/loader.php' );
//    new DT_Mapping_Module_Loader( 'theme' );
//}
// end mapping system load


// Theme support options
require_once( get_template_directory().'/functions/default-theme-configuration/theme-support.php' );

// WP Head and other cleanup functions
require_once( get_template_directory().'/functions/default-theme-configuration/cleanup.php' );

// Register custom menus and menu walkers
require_once( get_template_directory().'/functions/default-theme-configuration/menu.php' );

// Register sidebars/widget areas
require_once( get_template_directory().'/functions/default-theme-configuration/sidebar.php' );

// Makes WordPress comments suck less
require_once( get_template_directory().'/functions/default-theme-configuration/comments.php' );

// Adds support for multiple languages
require_once( get_template_directory().'/functions/translation/translation.php' );

// Remove Emoji Support
require_once( get_template_directory().'/functions/default-theme-configuration/disable-emoji.php' );

// Related post function - no need to rely on plugins
 require_once( get_template_directory().'/functions/default-theme-configuration/related-posts.php' );

// Customize the WordPress admin
//require_once( get_template_directory().'/functions/admin/admin.php' );
require_once( get_template_directory().'/functions/admin/admin-page.php' );

// Custom Login
require_once( get_template_directory().'/functions/login/zume-login.php' );
require_once( get_template_directory().'/functions/urls.php' );


require_once( get_template_directory().'/functions/post-type-playbook.php' );
require_once( get_template_directory().'/functions/post-type-reports.php' );
require_once( get_template_directory().'/functions/post-type-articles.php' );
require_once( get_template_directory().'/functions/population-counters.php' );

// Integrations
require_once( get_template_directory().'/functions/report-send-integration.php' );
require_once( get_template_directory().'/functions/site-link-post-type.php' );
Site_Link_System::instance();

// Register scripts and stylesheets
require_once( get_template_directory().'/functions/enqueue-scripts.php' );
require_once( get_template_directory().'/functions/rest-api.php' );
require_once( get_template_directory().'/functions/statistics.php' );
require_once( get_template_directory().'/functions/multi-role/multi-role.php' );

// maps
add_action( 'wp_head', 'zume_captcha_header' );

/**
 * GLOBAL FUNCTIONS
 */


function zume_get_user_meta( $user_id = null ) {

    if ( ! is_user_logged_in() ) {
        return [];
    }
    if ( is_null( $user_id ) ) {
        $user_id = get_current_user_id();
    }
    return array_map( function ( $a ) { return maybe_unserialize( $a[0] );
    }, get_user_meta( $user_id ) );
}

/**
 * END GLOBAL FUNCTIONS
 */

//
///**
// * Gets or Creates Network Site Profile
// * @return array
// */
//if ( ! function_exists( 'dt_network_site_profile' ) ) {
//    function dt_network_site_profile() {
//        $profile = get_option( 'dt_site_profile' );
//
//        if ( empty( $profile ) || empty( $profile['partner_id'] || ! isset( $profile['partner_id'] ) ) ) {
//            $profile = array(
//                'partner_id' => dt_network_site_id(),
//                'partner_name' => get_option( 'blogname' ),
//                'partner_description' => get_option( 'blogdescription' ),
//                'partner_url' => site_url()
//            );
//            update_option( 'dt_site_profile', $profile, true );
//        }
//
//        $profile['system'] = dt_network_site_system();
//
//        $profile['languages'] = dt_get_option( "dt_working_languages" );
//
//        return $profile;
//    }
//}
//
//
///**
// * Gets/Creates a Permanent ID for the Disciple Tools site. This allows for network duplicate checking etc.
// * @return string
// * @throws Exception
// */
//if ( ! function_exists( 'dt_network_site_id' ) ) {
//    function dt_network_site_id() {
//        $site_id = get_option( 'dt_site_id' );
//        if (empty( $site_id )) {
//            $site_id = hash( 'sha256', bin2hex( random_bytes( 40 ) ) );
//            add_option( 'dt_site_id', $site_id, '', 'yes' );
//        }
//        return $site_id;
//    }
//}
///**
// * @return array
// * @throws Exception
// */
//if ( ! function_exists( 'dt_network_site_system' ) ) {
//    function dt_network_site_system() : array {
//        global $wp_version, $wp_db_version;
//
//        $system = array(
//            'network_dashboard_version' => DT_Network_Dashboard::get_instance()->version ?? 0,
//            'network_dashboard_migration' => get_option( 'dt_network_dashboard_migration_number' ),
//            'network_dashboard_migration_lock' => get_option( 'dt_network_dashboard_migration_lock' ),
//            'dt_theme_version' => Disciple_Tools::instance()->version ?? 0,
//            'dt_theme_migration' => get_option( 'dt_migration_number' ),
//            'dt_theme_migration_lock' => get_option( 'dt_migration_lock' ),
//            'dt_mapping_migration' => get_option( 'dt_mapping_module_migration_number' ),
//            'dt_mapping_migration_lock' => get_option( 'dt_mapping_module_migration_lock' ),
//            'has_mapbox_key' => ( DT_Mapbox_API::get_key() ) ? 'yes' : 'no',
//            'php_version' => phpversion(),
//            'wp_version' => $wp_version,
//            'wp_db_version' => $wp_db_version,
//        );
//
//        return $system;
//    }
//}
//
