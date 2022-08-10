<?php
    $link = 'http';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') $link = 'https';
    $link = sanitize_url($link . "://" . $_SERVER['HTTP_HOST']);

?>

<?php

    if(isset($_POST["save-settings"])){
        if(isset($_POST["wp_smart_products_consumer_key"])){
            $consumer_key = sanitize_key($_POST["wp_smart_products_consumer_key"]);
            $consumerKeyRegex = "/ck_[0-9a-f]{40}/i";
            if(preg_match($consumerKeyRegex, $consumer_key)) update_option("wp_smart_products_consumer_key", $consumer_key);
        } 
        if(isset($_POST["wp_smart_products_consumer_secret"])){
            $consumer_secret = sanitize_key($_POST["wp_smart_products_consumer_secret"]);
            $consumerSecretRegex = "/cs_[0-9a-f]{40}/i";
            if(preg_match($consumerSecretRegex, $consumer_secret)) update_option("wp_smart_products_consumer_secret", $consumer_secret);
        } 
        if(isset($_POST["wp_smart_products_logo_url"])){
            update_option("wp_smart_products_logo_url", sanitize_url($_POST["wp_smart_products_logo_url"]));
        }

        if(isset($_POST["wp_smart_products_brand_color"])){
            $brand_color = sanitize_hex_color($_POST["wp_smart_products_brand_color"]);
            $colorRegex = "/#[0-9a-f]{3,6}/i";
            if(preg_match($colorRegex, $brand_color)) update_option("wp_smart_products_brand_color", $brand_color);
        } 
    }


?>

<div class="wrap" id="smt-admin-page">
        <div id="wp-smart-commerce-header-container">
            <img src="<?php echo esc_url(plugins_url("assets/SCColorLogo150X150.png", __FILE__)) ?>" alt="">
            <h1>WP Smart Commerce Dashboard</h1>
        </div>
        
        <form method="post" action="">

            <div>
                <label for="">Enter Logo URL</label>
                <input type="text" name="wp_smart_products_logo_url" value="<?php echo esc_url(get_option("wp_smart_products_logo_url")) ?>">
            </div>

            <div>
                <label for="wp_smart_products_color">Enter Brand Color</label>
                <?php // This input is placed in a div because of the color pick eye dropper chrome extension ?>
                <div>
                    <input type="color" name="wp_smart_products_brand_color" id="" value="<?php echo get_option("wp_smart_products_brand_color") ? esc_html(get_option("wp_smart_products_brand_color")) : esc_html("#21759b") ?>">
                </div>
                
            </div>

            <div class="submit">
                <input type="submit" class="button-primary" value="Save" name="save-settings"/>
            </div>

        </form>

        <div>
            <a href="<?php echo esc_url(get_site_url(null, "wp-smart-login")) ?>" target="_blank">Go To Products</a>
        </div>

        <section id="guide">
            <div>
                <h3>Create a User To Use The Plugin (Administrators already have access)</h3>
                <ol>
                    <li>Go to Users and Click on <b> <a href="<?php echo esc_url(admin_url("user-new.php")) ?>" target="_blank">Add New</a></b>.</li>
                    <li>Enter the <b>Username</b> and <b>Email</b> as well as any other optional fields.</li>
                    <li>Click on <b>Generate password</b> or set your own.</li>
                    <li>Under <b>Role</b> select the <b>Product Manager</b> role. (Only Product Managers and Administrators can use the plugin).</li>
                    <li>Click on <b>Add New User</b>.</li>
                </ol>
            </div>


            
        </section>
 
    </div>