<?php
/**
 * tracknotify
 *
 * @package tracknotify
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class TRACKNOTIFY_Actions {

  public function __construct() {
    add_action( 'init', array( $this, 'tracknotify_admin_notification' ) );
    add_action( 'init', array( $this, 'tracknotify_customer_notification' ) );
  }


  /**
   * Trigger action based on admin notification settings
   *
   * @since 1.0
   * @return mixed
   */
  public function tracknotify_admin_notification() {
    //admin status
    $admin_notification_statuses = get_option( 'tracknotify_admin_notification_status', true );

    if ( !empty( $admin_notification_statuses ) && is_array( $admin_notification_statuses ) ) {

      foreach( $admin_notification_statuses as $admin_notification_status ) {

        $admin_notification_status = str_replace( 'wc-', '' , $admin_notification_status );

        if ( !empty( $admin_notification_status ) ) {
          add_action( 'woocommerce_order_status_'.$admin_notification_status, array( $this, 'tracknotify_send_admin_notification' ) );
        }

      }
    }
  }


  /**
   * Trigger action based on customer notification settings
   *
   * @since 1.0
   * @return mixed
   */
  public function tracknotify_customer_notification() {
    //Customer status
    $customer_notification_statuses = get_option( 'tracknotify_customer_notification_status', true );

    if ( !empty( $customer_notification_statuses ) && is_array( $customer_notification_statuses ) ) {

      foreach( $customer_notification_statuses as $customer_notification_status ) {

        $customer_notification_status = str_replace( 'wc-', '' , $customer_notification_status );

        if ( !empty( $customer_notification_status ) ) {
          add_action( 'woocommerce_order_status_'.$customer_notification_status, array( $this, 'tracknotify_send_customer_notification' ) );
        }

      }
    }
  }


  /**
   * Send admin notification
   *
   * @since 1.0
   * @param order_id
   * @return mixed
   */
  public function tracknotify_send_admin_notification( $order_id ) {

    if ( !empty( $order_id ) ) {
      $admin_notification_text = get_option( 'tracknotify_admin_notification_text' , true );

      if ( !empty( $admin_notification_text ) ) {

        $message = $this->render_notification_text( $order_id, $admin_notification_text );

        if ( !empty( $message ) ) {
          $selected_service   = get_option( 'tracknotify_service', true );
          $phone_number       = get_option( 'tracknotify_admin_phone', true );
          $tracknotify_notification   = new Order_Status_Notification_Service();
          $response           = $tracknotify_notification->send_notification( $selected_service, $phone_number, $message,'ADMIN' );

        }

      }
    }

  }


  /**
   * Send notification to customers
   *
   * @since 1.0
   * @param order_id
   * @return mixed
   */
  public function tracknotify_send_customer_notification( $order_id ) {

    if ( !empty( $order_id ) ) {
      $customer_notification_text = get_option( 'tracknotify_customer_notification_text' , true );

      if ( !empty( $customer_notification_text ) ) {
        $message = $this->render_notification_text( $order_id, $customer_notification_text );

        if ( !empty( $message ) ) {
          $selected_service = get_option( 'tracknotify_service', true );
          $phone_number = tracknotify_get_customer_phone( $order_id );

          if ( !empty( $phone_number ) ) {
            $tracknotify_notification = new Order_Status_Notification_Service();
            $response = $tracknotify_notification->send_notification( $selected_service, $phone_number, $message,"CUSTOMER" );
          }
        }
      }
    }

  }


  /**
   * Render text based on the admin settings
   *
   * @since 1.0
   * @param order_id
   * @param notification_text
   * @return mixed
   */
  public function render_notification_text( $order_id, $notification_text ) {
    $order          = wc_get_order( $order_id );
    $billing_phone  = $order->get_billing_phone();
    $full_name      = $order->get_formatted_billing_full_name();
    $total          = number_format($order->get_total(),2);
    $status         = $order->get_status();
    $store_name     = get_bloginfo('name');
    $store_url      = get_bloginfo('url');
    $billing_fname  = $full_name;

    $search_params  = array( '{ORDER_NUMBER}', '{ORDER_STATUS}', '{STORE_NAME}', '{STORE_URL}', '{BILLING_FNAME}', '{FULLNAME}', '{PHONE}', '{PRICE}' );
    $replace_params = array( $order_id, $status, $store_name, $store_url, $billing_fname, $full_name, $billing_phone, $total );

    $notification_message = str_replace( $search_params, $replace_params, $notification_text );

    return $notification_message;
  }

}
return new TRACKNOTIFY_Actions();
