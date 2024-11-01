<?php
/**
 * Load assets
 *
 * @package Order Status Notification/Admin
 * @version 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'TRACKNOTIFY_Admin_Assets', false ) ) :

  /**
   * tracknotify_Admin_Assets Class.
   */
  class TRACKNOTIFY_Admin_Assets {

    /**
     * Hook in tabs.
     */
    public function __construct() {
      add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    /**
     * Enqueue styles.
     */
    public function admin_styles() {

      $version      = TRACKNOTIFY()->version;

      // Register admin styles.
      wp_register_style( 'tracknotify_toast', tracknotify()->plugin_url() . '/assets/css/jquery.toast.css', array(), $version );
      wp_register_style( 'tracknotify_admin', tracknotify()->plugin_url() . '/assets/css/tracknotify-admin.css', array(), $version );

      if ( isset( $_GET['page'] )
        && $_GET['page'] == 'wc-settings'
        && isset( $_GET['tab'] )
        && $_GET['tab'] == 'tracknofity_status_notification'
      ) {
        wp_enqueue_style( 'tracknotify_toast' );
        wp_enqueue_style( 'tracknotify_admin' );
      }

    }


    /**
     * Enqueue scripts.
     */
    public function admin_scripts() {

      $version      = TRACKNOTIFY()->version;

      // Register scripts.
      wp_register_script( 'tracknotify_toast', TRACKNOTIFY()->plugin_url() . '/assets/js/jquery.toast.js', array( 'jquery' ), $version );
      wp_register_script( 'tracknotify_admin', TRACKNOTIFY()->plugin_url() . '/assets/js/tracknotify-admin.js', array( 'jquery', 'tracknotify_toast' ), $version );

      wp_localize_script(
        'tracknotify_admin',
        'tracknotify_admin_params',
        array(
          'ajax_url'                  => admin_url( 'admin-ajax.php' ),
          'test_notification_nonce'   => wp_create_nonce( 'test-notification' ),
          'error_msg_heading'                 => __( 'Error', 'tracknofity-status-notification' ),
          'success_msg_heading'               => __( 'Success', 'tracknofity-status-notification' ),
          'success_msg'                       => __( 'Message sent successfully', 'tracknofity-status-notification' ),

        )
      );

      if ( isset( $_GET['page'] )
        && $_GET['page'] == 'wc-settings'
        && isset( $_GET['tab'] )
        && $_GET['tab'] == 'tracknofity_status_notification'
      ) {
        wp_enqueue_script( 'tracknotify_admin' );
        wp_enqueue_script( 'tracknotify_toast' );
      }

    }

  }

endif;

return new TRACKNOTIFY_Admin_Assets();
