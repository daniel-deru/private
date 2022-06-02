<?php

/**
 * Template Name: addproduct
 */


// Get the woocommerce api functions
require "woocommerce-api.php";
require  dirname(plugin_dir_path(__FILE__)) . "/includes/helpers.php";
require dirname(plugin_dir_path(__FILE__)) . "/includes/products.php";

// get the correct protocol
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";
$link = sanitize_url($link . "://" . $_SERVER['HTTP_HOST']);

// Global variables for the pages
$login_page = "wp-smart-login";
$products_page = "wp-smart-products";
$add_page = "wp-smart-add-product";
$edit_page = "wp-smart-edit-product";
$validCode = smt_smart_commerce_pro_checkCode();
wp_enqueue_media();




// Check where the request for the current page is coming from
if(isset($_SERVER['HTTP_REFERER'])){
    $previous_page = sanitize_url($_SERVER['HTTP_REFERER']);
    $from_products = preg_match("/" . $products_page . "\/\?id=[1-9]{1,5}/", $previous_page);
    $from_self = preg_match("/" . $add_page ."/", $previous_page);
    $from_edit_page = preg_match("/" . $edit_page ."/", $previous_page);

    if($from_products || $from_self || $from_edit_page){
        if($validCode){
            
            // Get all the categories
            $categories = array_map(function($category){
                return $category->to_array();
            }, get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]));

            // show($categories);

            // Get the weight and dimensions unit
            $weightUnit = get_option('woocommerce_weight_unit');
            $dimensionsUnit = get_option('woocommerce_dimension_unit');

            // Get the tax class and the tax class slugs
            $taxClasses = WC_Tax::get_tax_classes();
            $taxClassSlugs = WC_Tax::get_tax_class_slugs();

            // Get the shipping classes
            $shippingClasses = get_terms(array('taxonomy' => 'product_shipping_class', 'hide_empty' => false ));
            if(count($shippingClasses) > 0) $shippingClasses = array_map(function($cls){ return $cls->to_array(); }, $shippingClasses);

            
        }
        

    }
    else {
        // Add product to test on local
        header("Location: " . get_site_url(null, $login_page));
        exit;
    }
}
else {
    // Add product to test on local
    header("Location: " . get_site_url(null, $login_page));
    exit;
}

?>

<?php
    // This will happen when the form is submitted
    if(isset($_POST['save'])){
        $error = "";

        // If a new category needs to be created
        if(isset($_POST['category']) && $_POST['category']){

            // This array is for when a new category gets created
            $categoryArray = array('name' => sanitize_title($_POST['category']));

            // This adds the new category to the list of product categories
            $data['name'] = sanitize_title($_POST['category']);

            // Check if a parent category was specified
            if(isset($_POST['parent-category']) && $_POST['parent-category']){
                $categoryArray['parent'] = sanitize_title($_POST['parent-category']);
            } 

            // Change this
            // if($validCode) {
            //     $newCategory = json_decode($smt_smart_commerce_pro_createCategory($categoryArray), true);
            //     if($newCategory['error']) $error = $newCategory['message'];
            // };
        }
        
        


        $data = [];
        
        $data['name'] = sanitize_text_field($_POST['product-name']);

        $priceRegex = "/^[0-9]{1,9}(\.[0-9]{1,3})?$/";

        if(isset($_POST['product-regular-price'])){
            $regular_price = sanitize_text_field($_POST['product-regular-price']);
            if(preg_match($priceRegex, $regular_price)) $data['regular_price'] = $regular_price;
        } 

        if(isset($_POST['product-sale-price'])){
            $sale_price = sanitize_text_field($_POST['product-sale-price']);
            if(preg_match($priceRegex, $sale_price)) $data['sale_price'] = $sale_price;
        } 

        if(isset($_POST['tax-class']) && $_POST['tax-class']) $data['tax_class'] = $_POST['tax-class'];

        if(isset($_POST['product-type'])){
            $product_type = sanitize_text_field($_POST['product-type']);
            $productRegex = "/simple|external|grouped|variable/";
            if(preg_match($productRegex, $product_type)) $data['type'] = $product_type;
        } 

        if(isset($_POST['product-virtual'])) $data['virtual'] = true;

        if(isset($_POST['product-downloadable'])) $data['downloadable'] = true;

        if(isset($_POST['draft'])) $data["status"] = "draft";

        if(isset($_POST['wp-commerce-product-description'])){
            $description = wp_kses_post($_POST['wp-commerce-product-description']);
            $data['description'] = $description;
        } 

        if(isset($_POST['wp-commerce-product-short-description'])){
            $shortDescription = wp_kses_post($_POST['wp-commerce-product-short-description']);
            $data['short_description'] = $shortDescription;
        } 

        if(isset($_POST['product-sku'])){
            $product_sku = sanitize_text_field($_POST['product-sku']);
            $data['sku'] = $product_sku;
        } 

        if(isset($_POST['manage-stock'])) $data['manage_stock'] = true;

        if(isset($_POST['stock-quantity'])){
            $stock_quantity = sanitize_text_field($_POST['stock-quantity']);
            $quantityRegex = "/[0-9]{1,9}/";
            if(preg_match($quantityRegex, $stock_quantity)) $data['stock-quantity'] = $stock_quantity;
            if(!$_POST['stock-quantity']){
                $data['stock-quantity'] = 0;
            }
        }

        if(isset($_POST['stock-status'])){
            $stock_status = sanitize_key($_POST['stock-status']);
            $statusRegex = "/instock|outofstock|onbackorder/";
            if(preg_match($statusRegex, $stock_status)) $data['stock_status'] = $stock_status;
        } 

        // check if the dimensions are filled in and add them to the data object
        $shippingRegex = "/[0-9]{1,6}((\.|,)[0-9]{1,6})?/";
        if(isset($_POST['length']) && isset($_POST['width']) && isset($_POST['height'])){

            $length = sanitize_text_field($_POST['length']);
            $width = sanitize_text_field($_POST['width']);
            $height = sanitize_text_field($_POST['height']);

            if(
                preg_match($shippingRegex, $length) &&
                preg_match($shippingRegex, $width) &&
                preg_match($shippingRegex, $height)                
            ) 
            {
                $dimensions = array(
                    'length' => $length,
                    'width' => $width,
                    'height' => $height
                );
    
                $data['dimensions'] = $dimensions;
            }

            
        }

        // Check if the weight is filled
        if(isset($_POST['weight'])){
            $weight = sanitize_text_field($_POST['weight']);
            if(preg_match($shippingRegex, $weight)) $data["weight"] = $weight;
        } 


        if(isset($_POST['shipping-class'])){
            $shipping_class = sanitize_key($_POST['shipping-class']);

            foreach($shippingClasses as $class){
                // Set the shipping class id
                if($class['slug'] == $shipping_class) $data['shipping_class'] = $class['term_id'];
            }
        } 


        // Handle the image uploads
        if($_POST['image-urls']){
            
            $imageArray = explode(";", sanitize_text_field($_POST['image-urls']));
            $featuredImage = sanitize_key($_POST['featured']);

            if($featuredImage !== $imageArray[0]){
                $featuredIndex = array_search($featuredImage, $imageArray);
                array_splice($imageArray, $featuredIndex, 1);
                array_unshift($imageArray, $featuredImage);
            };

            $imageArray = array_map(function($item){return array('id' => sanitize_key($item));}, $imageArray);
            $data['images'] = $imageArray;

        }


        if(isset($_POST['product-categories']) && $_POST['product-categories']){
            $productCategories = explode("%", wp_kses_post($_POST['product-categories']));
            // if($newCategory){
            //     array_push($productCategories, $newCategory['id']);
            // }

            $productCategories = array_map(function($c){
                return array('id' => sanitize_key($c));
            }, $productCategories);
            $data['categories'] = $productCategories;
        }

        if(isset($_POST['product-tags']) && $_POST['product-tags']){
            $productTags = explode("%", sanitize_text_field($_POST['product-tags']));
            $productTags = array_map(function($t){
                return array('name' => sanitize_title($t));
            }, $productTags);
            $data['tags'] = $productTags;
        }

        create_product($data);
        // Change this 
    //    if($validCode) {

    //         $saveProduct = json_decode($smt_smart_commerce_pro_addProduct($data), true);
    //         if(isset($saveProduct['error'])) $error = $saveProduct['message'];  
    //    }

       if(!$error)  header("Location: " . get_site_url(null, $products_page . "?id=1"));

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
    <?php wp_head() ?>
    <title>Add Product</title>
</head>
<body>
    <header>
        <?php if(get_option("wp_smart_products_logo_url") !== null): ?>
            <div>
                <img src="<?php echo esc_url(get_option("wp_smart_products_logo_url")) ?>"/>
            </div>
        <?php endif;?>
        <a href="<?php echo esc_url(get_site_url(null, $products_page . "?id=1")) ?>">Go back to products</a>
    </header>
    <?php if($validCode): ?>
        <?php if($error):?>
            <div id="errors"><?php echo esc_html($error) ?></div>
        <?php endif;?>
        <form enctype="multipart/form-data" action="" method="post" enctype='multipart/form-data' id="addeditproduct-form">

            <!-- Name Input field -->
            <div id="title-price" class="flex-fields">
                <div>
                    <label for="product-name" class="label-block">Product Name</label>
                    <input type="text" name="product-name" id="name" value="<?php if(isset($_POST['product-name'])) echo esc_html($_POST['product-name'])?>">
                </div>
            </div>


            <!-- Image Input Field -->
            <div id="product-image">
                <div>
                    <label for="" class="label-block">Product Image & Gallery</label>
                    <button type="button" id="image-selector">Select Image</button>
                </div>
                
                <div id="image-viewer"></div>
                <input type="hidden" id="image-urls" name="image-urls">
            </div>
            

            <!-- Product Long Description -->
            <div id="product-description">
                <label for="product-description" class="label-block">Long Description</label>
                <?php wp_editor("", "wp-commerce-product-description")?>
               
                <!-- <textarea name="product-description" cols="30" rows="10" id="description">
                    <?php //if(isset($_POST['product-description'])) echo htmlentities($_POST['product-description']) ?>
                </textarea> -->
            </div>

            <!-- Product Data -->
            <div id="product-settings" class="flex-fields">
                <label class="label-block">
                    Product Data
                    <span class="help">
                        <i class="fa-regular fa-circle-question"></i>
                        <div>Set the product type, if the product is downloadable or virtual (i.e. Not a physical product)</div>
                    </span>
                </label>
                <div class="flex-container">
                    <div >
                        <select name="product-type" id="product-type">
                            <option value="" selected disabled>Product Type</option>
                            <option value="simple">Simple Product</option>
                            <option value="grouped">Grouped Product</option>
                            <option value="external">External/Affiliate Product</option>
                            <option value="variable">Variable Product</option>
                        </select>
                    </div>
        
                    <div>
                        <input type="checkbox" name="product-virtual" id="virtual">
                        <label for="virtual" class="inline">Virtual</label>
                    </div>
        
                    <div>
                        <input type="checkbox" name="product-downloadable" id="downloadable">
                        <label for="downloadable" class="inline">Downloadable</label>
                    </div>


                </div>
            </div>

            <!-- General -->
            <div id="general">
                <label class="label-block">General</label>
                <div class="flex-container">
                    <div class="flex-container-vertical">
                        <label for="product-regular-price" class="">Regular Price</label>
                        <input type="text" name="product-regular-price" id="regular-price" value="<?php if(isset($_POST['product-regular-price'])) echo esc_html($_POST['product-regular-price']) ?>">
                    </div>

                    <div class="flex-container-vertical">
                        <label for="product-sale-price" class="padding-left">Sale Price</label>
                        <input type="text" name="product-sale-price" id="sale-price" value="<?php if(isset($_POST['product-sale-price'])) echo esc_html($_POST['product-sale-price']) ?>">
                    </div>

                    <div class="flex-container-vertical">
                        <label for="tax-class">Tax Class</label>
                        <select name="tax-class" id="tax-class">
                            <option value="" disabled selected>Select Tax Class</option>
                            <?php 
                            
                                foreach($taxClasses as $i => $taxClass){ ?>
                                    <option value="<?php echo esc_html($taxClassSlugs[$i])?>"><?php echo esc_html($taxClass) ?></option>
                               <?php }
                            
                            ?>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Inventory -->
            <div id="inventory">
                <label class="label-block">
                    Inventory
                    <span class="help">
                        <i class="fa-regular fa-circle-question"></i>
                        <div>Set stock data like the SKU, whether you want to keep count of stock and if so, how much stock you have.</div>
                    </span> 
                </label>
                <div class="inventory-container">
                    <div id="sku" class="flex-container">
                        <label for="product-sku" class="">SKU</label>
                        <input type="text" name="product-sku" id="sku-input" value="<?php if(isset($_POST['product-sku'])) echo esc_html($_POST['product-sku']) ?>">
                    </div>

                    <div id="stock" class="flex-container">
                        <label for="enable-stock">Enable Stock</label>
                        <span>
                            <input type="checkbox" name="manage-stock" id="manage-stock">
                            This will keep count of the stock in the store
                        </span>
                    </div>

                    <div id="stock-quantity" class="flex-container">
                        <label for="stock-quantity">Stock Quantity</label>
                        <input type="number" value="0" name="stock-quantity" value="<?php if(isset($_POST['stock-quantity'])) echo esc_html($_POST['stock-quantity']) ?>">
                    </div>

                    <div id="stock-status-container" class="flex-container">
                        <label for="stock-status">Stock Status</label>
                        <select name="stock-status" id="stock-status">
                            <option value="instock" >In Stock</option>
                            <option value="outofstock" >Out of Stock</option>
                            <option value="onbackorder" >On Back Order</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Shipping -->
            <div id="shipping">
                <label class="label-block">
                    Shipping
                    <span class="help">
                        <i class="fa-regular fa-circle-question"></i>
                        <div>Set shipping information such as the weight and dimensions of the product. (keep in mind the unit these values are measured in)</div>
                    </span>
                </label>

                <div>
                    <div id="weight" class="flex-container between">
                        <label for="weight" class="">Weight (<?php echo esc_html($weightUnit) ?>)</label>
                        <input type="text" name="weight" value="<?php if(isset($_POST['weight'])) echo esc_html($_POST['weight']) ?>">
                    </div>

                    <div id="dimensions" class="flex-container">
                        <label for="">Dimensions (<?php echo esc_html($dimensionsUnit) ?>)</label>
                        <input type="text" name="length" placeholder="Length" value="<?php if(isset($_POST['length'])) echo esc_html($_POST['length']) ?>">
                        <input type="text" name="width" placeholder="Width" value="<?php if(isset($_POST['width'])) echo esc_html($_POST['width']) ?>">
                        <input type="text" name="height" placeholder="Height" value="<?php if(isset($_POST['height'])) echo esc_html($_POST['height']) ?>">
                    </div>

                    <?php if(count($shippingClasses) > 0):?>
                        <div id="shipping-class-container" class="flex-container between">
                            <label for="shipping-class">Shipping Class</label>
                            <select name="shipping-class" id="shipping-class">
                                <option value="">No Shipping Class</option>
                                <?php foreach($shippingClasses as $shippingClass):?>
                                    <option value="<?php echo esc_html($shippingClass['slug']) ?>"><?php echo esc_html($shippingClass['name']) ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    <?php endif; ?>

                    
                </div>
            </div>

            <div id="product-short-description">
                <label for="product-short-description" class="label-block">Short Description</label>
                <?php wp_editor("", "wp-commerce-product-short-description")?>
                <!-- <textarea name="product-short-description" id="short-description" cols="30" rows="10">
                    <?php // if(isset($_POST['product-short-description'])) echo htmlentities($_POST['product-short-description']) ?>
                </textarea> -->
            </div>

        <div id="categories-tags-container" class="flex-container around">

                <div id="categories">
                    <label class="label-block">
                        Choose Categories
                        <span class="help">
                            <i class="fa-regular fa-circle-question"></i>
                            <div>Set the categories for the product. You can set more that  one category for a product or create a new category. You can also create sub-categories but selecting the parent category for your new category</div>
                        </span> 
                    </label>
                    <div id="new-categories">
                        <input type="text" name="category" placeholder="Make a new category" id="category-input">
                    </div>
                    <select name="parent-category" id="parent-categories">
                        <option value="" selected >Set Parent</option>
                        <?php
                            foreach($categories as $category){
                                if($category['parent'] == 0){?>
                                <option value="<?php echo esc_html($category['term_id']) ?>" id="<?php echo $category['term_id']?>">
                                    <?php echo esc_html($category['name']) ?>
                                </option>
                        <?php }}
                        
                        ?>
                    </select>
                    <div id="categories-checkboxes">

                    </div>
                </div>

                <div id="tags">
                <label class="label-block">
                    Add Tags
                    <span class="help">
                            <i class="fa-regular fa-circle-question"></i>
                            <div>Set the tag for the product to track the product and reference it in other places as well.</div>
                    </span> 
                </label>
                    <div id="new-tags">
                        <input type="text" name="new-tag" id="new-tag">
                        <button type="button" id="tag-btn">Add</button>
                    </div>
                    <div id="tags-container">

                    </div>
                </div>

        </div>

        

            <div id="btn-save">
                <input type="submit" id="save-btn" value="Save" name="save">
                <div>
                    <input type="checkbox" name="draft" id="draft">
                    <label for="draft" class="inline">Hide Product</label>
                </div>
            </div>
            <?php //This will get the data from the form to submit to the api?>
            <input type="hidden" name="product-categories" id="hidden-categories">
            <input type="hidden" name="product-tags" id="hidden-tags">
            <?php // This will pass the data to javascript to handle the displaying of the categories?>
            <input type="hidden" id="php-categories-data" value='<?php echo esc_html(json_encode($categories)) ?>'>
        </form>
    <?php else: ?>
        <h1>Please enter the required codes in the WP Smart Commerce plugin.</h1>
    <?php endif; ?>
    <input type="hidden" value="<?php echo esc_html($color) ?>" id="brand-color">
</body>
<?php wp_footer(); ?>
</html>