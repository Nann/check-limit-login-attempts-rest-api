<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.facebook.com/nannchalermchai
 * @since             1.0.0
 * @package           Check_Limit_Login_Attempts
 *
 * @wordpress-plugin
 * Plugin Name:       Check Limit Login Attempts
 * Plugin URI:        https://nann.me/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Nann
 * Author URI:        https://www.facebook.com/nannchalermchai
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       check-limit-login-attempts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CHECK_LIMIT_LOGIN_ATTEMPTS_VERSION', '1.0.0' );

add_action( 'admin_init', 'check_if_limit_login_installed' );
function check_if_limit_login_installed() {

    // If Limit Login Attempts Reloaded is NOT installed, Deactivate the plugin
    if ( is_admin() && current_user_can( 'activate_plugins') && !is_plugin_active( 'limit-login-attempts-reloaded/limit-login-attempts-reloaded.php') ) {

        // Show dismissible error notice
        add_action( 'admin_notices', 'limit_login_check_notice' );

        // Deactivate this plugin
        deactivate_plugins( plugin_basename( __FILE__) );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

add_action('rest_api_init', function () {
    register_rest_route( 'nannme/v1', 'limit-login-retries-stats/',array(
        'methods'  => 'GET',
        'callback' => 'get_api_limit_login'
    ));
    register_rest_route( 'nannme/v1', 'limit-login-logged/',array(
        'methods'  => 'GET',
        'callback' => 'get_api_limit_login_logged'
    ));
});

function get_api_limit_login($request) {

    global $wpdb;

    $option_name = 'limit_login_retries_stats';
    $sql_limit_login_retries_stats = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT option_value
             FROM $wpdb->options
             WHERE option_name = %s",
            $option_name
        )
    );

    $retries_stats = unserialize($sql_limit_login_retries_stats);
    $datei18n = date_i18n( 'Y-m-d' );
    $rest_api_retries_stats = [];
    $limit_login_arr = [];

    if( $retries_stats ) {
        foreach($retries_stats as $limit_login_date => $limit_login_count) {
            $limit_login_arr = array(
                'date' => $limit_login_date,
                'retries_count' => $limit_login_count
            );
            array_push($rest_api_retries_stats, $limit_login_arr);
        }
    } else {
        $rest_api_retries_stats = array(
            'date' => $datei18n,
            'retries_count' => 0
        );
    }

    $response = new WP_REST_Response($rest_api_retries_stats);
    $response->set_status(200);

    return $response;
}

function get_api_limit_login_logged($request) {

    global $wpdb;

    $option_name = 'limit_login_logged';
    $sql_limit_login_logged = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT option_value
             FROM $wpdb->options
             WHERE option_name = %s",
            $option_name
        )
    );

    $logged = unserialize($sql_limit_login_logged);
    $blocklist_ip = [];
    $block_ip_arr = [];

    if( is_array( $logged ) && ! empty( $logged ) ) {
        foreach ($logged as $ip => $user_info ) {
            $block_ip_arr = array( 'blocklist_ip' => $ip );
            array_push($blocklist_ip, $block_ip_arr);
        }
    } else {
        $blocklist_ip = array( 'blocklist_ip' => 'no ip' );
        array_push($blocklist_ip, $block_ip_arr);
    }


    $response = new WP_REST_Response($blocklist_ip);
    $response->set_status(200);

    return $response;
}

// Show dismissible error notice if Limit Login Attempts Reloaded is not present
function limit_login_check_notice() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p>Sorry, but this plugin requires Limit Login Attempts Reloaded.
            So please ensure that Limit Login Attempts Reloaded is both installed and activated.
        </p>
    </div>
    <?php
}