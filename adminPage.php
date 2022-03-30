<?php
    $link = 'http';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') $link = 'https';
    $link = $link . "://" . $_SERVER['HTTP_HOST']

?>

<?php

    if(isset($_POST["save-settings"])){
        if(isset($_POST["wp_smart_products_consumer_key"])) update_option("wp_smart_products_consumer_key", $_POST["wp_smart_products_consumer_key"]);
        if(isset($_POST["wp_smart_products_consumer_secret"])) update_option("wp_smart_products_consumer_secret", $_POST["wp_smart_products_consumer_secret"]);
        if(isset($_POST["wp_smart_products_logo_url"])) update_option("wp_smart_products_logo_url", $_POST["wp_smart_products_logo_url"]);
        if(isset($_POST["wp_smart_products_brand_color"])) update_option("wp_smart_products_brand_color", $_POST["wp_smart_products_brand_color"]);
    }


?>

<div class="wrap" id="smt-admin-page">
        <div id="wp-smart-commerce-header-container">
            <img src="<?= dirname(plugin_dir_url(__FILE__))?>/smart_commerce/assets/SC Color Logo 150X150.png" alt="">
            <h1>WP Smart Commerce Dashboard</h1>
        </div>
        
        <form method="post" action="">
            <div>
                <label>Consumer Key</label>
                <input type="text" name="wp_smart_products_consumer_key" value="<?php echo get_option('wp_smart_products_consumer_key'); ?>" />
            </div>

            <div>
                <label >Consumer Secret</label>
                <input type="text" name="wp_smart_products_consumer_secret" value="<?php echo get_option('wp_smart_products_consumer_secret'); ?>" />
            </div>

            <div>
                <label for="">Enter Logo URL</label>
                <input type="text" name="wp_smart_products_logo_url" value="<?php echo get_option("wp_smart_products_logo_url");?>">
            </div>

            <div>
                <label for="wp_smart_products_color">Enter Brand Color</label>
                <?php // This input is placed in a div because of the color pick eye dropper chrome extension?>
                <div>
                    <input type="color" name="wp_smart_products_brand_color" id="" value="<?php echo get_option("wp_smart_products_brand_color") ? get_option("wp_smart_products_brand_color"): "#21759b";?>">
                </div>
                
            </div>

            <div class="submit">
                <input type="submit" class="button-primary" value="Save" name="save-settings"/>
            </div>

        </form>

        <div>
            <a href="<?= $link?>/wp-smart-login" target="_blank">Go To Products</a>
        </div>

        <section id="guide">
            <div>
                <h3>How to setup plugin</h3>
                <ol>
                    <li>In the Admin Dashboard go to: <b>WooCommerce > Settings > Advanced > <a href="<?= $link?>/wp-admin/admin.php?page=wc-settings&tab=advanced&section=keys" target="_blank">REST API</a></b>.</li>
                    <li>Click on <b>Add Key</b>.</li>
                    <li>Enter a description for the key such as: WP Smart Commerce Integration.</li>
                    <li>Make sure the correct User is selected and set the Permissions to <b>Read/Write</b>.</li>
                    <li>Click on <b>Generate Key</b>.</li>
                    <li>Copy both the <b>Consumer Key</b> and <b>Consumer Secret</b> and put them somewhere safe.</li>
                    <li>In the <b>Smart Commerce Admin Dashboard</b> paste the keys in the correct fields as they are labelled.</li>
                    <li>Optionally enter your Logo URL and brand color and click Save.</li>
                </ol>
            </div>
            <div>
                <h3>Create a User To Use The Plugin (Administrators already have access)</h3>
                <ol>
                    <li>Go to Users and Click on <b> <a href="<?= $link?>/wp-admin/user-new.php" target="_blank">Add New</a></b>.</li>
                    <li>Enter the <b>Username</b> and <b>Email</b> as well as any other optional fields.</li>
                    <li>Click on <b>Generate password</b> or set your own.</li>
                    <li>Under <b>Role</b> select the <b>Product Manager</b> role. (Only Product Managers and Administrators can use the plugin).</li>
                    <li>Click on <b>Add New User</b>.</li>
                </ol>
            </div>


            
        </section>
 
    </div>