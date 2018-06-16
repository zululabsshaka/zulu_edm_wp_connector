<?php
/*
  Plugin Name: Zulu eDM Contact Form 7 Sync
  Plugin URI: http://www.zuluedm.com/integrations/#WPC7plugin
  Description: Send your Contact Form 7 data into your Zulu eDM Account
  Version: 1.0
  Author: Zulu eDM Tribe
  Author URI: http://www.zuluedm.com/developers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Declare some global constants
define('ZE_CONNECTOR_VERSION', '1.0');
define('ZE_CONNECTOR_DB_VERSION', '1.0');
define('ZE_CONNECTOR_ROOT', dirname(__FILE__));
define('ZE_CONNECTOR_URL', plugins_url('/', __FILE__));
define('ZE_CONNECTOR_BASE_FILE', basename(dirname(__FILE__)) . '/zulu-edm-connector.php');
define('ZE_CONNECTOR_PATH', plugin_dir_path(__FILE__)); //use for include files to other files
define('ZE_CONNECTOR_PRODUCT_NAME', 'Zulu eDM Connector');
define('ZE_CONNECTOR_CURRENT_THEME', get_stylesheet_directory());
//load_plugin_textdomain('zeconnector', false, basename(dirname(__FILE__)) . '/languages');

/*
 * include utility classes
 */
if (!class_exists('Ze_Connector_Utility')) {
    include( ZE_CONNECTOR_ROOT . '/includes/class-ze-utility.php' );
}
if (!class_exists('Ze_Connector_Service')) {
    include( ZE_CONNECTOR_ROOT . '/includes/class-ze-service.php' );
}

/*
 * Main ZE connector class
 * @class Ze_Connector_Init
 * @since 1.0
 */

class Ze_Connector_Init {

    /**
     *  Set things up.
     *  @since 1.0
     */
    public function __construct() {
        //run on activation of plugin
        //register_activation_hook(__FILE__, array($this, 'ze_connector_activate'));

        //run on deactivation of plugin
        //register_deactivation_hook(__FILE__, array($this, 'ze_connector_deactivate'));

        // validate is contact form 7 plugin exist
        add_action('admin_init', array($this, 'validate_parent_plugin_exists'));

        // register admin menu under "Contact" > "Integration"
        add_action('admin_menu', array($this, 'register_ze_menu_pages'));

        // load the js and css files
        add_action('init', array($this, 'load_css_and_js_files'));
    }


    /**
     * Validate parent Plugin Contact Form 7 exist and activated
     * @access public
     * @since 1.0
     */
    public function validate_parent_plugin_exists() {
        $plugin = plugin_basename(__FILE__);
        if ((!is_plugin_active('contact-form-7/wp-contact-form-7.php') ) && (!is_plugin_active('zulu-edm-connector/zulu-edm-connector') )) {
            add_action('admin_notices', array($this, 'contact_form_7_missing_notice'));
            deactivate_plugins($plugin);
            if (isset($_GET['activate'])) {
                // Do not sanitize it because we are destroying the variables from URL
                unset($_GET['activate']);
            }
        }
    }

    /**
     * If Contact Form 7 plugin is not installed or activated then throw the error
     *
     * @access public
     * @return mixed error_message, an array containing the error message
     *
     * @since 1.0 initial version
     */
    public function contact_form_7_missing_notice() {
        $plugin_error = Ze_Connector_Utility::instance()->admin_notice(array(
            'type' => 'error',
            'message' => 'Zulu eDM Connector Add-on requires Contact Form 7 plugin and LACRM plugin to be installed and activated.'
                ));
        echo $plugin_error;
    }

    /**
     * Create/Register menu items for the plugin.
     * @since 1.0
     */
    public function register_ze_menu_pages() {
        $current_role = Ze_Connector_Utility::instance()->get_current_user_role();
    }


    public function load_css_and_js_files() {
        add_action('admin_print_styles', array($this, 'add_css_files'));
        add_action('admin_print_scripts', array($this, 'add_js_files'));
    }

    /**
     * enqueue CSS files
     * @since 1.0
     */
    public function add_css_files() {
        if (is_admin() && ( isset($_GET['page']) && ( ( $_GET['page'] == 'wpcf7-new' ) || ( $_GET['page'] == 'wpcf7-zulu-edm-config' ) || ( $_GET['page'] == 'wpcf7' ) ) )) {
            wp_enqueue_style('ze-connector-css', ZE_CONNECTOR_URL . 'assets/css/ze-connector.css', ZE_CONNECTOR_VERSION, true);
        }
    }

    /**
     * enqueue JS files
     * @since 1.0
     */
    public function add_js_files() {
        if (is_admin() && ( isset($_GET['page']) && ( ( $_GET['page'] == 'wpcf7-new' ) || ( $_GET['page'] == 'wpcf7-zulu-edm-config' ) ) )) {
            wp_enqueue_script('ze-connector-js', ZE_CONNECTOR_URL . 'assets/js/ze-connector.js', ZE_CONNECTOR_VERSION, true);
            wp_enqueue_script('jquery-json', ZE_CONNECTOR_URL . 'assets/js/jquery.json.js', '', '2.3', true);
        }
    }

}

// Initialize the LACRM connector class
$init = new Ze_Connector_Init();
