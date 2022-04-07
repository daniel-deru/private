<?php

/*

Plugin Name: WP Smart Commerce
Plugin URI: https://smartmetatec.com/wpsmartcommerce
Description: Save your precious time by letting your clients add and edit their Woocommerce products easily and hassle free without your help.
Version: 1.0.0
Author: Smart Meta Technologies
Author URI: https://smartmetatec.com

*/
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// This function creates a custom login page
function add_login(){ 
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
function add_products(){ 
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
function add_addproduct(){ 
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
function add_editproduct(){ 
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
function delete_login(){
    $page = get_page_by_title("WP Smart Login");

    wp_delete_post($page->ID, true);
};


// delete the products page when the plugin is deleted
function delete_products(){
    $page = get_page_by_title("WP Smart Products");

    wp_delete_post($page->ID, true);
};

// delete the add product page when the plugin is deleted
function delete_addproduct(){
    $page = get_page_by_title("WP Smart Add Product");

    wp_delete_post($page->ID, true);
};

// delete the edit product page when the plugin is deleted
function delete_editproduct(){
    $page = get_page_by_title("WP Smart Edit Product");

    wp_delete_post($page->ID, true);
};



// This will create a custom template
function login_page_template( $page_template )
{
    if ( is_page( 'WP Smart Login' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/access.php';
    }
    return $page_template;
}

// This will create a custom template
function products_page_template( $page_template )
{
    if ( is_page( 'WP Smart Products' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/products.php';
    }
    return $page_template;
}

// create a custom page template for the addproduct page
function addproduct_page_template( $page_template )
{
    if ( is_page( 'WP Smart Add Product' ) ) {
        wp_enqueue_media();
        $page_template = dirname( __FILE__ ) . '/templates/addproduct.php';
    }
    return $page_template;
}

// create a custom page template for the editproduct page
function editproduct_page_template( $page_template )
{
    if ( is_page( 'WP Smart Edit Product' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/editproduct.php';
    }
    return $page_template;
}


// Add the custom user role to the wordpress users
function add_custom_user_role(){
        add_role("product_manager", "Product Manager", array());
}

// delete the custom user from the wordpress users
function delete_custom_user_role(){
    remove_role("product_manager");
}




// these hooks fire when the plugin is activated
register_activation_hook(__FILE__, 'add_login');
register_activation_hook(__FILE__, 'add_products');
register_activation_hook(__FILE__, 'add_addproduct');
register_activation_hook(__FILE__, 'add_editproduct');
register_activation_hook(__FILE__, 'add_custom_user_role');

// These hooks fire when the plugin is deleted
register_deactivation_hook( __FILE__, 'delete_login' );
register_deactivation_hook( __FILE__, 'delete_products' );
register_deactivation_hook( __FILE__, 'delete_addproduct' );
register_deactivation_hook( __FILE__, 'delete_editproduct' );
register_deactivation_hook( __FILE__, 'delete_custom_user_role' );

// these custom filters create the custom page templates and assigns them to the correct page
add_filter( 'page_template', 'login_page_template' );
add_filter( 'page_template', 'products_page_template' );
add_filter( 'page_template', 'addproduct_page_template' );
add_filter( 'page_template', 'editproduct_page_template' );

add_filter("show_admin_bar", "hide_admin_bar");

function hide_admin_bar(){
    if(is_page("wp-smart-add-product")){
        return false;
    }
}


add_action('admin_menu', 'SetupPage');
add_action('admin_init', 'RegisterSettings');
add_action("admin_head", "admin_styles");
add_action("wp_enqueue_scripts", "wp_enqueue_media");

add_action("admin_enqueue_scripts", "load_media");
function load_media(){
    wp_enqueue_media();
}



// add_action("wp_enqueue_scripts", "enqueue_addproduct_script");

// function enqueue_addproduct_script(){
//     // echo "<h1>This is a page template and the script functions is loading</h1>";
//     // if(is_page("wp-smart-add-product")){
//     //     echo "<h1>This is a page template and the script functions is loading</h1>";
//         wp_enqueue_media();
//         wp_register_script("addproduct", plugins_url("public/js/addproduct.js", __FILE__), "", "", true);
//         wp_enqueue_script("addproduct", plugins_url("public/js/addproduct.js", __FILE__), "", "", true);
//     // }

// }

function SetupPage() {
    add_menu_page(__("WPSmartCommerce "), __("Smart Commerce"), "manage_options", __FILE__, 'PageContent', plugin_dir_url(__FILE__) . "assets/WPSC.svg");
}

function RegisterSettings() {
    // Add options to database if they don't already exist
    add_option("wp_smart_products_consumer_key", "", "", "yes");
    add_option("wp_smart_products_consumer_secret", "", "", "yes");
    add_option("wp_smart_products_logo_url", "", "", "yes");
    add_option("wp_smart_products_brand_color", "", "", "yes");

}

function PageContent() {
    // if (!current_user_can('manage_options'))
    //     wp_die(__("You don't have access to this page"));
    require_once dirname( __FILE__ ) . "/adminPage.php";

}

function admin_styles(){
    echo '<link rel="stylesheet" href="' . dirname(plugin_dir_url(__FILE__)) .'/smart_commerce/public/css/admin.css">';
    // echo  '<script src="' . dirname(plugin_dir_url(__FILE__)) . '/lk_supplier/public/js/wp_smart_feeds_help.js" defer></script>';
}

// register_activation_hook(__FILE__, "verifyDomain");

// function verifyDomain(){
//     $request = curl_init("http");
// };

add_action("wp_login", "wp_commerce_checkUser");
register_activation_hook(__FILE__, "wp_commerce_checkUser");
wp_schedule_event(time(), 'hourly', 'wp_commerce_checkUser');

function wp_commerce_checkUser(){

    $link = 'http://';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $link = 'https://';
    }

    $link .= str_replace("www.", "", $_SERVER['HTTP_HOST']);

    $data = array(
        'domain' => $link
    );

    $payload = http_build_query($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.smartmetatec.com/api/verify/commerce/user");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);

    curl_close($ch);

    $jsonResponse = json_decode($response, true);

    if(!$jsonResponse['pass']){
        deactivate_plugins(plugin_basename(__FILE__), true);
    }

};


function addLinks($links){
    $url = esc_url(
        add_query_arg(
            'page',
            'smart_commerce/smart_commerce.php',
            get_admin_url() . "admin.php"
        ));

    $settings_link = "<a href='" . $url ."' target='_blank'>Settings</a>";

    array_unshift(
        $links,
        $settings_link
    );
    return $links;
}

add_filter('plugin_action_links_smart_commerce/smart_commerce.php', "addLinks");






