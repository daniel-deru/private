<?php

/*

Plugin Name: WP Smart Commerce
Slug: wp-smart-commerce
Plugin URI: https://smartmetatec.com/wpsmartcommerce
Description: Save your precious time by letting your clients add and edit their Woocommerce products easily and hassle free without your help.
Version: 3.0.0
Author: Smart Meta Technologies
Author URI: https://smartmetatec.com

*/
// require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
// require_once "includes/update.php";

// This function creates a custom login page
function smt_smart_commerce_pro_add_login(){ 
    $title_of_page = "WP Smart Login";
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'WP Smart Login',
                    'post_title'        =>  $title_of_page,
                    'post_status'       =>  'publish',
                    'post_type'         =>  'page'

                )
            );
            update_post_meta($post_id, '_wp_page_template', 'Access');
        }  
}


// Create a products page to list all the products
function smt_smart_commerce_pro_add_products(){ 
    $title_of_page = "WP Smart Products";
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'WP Smart Products',
                    'post_title'        =>  $title_of_page,
                    'post_status'       =>  'publish',
                    'post_type'         =>  'page'

                )
            );
            update_post_meta($post_id, '_wp_page_template', 'Products');
        }  
}

// Create an add product page to add new products
function smt_smart_commerce_pro_add_addproduct(){ 
    $title_of_page = "WP Smart Add Product";
    wp_enqueue_media();
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'WP Smart Add Product',
                    'post_title'        =>  $title_of_page,
                    'post_status'       =>  'publish',
                    'post_type'         =>  'page'

                )
            );
            update_post_meta($post_id, '_wp_page_template', 'Addproduct');
    }  
}

// Create an edit product page to edit products
function smt_smart_commerce_pro_add_editproduct(){ 
    $title_of_page = "WP Smart Edit Product";
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'WP Smart Edit Product',
                    'post_title'        =>  $title_of_page,
                    'post_status'       =>  'publish',
                    'post_type'         =>  'page'

                )
            );
            update_post_meta($post_id, '_wp_page_template', 'EditProduct');
        }  
}

// Delete function for when the plugin is deleted to delete the login page
function smt_smart_commerce_pro_delete_login(){
    $page = get_page_by_title("WP Smart Login");

    wp_delete_post($page->ID, true);
};


// delete the products page when the plugin is deleted
function smt_smart_commerce_pro_delete_products(){
    $page = get_page_by_title("WP Smart Products");

    wp_delete_post($page->ID, true);
};

// delete the add product page when the plugin is deleted
function smt_smart_commerce_pro_delete_addproduct(){
    $page = get_page_by_title("WP Smart Add Product");

    wp_delete_post($page->ID, true);
};

// delete the edit product page when the plugin is deleted
function smt_smart_commerce_pro_delete_editproduct(){
    $page = get_page_by_title("WP Smart Edit Product");

    wp_delete_post($page->ID, true);
};



// This will create a custom template
function smt_smart_commerce_pro_login_page_template( $page_template )
{
    if ( is_page( 'WP Smart Login' ) ) {
        $page_template = plugin_dir_path( __FILE__ ) . 'templates/access.php';
    }
    return $page_template;
}

// This will create a custom template
function smt_smart_commerce_pro_products_page_template( $page_template )
{
    if ( is_page( 'WP Smart Products' ) ) {
        $page_template = plugin_dir_path( __FILE__ ) . 'templates/products.php';
    }
    return $page_template;
}

// create a custom page template for the addproduct page
function smt_smart_commerce_pro_addproduct_page_template( $page_template )
{
    if ( is_page( 'WP Smart Add Product' ) ) {
        wp_enqueue_media();
        $page_template = plugin_dir_path( __FILE__ ) . 'templates/addproduct.php';
    }
    return $page_template;
}

// create a custom page template for the editproduct page
function smt_smart_commerce_pro_editproduct_page_template( $page_template )
{
    if ( is_page( 'WP Smart Edit Product' ) ) {
        $page_template = plugin_dir_path( __FILE__ ) . 'templates/editproduct.php';
    }
    return $page_template;
}


// Add the custom user role to the wordpress users
function smt_smart_commerce_pro_add_custom_user_role(){
        add_role("product_manager", "Product Manager", array());
}

// delete the custom user from the wordpress users
function smt_smart_commerce_pro_delete_custom_user_role(){
    remove_role("product_manager");
}




// these hooks fire when the plugin is activated
register_activation_hook(__FILE__, 'smt_smart_commerce_pro_add_login');
register_activation_hook(__FILE__, 'smt_smart_commerce_pro_add_products');
register_activation_hook(__FILE__, 'smt_smart_commerce_pro_add_addproduct');
register_activation_hook(__FILE__, 'smt_smart_commerce_pro_add_editproduct');
register_activation_hook(__FILE__, 'smt_smart_commerce_pro_add_custom_user_role');

// These hooks fire when the plugin is deleted
register_deactivation_hook( __FILE__, 'smt_smart_commerce_pro_delete_login' );
register_deactivation_hook( __FILE__, 'smt_smart_commerce_pro_delete_products' );
register_deactivation_hook( __FILE__, 'smt_smart_commerce_pro_delete_addproduct' );
register_deactivation_hook( __FILE__, 'smt_smart_commerce_pro_delete_editproduct' );
register_deactivation_hook( __FILE__, 'smt_smart_commerce_pro_delete_custom_user_role' );

// these custom filters create the custom page templates and assigns them to the correct page
add_filter( 'page_template', 'smt_smart_commerce_pro_login_page_template' );
add_filter( 'page_template', 'smt_smart_commerce_pro_products_page_template' );
add_filter( 'page_template', 'smt_smart_commerce_pro_addproduct_page_template' );
add_filter( 'page_template', 'smt_smart_commerce_pro_editproduct_page_template' );

add_filter("show_admin_bar", "smt_smart_commerce_pro_hide_admin_bar");

function smt_smart_commerce_pro_hide_admin_bar(){
    if(is_page("wp-smart-add-product")){
        return false;
    }
}


add_action('admin_menu', 'smt_smart_commerce_pro_SetupPage');
add_action('admin_init', 'smt_smart_commerce_pro_RegisterSettings');
add_action("wp_enqueue_scripts", "wp_enqueue_media");


function smt_smart_commerce_pro_SetupPage() {
    add_menu_page(__("WPSmartCommerce "), __("Smart Commerce"), "manage_options", __FILE__, 'smt_smart_commerce_pro_PageContent', plugin_dir_url(__FILE__) . "assets/WPSC.svg");
}

function smt_smart_commerce_pro_RegisterSettings() {
    // Add options to database if they don't already exist
    add_option("wp_smart_products_consumer_key", "", "", "yes");
    add_option("wp_smart_products_consumer_secret", "", "", "yes");
    add_option("wp_smart_products_logo_url", "", "", "yes");
    add_option("wp_smart_products_brand_color", "", "", "yes");

}

function smt_smart_commerce_pro_PageContent() {
    if (!current_user_can('manage_options')) wp_die(__("You don't have access to this page"));
    require_once "adminPage.php";
}

add_action("wp_login", "smt_smart_commerce_pro_checkUser");
register_activation_hook(__FILE__, "smt_smart_commerce_pro_checkUser");
wp_schedule_event(time(), 'hourly', 'smt_smart_commerce_pro_checkUser');

function smt_smart_commerce_pro_checkUser(){

    $link = 'http://';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $link = 'https://';
    }

    $link = sanitize_url($link . str_replace("www.", "", $_SERVER['HTTP_HOST']));

    $data = array(
        'domain' => $link
    );

    $args = array(
        'body' => $data
    );
    $request = wp_remote_post("https://api.smartmetatec.com/api/verify/commerce/user", $args);

    $response = wp_remote_retrieve_body( $request );

    $jsonResponse = json_decode($response, true);

    if(!$jsonResponse['pass']){
        deactivate_plugins(plugin_basename(__FILE__), true);
    }

};


function smt_smart_commerce_pro_addLinks($links){
    $url = esc_url(
        add_query_arg(
            'page',
            'smart_commerce/smart_commerce.php',
            get_admin_url() . "admin.php"
        ));

    $settings_link = "<a href='" . esc_url($url) ."' target='_blank'>Settings</a>";

    array_unshift(
        $links,
        $settings_link
    );
    return $links;
}

add_filter('plugin_action_links_smart_commerce/smart_commerce.php', "smt_smart_commerce_pro_addLinks");

// This function will remove selected stylesheets
function smt_smart_commerce_remove_all_styles() {
    // global $wp_styles;
    if (    
            is_page( 'WP Smart Login' ) || 
            is_page( 'WP Smart Products' ) //|| 
            // is_page( 'WP Smart Add Product' ) || 
            // is_page( 'WP Smart Edit Product' )
        )
        {
            // wp_deregister_style('theme_stylesheet');
            // wp_dequeue_style('theme_stylesheet');
            // wp_deregister_style('auxin-base');
            // wp_dequeue_style('auxin-base');
            // wp_deregister_style('auxin-main');
            // wp_dequeue_style('auxin-main');
        }
}

// add_action( 'wp_print_styles', 'smt_smart_commerce_remove_all_styles', 100 );

// Enqueue the scripts and styles
function smt_smart_commerce_register_scripts(){

    wp_enqueue_style("smt_smart_commerce_fontawesome_css", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css");
    wp_enqueue_script("smt_smart_commerce_fontawesome_js", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js", [], false, true);

    if ( is_page( 'WP Smart Login' ) ) {
        wp_enqueue_style("smt_smart_commerce_access_style", plugins_url("/public/css/access.css", __FILE__));
        wp_enqueue_script("smt_smart_commerce_access", plugins_url("/public/js/access.js", __FILE__), array('jquery'), false, true);
    }
    else if( is_page('WP Smart Products')){
        wp_enqueue_style("smt_smart_commerce_product_style", plugins_url("/public/css/products.css", __FILE__));
        wp_enqueue_script("smt_smart_commerce_products", plugins_url("/public/js/products.js", __FILE__), array('jquery'), false, true);
    }
    else if(is_page('WP Smart Add Product')){
        wp_enqueue_style("smt_smart_commerce_add_product_style", plugins_url("/public/css/addproduct.css", __FILE__));
        wp_enqueue_script("smt_smart_commerce_add_product", plugins_url("/public/js/addproduct.js", __FILE__), array('jquery'), false, true);
    }
    else if(is_page('WP Smart Edit Product')){
        wp_enqueue_style("smt_smart_commerce_edit_product_style", plugins_url("/public/css/addproduct.css", __FILE__));
        wp_enqueue_script("smt_smart_commerce_edit_product", plugins_url("/public/js/editproduct.js", __FILE__), array('jquery'), false, true);
    }

}

add_action("wp_enqueue_scripts", "smt_smart_commerce_register_scripts", 20);

// Add the admin stylesheet
function smt_smart_commerce_admin_style(){
    global $pagenow;

    if(isset($_GET['page'])) $page = sanitize_text_field($_GET['page']);

    if($pagenow === "admin.php" && $page === "smart_commerce/smart_commerce.php"){
        wp_register_style("smt_smart_metatec_admin", plugins_url("/public/css/admin.css", __FILE__));
        wp_enqueue_style("smt_smart_metatec_admin");
    }
}
add_action('admin_enqueue_scripts', 'smt_smart_commerce_admin_style');


// Section for updating the plugin

class SmartMetaTecUpdateChecker{

    public $plugin_slug;
    public $version;
    public $cache_key;
    public $cache_allowed;

    public function __construct() {

        $this->plugin_slug = plugin_basename( __DIR__ );
        $this->version = '1.0';
        $this->cache_key = 'smart_metatec_custom_upd';
        $this->cache_allowed = false;

        add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
        add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
        add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );

    }

    public function request(){

        $remote = get_transient( $this->cache_key );

        if( false === $remote || ! $this->cache_allowed ) {

            try{
                $remote = wp_remote_get(
                    'https://update.smartmetatec.com/update.json',
                    array(
                        'timeout' => 10,
                        'headers' => array(
                            'Accept' => 'application/json'
                        )
                    )
                );
            } catch(Exception $e) {
                echo "Something went wrong with the update";
            }
           

            if(
                is_wp_error( $remote )
                || 200 !== wp_remote_retrieve_response_code( $remote )
                || empty( wp_remote_retrieve_body( $remote ) )
            ) { 
                return false;
            }

            

            set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );

        }
        $remote = json_decode( wp_remote_retrieve_body( $remote ) );

        return $remote;

    }


    function info( $res, $action, $args ) {

        // do nothing if you're not getting plugin information right now
        if( 'plugin_information' !== $action ) return $res;

        // do nothing if it is not our plugin
        if( $this->plugin_slug !== $args->slug ) return $res;

        // get updates
        $remote = $this->request();

        if( ! $remote ) return $res;

        $res = new stdClass();

        $res->name = $remote->name;
        $res->slug = $remote->slug;
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->author = $remote->author;
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->requires_php = $remote->requires_php;
        $res->last_updated = $remote->last_updated;

        $res->sections = array(
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
        );

        return $res;

    }

    public function update( $transient ) {

        if ( empty($transient->checked ) ) return $transient;

        $remote = $this->request();

        if(
            $remote
            && version_compare( $this->version, $remote->version, '<' )
            && version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' )
            && version_compare( $remote->requires_php, PHP_VERSION, '<' )
        ) {
            $res = new stdClass();
            $res->slug = $this->plugin_slug;
            $res->plugin = plugin_basename( __FILE__ ); // misha-update-plugin/misha-update-plugin.php
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;

            $transient->response[ $res->plugin ] = $res;

    }

        return $transient;

    }

    public function purge( $upgrader, $options ){

        if (
            $this->cache_allowed
            && 'update' === $options['action']
            && 'plugin' === $options[ 'type' ]
        ) {
            // just clean the cache when new plugin version is installed
            delete_transient( $this->cache_key );
        }

    }


}
new SmartMetaTecUpdateChecker();