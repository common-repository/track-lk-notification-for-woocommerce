<?php
/**
* Plugin Name: Track.LK Notification For WooCommerce
* Description: This extension simply sends order notification to the customer and admin via Track.LK SMS Service Sri Lanka
* Plugin URI: http://track.lk/wc
* Version: 1.4
* Author: InviuLabs
* Author URI: http://portal.track.lk
* Text Domain: tracklk-notify
*
* WC requires at least: 3.0
* WC tested up to: 4.8
*
* @package tracklk-notify
*/

defined( 'ABSPATH' ) || exit;

// Define TRACKNOTIFY_PLUGIN_FILE.
if ( ! defined( 'TRACKNOTIFY_PLUGIN_FILE' ) ) {
  define( 'TRACKNOTIFY_PLUGIN_FILE', __FILE__ );
}

// include dependencies file
if ( ! class_exists( 'TRACKNOTIFY_Dependencies' ) ){
  include_once dirname( __FILE__) . '/includes/class-order-status-notification-dependencies.php';
}

// Include the main OrderStatusNotification class.
if ( ! class_exists( 'Order_Status_Notification', false ) ) {
  include_once dirname( TRACKNOTIFY_PLUGIN_FILE ) . '/includes/class-order-status-notification.php';
}

/**
 * Returns the main instance of tracknotify.
 *
 * @since  1.0
 * @return OrderStatusNotification
 */
function TRACKNOTIFY() {
  return Order_Status_Notification::instance();
}

// Global for backwards compatibility.
$GLOBALS['tracknotify-status-notification'] = TRACKNOTIFY();
