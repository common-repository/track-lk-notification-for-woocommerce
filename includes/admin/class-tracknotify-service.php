<?php
/**
 * Order_Status_Notification Service
 *
 * @package Order_Status_Notification
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;


class Order_Status_Notification_Service {

  protected $data;

  protected $options;



  protected $admin_number;

  protected $tracknotify_app_key;

  protected $tracknotify_app_secret;

  public function __construct() {

    $this->tracknotify_app_key           = get_option( 'tracknotify_app_key', true );

    $this->tracknotify_app_secret         = get_option( 'tracknotify_app_secret', true );

  }

  /**
   * Send Notification
   *
   * @param Service Type, Phone Number, Message,Msg Mode
   * @return array
   * @since 1.0
   */
  public function send_notification( $service_type, $phone_number, $message,$mode ) {

    $response = array();

    if ( $service_type == 'tracklksms' ) {

      $url = "https://api.track.lk/plugin/wc-notify/";


      $tracknotify_app_key = get_option( 'tracknotify_app_key', true );
      $tracknotify_app_secret = get_option( 'tracknotify_app_secret', true );
      $v =   defined( 'TRACKNOTIFY_VERSION' ) ."|". $GLOBALS['wp_version'];

      $data = json_encode(
                array(
                  'body'    => $message,
                  'method'    => "WC-".$mode,
                  'ref'    => get_bloginfo('url'),
                  'to'      => $phone_number,
                  'appKey'      => $tracknotify_app_key,
                  'appSecret'      => $tracknotify_app_secret,
                  'versions'      => $v
                )
              );

      $auth = base64_encode( $this->tracknotify_app_secret );

      $args = array(
        'headers' => array(
            'Content-Type' => "application/json",
            'AppKey' => $auth
        ),
      'body'    => $data,
      );
        //echo $data;
      $response  = wp_remote_post( $url, $args );

      if ( !empty( $response ) ) {

        if ( isset( $response['body'] ) ) {

          $response_data = json_decode( $response['body'] );


          if ( !in_array( $response_data->code, array( '2000') ) ) {
            $this->data['errors'][] = __( 'Your message was failed to be sent.', 'tracknofity-status-notification' );
            $this->data['errors'][] = sprintf( __( 'Error: %1$s - %2$s', 'tracknofity-status-notification' ), $response_data->message, $response_data->code );
          }
          else {
            $this->data['success'][] = __( 'Your message was sent successfully.', 'tracknofity-status-notification' );
          }
        }
      }
      else {
        $this->data['errors'][] = __( 'Your message could not be sent. Please try again later.', 'tracknofity-status-notification' ) ;
      }
      return $this->data;
    }

    else {
        $this->data['errors'][] = __( 'Your message could not be sent. Please try again later.', 'tracknofity-status-notification' ) ;

    }

  }

}
