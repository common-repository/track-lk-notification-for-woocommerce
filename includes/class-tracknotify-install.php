<?php
/**
 * Installation related functions and actions.
 *
 * @package tracknotify/Classes
 * @version 1.0
 */


defined( 'ABSPATH' ) || exit;

/**
 * tracknotify Class.
 */
class TRACKNOTIFY_Install {

  /**
   * Hook in tabs.
   */
  public static function init() {

//    add_filter('plugin_action_links_'.plugin_basename(__FILE__) ,    array( __CLASS__, 'track_plugin_action_links' ) );

    add_filter('plugin_action_links_tracklk-notify/tracknofity-notification.php',   array( __CLASS__, 'track_plugin_action_links' ) );
    add_filter( 'woocommerce_get_settings_pages', array( __CLASS__, 'tracknotify_settings_class' ) );
  }



  /**
   * Show action links on the plugin screen.
   *
   * @param mixed $links Plugin Action links.
   *
   * @return array
   */
  public  function track_plugin_action_links( $links ) {
    $action_links = array(
          'Settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=tracknofity_status_notification' ) . '" aria-label="' . esc_attr__( 'View Track.LK Notification settings', 'tracknofity-status-notification' ) . '">' . esc_html__( 'Settings', 'tracknofity-status-notification' ) . '</a>',
          'Platform' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=tracknofity_status_notification&section=tracknotifysms' ) . '" aria-label="' . esc_attr__( 'View Track.LK Notification settings', 'tracknofity-status-notification' ) . '">' . esc_html__( 'Platform', 'tracknofity-status-notification' ) . '</a>'
   );
    return array_merge( $action_links, $links );
  }


  /**
   * Add settings class.
   *
   * @param array $settings Plugin settings.
   *
   * @return array
   */
  public static function tracknotify_settings_class( $settings ) {
    $settings[] = include 'admin/class-wc-tracknotify-notification.php';
    return $settings;
  }

}

TRACKNOTIFY_Install::init();
