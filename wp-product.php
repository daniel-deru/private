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
// function wpse_enqueue_page_template_styles() {
//     if ( is_page_template( 'templates/login.php' ) ) {
//         echo "<h1>The page template;s css has been loaded</h1>";
//         wp_enqueue_style( 'login', plugins_url() . "/private/assets/styles/" );
//     }
//     else {
//         echo "<h1>The template page css did not load </h1>";
//     }
// }



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


// add_action( 'wp_enqueue_scripts', 'wpse_enqueue_page_template_styles' );