<?php

    if(isset($_POST["save-settings"])){
        if(isset($_POST["wp_smart_products_consumer_key"])) update_option("wp_smart_products_consumer_key", $_POST["wp_smart_products_consumer_key"]);
        if(isset($_POST["wp_smart_products_consumer_secret"])) update_option("wp_smart_products_consumer_secret", $_POST["wp_smart_products_consumer_secret"]);
        if(isset($_POST["wp_smart_products_logo_url"])) update_option("wp_smart_products_logo_url", $_POST["wp_smart_products_logo_url"]);
        if(isset($_POST["wp_smart_products_brand_color"])) update_option("wp_smart_products_brand_color", $_POST["wp_smart_products_brand_color"]);
    }


?>

<div class="wrap" id="smt-admin-page">
        <h1>Enter the WooCommerce API Credentials below</h1>
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
    </div>