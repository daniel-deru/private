<?php
/**
 * 
 * Template Name: Access
 * Template Post Type: page
 * 
 */


function smt_smart_commerce_pro_check(){
    $link = 'http://';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $link = 'https://';
    }

    $link .= str_replace("www.", "", $_SERVER['HTTP_HOST']);
    // $link .= $_SERVER['HTTP_HOST'];

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
    echo "<pre>";
    print_r($data);
    print_r($jsonResponse);
    echo "</pre>";

    if(!$jsonResponse['pass']){
        echo "the response didn't pass";
    }
    else {
        echo "The response passed";
    }

};


// smt_smart_commerce_pro_check();
$error = null;

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";

// Here append the common URL characters.
$link .= "://";

// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
// $link .= $_SERVER['REQUEST_URI'];

if(isset($_POST['products-login'])){

    if (empty($_POST['products-name'])) $error = "Please enter a name";
    else if(empty($_POST["products-password"])) $error = "Please enter a password";
    
    else if(!empty($_POST['products-name']) && !empty($_POST['products-password'])){

        $name = sanitize_title($_POST['products-name']);
        $password = sanitize_text_field($_POST['products-password']);

        $user = wp_authenticate($name, $password);

        if(!is_wp_error($user)){
            if(in_array('product_manager', $user->roles) || in_array('administrator', $user->roles)){

                header("Location:" . $link . "/wp-smart-products?id=1");
                exit;
            } else {
                $error = "The password is wrong or the user doesn't exist";
            }
        } else {
            $error = "Incorrect Username or Password";
        }
        
    }
}


$color = get_option("wp_smart_products_brand_color") ? get_option("wp_smart_products_brand_color") : "#21759b";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo esc_url(get_site_icon_url()) ?>">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <!-- <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" integrity="sha512-yFjZbTYRCJodnuyGlsKamNE/LlEaEAxSUDe5+u61mV8zzqJVFOH7TnULE2/PP/l5vKWpUNnF4VGVkXh3MjgLsg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <!-- <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/access.php"?>"> -->
    <!-- <script src="<?php // echo dirname(plugin_dir_url(__FILE__), 1) . "/public/js/access.js"?>" defer></script> -->
    <?php wp_head() ?>
    <title>Login</title>
</head>
<body>


    <div id="display_errors"><?php if($error) echo esc_html($error) ?></div>
    <div id="login-form-wrapper">
        <?php if(get_option("wp_smart_products_logo_url")):?>
            <div id="wp-smart-commerce-brand-logo">
                <img src="<?php echo esc_url(get_option("wp_smart_products_logo_url")) ?>" alt="">
            </div>
        <?php endif; ?>
        <form action="" method="post" id="login-form">
            <div class="form-field">
                <label for="products-name">Username</label>
                <input type="text" name="products-name">
            </div>

            <div class="form-field">
                <label for="products-password">Password</label>
                <div class="password-container">
                    <input type="password" name="products-password" id="password">
                    <button id="icon-button" type="button">
                        <i class="fa fa-eye show" id="show-password"></i>
                    </button>
                </div>
            </div>
            <div class="form-field">
                <input type="submit" name="products-login" value="login" id="login-btn">
            </div>
        </form>
    </div>
    <input type="hidden" value="<?php echo esc_html($color) ?>" id="brand-color">
   <?php wp_footer() ?>
</body>
</html>


