<?php
/**
 * Functions used in the Zúme implementation
 *
 * @since 0.1
 */

/* Require Authentication for Zúme */
function zume_force_login() {
    // if user is not logged in redirect to login
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( zume_login_url() );
        exit;
    }
}

// Remove admin bar on the front end.
if ( ! current_user_can( 'administrator' ) ) {
    add_filter( 'show_admin_bar', '__return_false' );
}


/**
 * Remove menu items for coaches in the admin dashboard.
 */
function zume_custom_menu_page_removing() {

    if (is_admin() && current_user_can( 'coach' ) && !current_user_can( 'administrator' ) ) {

        remove_menu_page( 'index.php' );                  //Dashboard
        remove_menu_page( 'jetpack' );                    //Jetpack*
        remove_menu_page( 'edit.php' );                   //Posts
        remove_menu_page( 'upload.php' );                 //Media
        remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit.php?post_type=steplog' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments
        remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page( 'plugins.php' );                //Plugins
    //    remove_menu_page( 'users.php' );                  //Users
        remove_menu_page( 'tools.php' );                  //Tools
        remove_menu_page( 'options-general.php' );        //Settings

    }
}
add_action( 'admin_menu', 'zume_custom_menu_page_removing' );



function zume_home_url( $current_language = null ) {
    return site_url();
}

// changing the logo link from wordpress.org to your site
function zume_login_url() {  return site_url() . '/login'; }
add_filter( 'login_headerurl', 'zume_login_url' );

function zume_lostpassword_url( $current_language = null ) {
    return site_url() . '/login/?action=lostpassword';
}

function zume_register_url( $current_language = null ) {
    return $url = site_url() . '/login/?action=register';
}

function zume_profile_url() {
    return site_url( '/account' );
}

/**
 * Returns the full URI of the images folder with the ending slash, either as images/ or as images/sub_folder/.
 *
 * @param string $sub_folder
 * @return string
 */
function zume_images_uri( $sub_folder = '' ) {
    $zume_images_uri = site_url( '/wp-content/themes/zume-community/assets/images/' );
    if ( empty( $sub_folder ) ) {
        return $zume_images_uri;
    } else {
        return $zume_images_uri . $sub_folder . '/';
    }
}

function zume_files_uri() {
    return 'https://storage.googleapis.com/zume-file-mirror/' . zume_current_language() . '/';
}

function zume_files_download_uri( $id ) {
    // post id of downloads / meta field
    return zume_files_uri() . '/';
}

function zume_home_id() {
    return site_url();
}
