<?php

/**
 * Service class for Zulu eDM Connector
 * @since 1.0
 */
if ( !defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/**
 * Ze_Connector_Service Class
 *
 * @since 1.0
 */
class Ze_Connector_Service {
    /**
    *  Set things up.
    *  @since 1.0
    */
   public function __construct() {
      // Add new tab to contact form 7 editors panel
      add_filter( 'wpcf7_editor_panels', array( $this, 'cf7_ze_editor_panels' ) );

      add_action('wpcf7_after_save', array( $this, 'save_ze_settings' ) );
      add_action('wpcf7_before_send_mail', array( $this, 'cf7_save_to_zuluedm' ) );
   }


   /**
    * Add new tab to contact form 7 editors panel
    * @since 1.0
    */
   public function cf7_ze_editor_panels( $panels ) {
      $panels[ 'zulu_edm' ] = array(
          'title' => __( 'Zulu eDM', 'contact-form-7' ),
          'callback' => array( $this, 'cf7_editor_panel_zulu_edm' )
      );

      return $panels;
   }

   /*
    * Set Zulu eDM settings with contact form
    * @since 1.0
    */
   public function save_ze_settings( $post ) {
     update_post_meta( $post->id(), 'ze_settings', $_POST['cf7-ze'] );
   }

	public function cf7_save_to_zuluedm($form) {

		$form_id = $form->id();
		$form_data = get_post_meta($form_id, 'ze_settings');

		$zuluedm_api_key = $form_data[0]['zuluedm-api-key'];
		$zuluedm_api_username = $form_data[0]['zuluedm-api-username'];
		$zuluedm_api_password = $form_data[0]['zuluedm-api-password'];
		$zuluedm_field_mapping = $form_data[0]['zuluedm-field-mapping'];

		$zuluedm_api_url = "https://api.au.zuluedm.com/api/1.1/?wsdl";

		/* Create a SOAP Client Object */
		$client = new SoapClient($zuluedm_api_url, array('trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE));

		$fields = preg_split("/[\s]+/", $zuluedm_field_mapping);

		$data = array();
		foreach($fields as $field){
			list($edm_field, $cf7_field) = explode(':', $field);
			$data[] = array('field' => $edm_field, 'value' => $_POST[$cf7_field]);
		}

		try {
			/* Add the Contact to the System */
			$client->__soapCall('Contacts.AddContact', array('apikey' => $zuluedm_api_key,'user' => $zuluedm_api_username,'pass' => $zuluedm_api_password,'data' => $data));
		}
		catch (Exception $e) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			Ze_Connector_Utility::ze_debug_log($data);
		}
	}

   /*
    * Zulu eDM settings page
    * @since 1.0
    */
   public function cf7_editor_panel_zulu_edm( $post ) {
         $form_id = sanitize_text_field( $_GET['post'] );
         $form_data = get_post_meta( $form_id, 'ze_settings' );
      ?>
		<form method="post">
         <div class="ze-fields">
            <h2><span><?php echo esc_html( __( 'Zulu eDM Settings', 'zeconnector' ) ); ?></span></h2>
			<p>
               <label><?php echo esc_html( __( 'Zulu eDM API Key', 'zeconnector' ) ); ?></label>
               <input type="text" name="cf7-ze[zuluedm-api-key]" id="zuluedm-api-key"
                      value="<?php echo ( isset ( $form_data[0]['zuluedm-api-key'] ) ) ? esc_attr( $form_data[0]['zuluedm-api-key'] ) : ''; ?>"/>
            </p>
			<p>
               <label><?php echo esc_html( __( 'Zulu eDM API Username', 'zeconnector' ) ); ?></label>
               <input type="text" name="cf7-ze[zuluedm-api-username]" id="zuluedm-api-username"
                      value="<?php echo ( isset ( $form_data[0]['zuluedm-api-username'] ) ) ? esc_attr( $form_data[0]['zuluedm-api-username'] ) : ''; ?>"/>
            </p>
			<p>
               <label><?php echo esc_html( __( 'Zulu eDM API Password', 'zeconnector' ) ); ?></label>
               <input type="password" name="cf7-ze[zuluedm-api-password]" id="zuluedm-api-password"
                      value="<?php echo ( isset ( $form_data[0]['zuluedm-api-password'] ) ) ? esc_attr( $form_data[0]['zuluedm-api-password'] ) : ''; ?>"/>
            </p>
			<p>
               <label><?php echo esc_html( __( 'Field Mapping', 'zeconnector' ) ); ?></label>
               <textarea name="cf7-ze[zuluedm-field-mapping]" id="zuluedm-field-mapping"><?php echo ( isset ( $form_data[0]['zuluedm-field-mapping'] ) ) ? esc_attr( $form_data[0]['zuluedm-field-mapping'] ) : ''; ?></textarea>
               <span style="display:inline-block;">Eg.,<br>firstname:your-name<br>email:your-email</span>
            </p>
         </div>
		<p>
		   <b>Zulu eDM Fields:</b> title, firstname, lastname, email, telephone, workphone, mobilephone, fax, username, password, address1, address2, city, state, postcode, country, companyname, subscribed, signupdate, doubleoptedin
		</p>
      </form>
   <?php }

}

$ze_connector_service = new Ze_Connector_Service();


