<?php
/**
 * Order Status Notification Settings
 *
 * @author    dorishk
 * @category  Admin
 * @version   1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (  class_exists( 'WC_Settings_Page' ) ) :

/**
 * WC_Settings_Accounts
 */
class WC_Settings_TrackNotify_Notification extends WC_Settings_Page {

  /**
  * Constructor.
  */
  public function __construct() {
    $this->id    = 'tracknofity_status_notification';
    $this->label = __( 'Track.LK Notification', 'tracknofity-status-notification' );

    add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
    add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
    add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
    add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    add_action( 'current_screen', array( $this, 'tracknotify_add_tabs' ), 99 );

    add_action( 'woocommerce_admin_field_button' , array( $this, 'tracknotify_admin_field_button' ), 10 );

  }


  /**
   * Get sections.
   *
   * @return array
   */
  public function get_sections() {

    $sections = array(
      ''           => __( 'General', 'food-store' ),
      'tracknotifysms'     => __( 'Platform Configuration',  'food-store' )
    );
    return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
  }

  /**
  * Output sections.
  */
  public function output_sections() {
    global $current_section;

    $sections = $this->get_sections();

    if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
      return;
    }

       _e('<ul class="subsubsub">');

    $array_keys = array_keys( $sections );

    foreach ( $sections as $id => $label ) {
         _e('<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>');
      }

       _e('</ul><br class="clear" />');
  }

  public function tracknotify_admin_field_button( $value ){
    $option_value = (array) WC_Admin_Settings::get_option( $value['id'] );
    $description = WC_Admin_Settings::get_field_description( $value );

  ?>

  <tr valign="top">
    <th scope="row" class="titledesc"></th>

    <td class="forminp forminp-<?php echo esc_html(sanitize_title( $value['type'] )) ?>">
      <button class="button-primary tracknotify-send-test-notification">
        <?php echo __( 'Send test notification',  'tracknofity-status-notification' ); ?>
      </button>
    </td>
  </tr>

  <?php
}


  /**
   * Get settings array
   *
   * @return array
   */
  public function get_settings( $current_section = '' ) {

    $current_section = isset( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : '';

    if ( '' === $current_section ) {

      $settings = apply_filters(
        'woocommerce_settings_tracknotify_general',

        array(

          array(
            'title'   => __( 'Notification Settings', 'tracknofity-status-notification' ),
            'type'    => 'title',
            'desc'    => '',
            'id'      => 'tracknotify_notification_title'
          ),

          array(
            'title'   => __( 'Enable', 'tracknofity-status-notification' ),
            'desc'    => __( 'Enable Track.LK Order Status Notification.', 'tracknofity-status-notification' ),
            'type'    => 'checkbox',
            'id'      => 'tracknotify_enabled',
            'default' => 'no'
          ),

          array(
            'title'   => __( 'Select notification service', 'tracknofity-status-notification' ),
            'desc'    => __( 'Select service for notification.', 'tracknofity-status-notification' ),
            'type'    => 'radio',
            'options' => array( 'tracklksms' => 'Track.LK SMS' ),
            'id'      => 'tracknotify_service',
            'class'   => 'tracknotify_service',
            'autoload' => false,
            'desc_tip' => true,
          ),

          array(
            'title'   => __( 'Admin phone number', 'tracknofity-status-notification' ),
            'desc'    => __( 'Enter admin phone number with country code (Sri Lanka Only). eg: +94777123456, comma separated for multiple numbers ', 'tracknofity-status-notification' ),
            'type'    => 'text',
            'id'      => 'tracknotify_admin_phone',
            'autoload'        => false,
            'desc_tip'        => true,
          ),

          array(
            'title'    => __( 'Send notification to admin on order status', 'tracknofity-status-notification' ),
            'desc'     => __( 'Select order status for which admin would get notification', 'tracknofity-status-notification' ),
            'id'       => 'tracknotify_admin_notification_status',
            'type'     => 'multiselect',
            'class'    => 'wc-enhanced-select',
            'css'      => 'min-width: 350px;',
            'options'  => wc_get_order_statuses(),
            'default'  => '',
            'autoload' => false,
            'desc_tip' => true,
          ),

          array(
            'title'   => __( 'Admin SMS Text', 'tracknofity-status-notification' ),
            'desc'    => __( 'Enter the text that would be send to the admin when a new order would be placed. Available placeholders {ORDER_NUMBER}, {ORDER_STATUS}, {STORE_NAME}, {BILLING_FNAME}, {FULLNAME}, {PHONE}, {PRICE}', 'tracknofity-status-notification' ),
            'type'    => 'textarea',
            'css'     => 'min-width: 50%; height: 100px;',
            'id'      => 'tracknotify_admin_notification_text',
            'autoload'        => false,
            'desc_tip'        => true,
          ),

          array(
            'title'    => __( 'Send notification to customers on order status', 'tracknofity-status-notification' ),
            'desc'     => __( 'Select order status for which customer would get notification', 'tracknofity-status-notification' ),
            'id'       => 'tracknotify_customer_notification_status',
            'type'     => 'multiselect',
            'class'    => 'wc-enhanced-select',
            'css'      => 'min-width: 350px;',
            'options'  => wc_get_order_statuses(),
            'default'  => '',
            'autoload' => false,
            'desc_tip' => true,
          ),

          array(
            'title'   => __( 'Customer SMS Text', 'tracknofity-status-notification' ),
            'desc'    => __( 'Enter the text that would be send to the customer when they make a new order. Available placeholders {ORDER_NUMBER}, {ORDER_STATUS}, {STORE_NAME}, {BILLING_FNAME}, {FULLNAME}, {PHONE}, {PRICE}', 'tracknofity-status-notification' ),
            'type'    => 'textarea',
            'css'     => 'min-width: 50%; height: 100px;',
            'id'      => 'tracknotify_customer_notification_text',
            'autoload'        => false,
            'desc_tip'        => true,
          ),

          array(
            'type' => 'sectionend',
            'id' => 'order_status_notification_options'
          ),

          array(
            'title' => __( 'Test Notification', 'tracknofity-status-notification' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'general_options',
          ),

          array(
            'title'   => __( 'Test Phone Number', 'tracknofity-status-notification' ),
            'desc'    => __( 'Enter phone number where you want to send the test message. eg: +94777123456 ', 'tracknofity-status-notification' ),
            'type'    => 'text',
            'id'      => 'tracknotify_test_phone',
            'autoload'  => false,
            'desc_tip'  => true,
          ),

          array(
            'title'   => __( 'Test Message', 'tracknofity-status-notification' ),
            'desc'    => __( 'Enter the test message which would be sent to the number', 'tracknofity-status-notification' ),
            'type'    => 'textarea',
            'css'     => 'min-width: 50%; height: 100px;',
            'id'      => 'tracknotify_test_message',
            'autoload'        => false,
            'desc_tip'        => true,
          ),

          array(
            'name'    => __( 'Send test message', 'tracknofity-status-notification' ),
            'type'    => 'button',
            'title'   => __( 'Send Test Notification', 'tracknofity-status-notification' ),
            'id'      => 'send_test_message',
          ),

          array(
            'type' => 'sectionend',
            'id' => 'order_status_test_notification'
          ),

        )
      );
    }
    elseif( 'tracknotifysms' === $current_section  ) {
      $settings = apply_filters(
        'woocommerce_settings_tracknotify_general',

        array(

          array(
            'title'   => __( 'Platform Settings [SMS]', 'tracknofity-status-notification' ),
            'type'    => 'title',
            'desc'    => '',
            'id'      => 'tracknotify_sms_title'
          ),

          array(
            'title'   => __( 'App Key', 'tracknofity-status-notification' ),
            'desc'    => __( 'To view API credentials visit <a href="https://portal.track.lk">https://portal.track.lk</a> ', 'tracknofity-status-notification' ),
            'type'    => 'text',
            'id'      => 'tracknotify_app_key',
          ),

          array(
            'title'   => __( 'App Secret', 'tracknofity-status-notification' ),
            'desc'    => __( 'To view API credentials visit <a href="https://portal.track.lk">https://portal.track.lk</a> ', 'tracknofity-status-notification' ),
            'type'    => 'text',
            'id'      => 'tracknotify_app_secret',
          ),



        array(
          'type' => 'sectionend',
          'id' => 'order_status_notification_options'
        ),

        )
      );
    }

    return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
  }

  public function tracknotify_add_tabs() {
    $screen = get_current_screen();

    if ( isset( $_GET['page'] )
      && isset( $_GET['tab'] )
      && $_GET['page'] == 'wc-settings'
      && $_GET['tab']
      && $_GET['tab'] == 'tracknofity_status_notification' ) {

      $screen->add_help_tab(
        array(
          'id'      => 'tracknotify_support_tab',
          'title'   => __( 'Track.LK Notification Support', 'woocommerce' ),
          'content' =>
            '<h2>' . __( 'Track.LK Notification Support', 'woocommerce' ) . '</h2>' .
            '<p>' . sprintf(
              __( '{ORDER_NUMBER}, {ORDER_STATUS}, {STORE_NAME}, {BILLING_FNAME}, {FULLNAME}, {PHONE}, {PRICE}, using, or extending WooCommerce, <a href="%s">please read our documentation</a>. You will find all kinds of resources including snippets, tutorials and much more.', 'woocommerce' ),
              'https://track.lk/#wc'
            ) . '</p>',
        )
      );

    }

  }


}
return new WC_Settings_TrackNotify_Notification();

endif;
