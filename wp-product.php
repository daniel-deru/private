<?php

/*

Plugin Name: WP Product


*/


// This function creates a custom login page
function add_login(){ 
    $title_of_page = "access";
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'access',
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
    $title_of_page = "products";
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'products',
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
    $title_of_page = "addproduct";
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'addproduct',
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
    $title_of_page = "editproduct";
    if( null == get_page_by_title( $title_of_page ) ) {
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  '1',
                    'post_name'         =>  'editproduct',
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
    $page = get_page_by_title("access");

    wp_delete_post($page->ID, true);
};


// delete the products page when the plugin is deleted
function delete_products(){
    $page = get_page_by_title("products");

    wp_delete_post($page->ID, true);
};

// delete the add product page when the plugin is deleted
function delete_addproduct(){
    $page = get_page_by_title("addproduct");

    wp_delete_post($page->ID, true);
};

// delete the edit product page when the plugin is deleted
function delete_editproduct(){
    $page = get_page_by_title("editproduct");

    wp_delete_post($page->ID, true);
};



// This will create a custom template
function login_page_template( $page_template )
{
    if ( is_page( 'access' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/access.php';
    }
    return $page_template;
}

// This will create a custom template
function products_page_template( $page_template )
{
    if ( is_page( 'products' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/products.php';
    }
    return $page_template;
}

// create a custom page template for the addproduct page
function addproduct_page_template( $page_template )
{
    if ( is_page( 'addproduct' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/addproduct.php';
    }
    return $page_template;
}

// create a custom page template for the editproduct page
function editproduct_page_template( $page_template )
{
    if ( is_page( 'editproduct' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/editproduct.php';
    }
    return $page_template;
}

function add_custom_user_role(){
    // if(!$GLOBALS['wp_roles']->is_role("product_manager")){
        add_role("product_manager", "Product Manager", array());
    // }
}

function delete_custom_user_role(){
    remove_role("product_manager");
}



// set the stylesheet for the page
function access_page_template_styles() {
    // if ( is_page_template( 'access.php' ) ) {
        wp_register_style('access', plugin_dir_url(__FILE__) . 'templates/access.css');
        wp_enqueue_style('access');
    // }
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


add_action( 'wp_enqueue_scripts', 'access_page_template_styles' );


add_action('admin_menu', 'SetupPage');
add_action('admin_init', 'RegisterSettings');

function SetupPage() {
    add_menu_page(__("WPSmartProducts "), __("WP Smart Products"), "manage_options", __FILE__, 'PageContent', "");
}

function RegisterSettings() {
    // Add options to database if they don't already exist
    add_option("wp_smart_products_consumer_key", "", "", "yes");
    add_option("wp_smart_products_consumer_secret", "", "", "yes");

    // Register settings that this form is allowed to update
    register_setting('wp_smart_products_settings', 'wp_smart_products_consumer_key');
    register_setting('wp_smart_products_settings', 'wp_smart_products_consumer_secret');
}

function PageContent() {
    if (!current_user_can('manage_options'))
        wp_die(__("You don't have access to this page"));
    ?><div class="wrap">
        <h1>Enter the WooCommerce API Credentials below</h1>
        <form method="post" action="options.php">

            <?php settings_fields('wp_smart_products_settings'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Consumer Key</th>
                    <td><input type="text" name="wp_smart_products_consumer_key" value="<?php echo get_option('wp_smart_products_consumer_key'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Consumer Secret</th>
                    <td><input type="text" name="wp_smart_products_consumer_secret" value="<?php echo get_option('wp_smart_products_consumer_secret'); ?>" /></td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" class="button-primary" value="Save" />
            </p>

        </form>
    </div>
    <?php }