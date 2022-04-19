<?php

/**
 * Template Name: Editproduct
 */


// Get the woocommerce api functions
require __DIR__ . "/woocommerce-api.php";
require  __DIR__ . "/../includes/helpers.php";
// get the correct protocol

// Everything in this php tag happens when the page is loaded
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";
$link .= "://" . $_SERVER['HTTP_HOST'];

// Global variables for the pages
$login_page = "wp-smart-login";
$products_page = "wp-smart-products";
$add_page = "wp-smart-add-product";
$edit_page = "wp-smart-edit-product";
$validCodes = smt_smart_commerce_pro_checkCode();
wp_enqueue_media();

// Check where the request for the current page is coming from
if(isset($_SERVER['HTTP_REFERER'])){
    $previous_page = $_SERVER['HTTP_REFERER'];
    $from_products = preg_match("/" . $products_page . "\/\?id=[1-9]{1,5}/", $previous_page);
    $from_self = preg_match("/" . $edit_page ."/", $previous_page);

    if($from_products || $from_self){
        if($validCodes){
            $categoriesData = json_decode($smt_smart_commerce_pro_listCategories(), true);
            $categories = $categoriesData['data'];
            $id = $_GET['id'];

            if(isset($_GET['id'])){
                $product = json_decode($smt_smart_commerce_pro_getProduct($_GET['id']), true);
            }

            $productImages = json_encode(array_map(function($item){return array('src' => $item["src"], "id" => $item['id']);}, $product['images']));

            $taxClassData = json_decode($smt_smart_commerce_pro_getTaxClasses(), true);

            $shippingClasses = json_decode($smt_smart_commerce_pro_getShippingClasses(), true);


            $unitData = json_decode($smt_smart_commerce_pro_units(), true);

            $weightUnit;
            $dimensionsUnit;

            foreach($unitData as $option){
                if($option['id'] == "woocommerce_weight_unit"){
                    $weightUnit = $option['value'];
                }
                if($option['id'] == "woocommerce_dimension_unit"){
                    $dimensionsUnit = $option['value'];
                }
            }

            $shippingClasses = json_decode($smt_smart_commerce_pro_getShippingClasses(), true);

            //  Make an array to send to javascript to handle the display of the categories
            $javascriptProductData = array(
                'downloadable' => $product['downloadable'],
                'virtual' => $product['virtual'],
                'draft' => $product["status"] == "draft" ? true : false,
                'categories' => $product['categories'],
                'manage_stock' => $product['manage_stock'],
                'product_images' => $productImages,
                'product_type' => $product['type'],
                'tax_class' => $product["tax_class"],
                'stock_status' => $product['stock_status'],
            );

            // Check if the product has a shipping class before sending it to javascript
            $product['shipping_class'] && $javascriptProductData['shipping_class'] = $product['shipping_class'];
        }

    }
    else {
        // Add product to test on local
        header("Location: " . $link . "/" . $login_page);
        exit;
    }
}
else {
    // Add product to test on local
    header("Location: " . $link . "/" . $login_page);
    exit;
}

?>

<?php
    // This will happen when the form is submitted
    if(isset($_POST['save'])){
        $error = "";

        if(isset($_POST['category']) && $_POST['category']){
            $categortArray = array(
                'name' => $_POST['category']
            );

            $data['name'] = $_POST['category'];

            if(isset($_POST['parent-category']) && $_POST['parent-category']){
                $categortArray['parent'] = $_POST['parent-category'];
            }

           if($validCodes) {
               $newCategory = json_decode($smt_smart_commerce_pro_createCategory($categortArray), true);
               if($newCategory["error"]) $error = $newCategory["message"];
            };
        }


        $data = [];
        $data['name'] = sanitize_text_field($_POST['product-name']);

        if(isset($_POST['product-regular-price'])){
            $regular_price = sanitize_text_field($_POST['product-regular-price']);
            if(preg_match($priceRegex, $regular_price)) $data['regular_price'] = $regular_price;
        } 

        if(isset($_POST['product-sale-price'])){
            $sale_price = sanitize_text_field($_POST['product-sale-price']);
            if(preg_match($priceRegex, $sale_price)) $data['sale_price'] = $sale_price;
        }

        if(isset($_POST['tax-class'])){
            $taxClass = sanitize_text_field($_POST['tax-class']);
            $correctClass = array_filter($taxClassData, function($class) use ($taxClass){ return $class['slug'] == $taxClass;});
            if(count($correctClass) == 1) $data['tax_class'] = $taxClass;
        }

        if(isset($_POST['product-type'])){
            $product_type = sanitize_text_field($_POST['product-type']);
            $productRegex = "/simple|external|grouped|variable/";
            if(preg_match($productRegex, $product_type)) $data['type'] = $product_type;
        }

        if(isset($_POST['product-virtual'])) $data['virtual'] = true;
        else $data['virtual'] = false;

        if(isset($_POST['product-downloadable'])) $data['downloadable'] = true;
        else  $data['downloadable'] = false;

        if(isset($_POST['draft'])) $data['status'] = 'draft';
        else $data['status'] = 'publish';

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
        else  $data['manage_stock'] = false;

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
                if($class['slug'] == $shipping_class) $data['shipping_class'] = $shipping_class;
            }
        } 

         // Handle the image uploads
         if($_POST['image-urls']){
            
            $imageArray = explode(";", $_POST['image-urls']);
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
            
            $productCategories = explode("%", $_POST['product-categories']);
            if(isset($newCategory)){
                array_push($smt_smart_commerce_pro_productCategories, $newCategory['id']);
            }
            $productCategories = array_map(function($c){
                return array('id' => sanitize_key($c));
            }, $productCategories);
            $data['categories'] = $productCategories;
        } else if(!$_POST['product-categories']) {
            $data['categories'] = array();
        }

        if(isset($_POST['product-tags']) && $_POST['product-tags']){
            $productTags = explode("%", $_POST['product-tags']);
            $productTags = array_map(function($t){
                return array('name' => sanitize_key($t));
            }, $productTags);
            $data['tags'] = $productTags;
        } else if(!$_POST['product-tags']){
            $data['tags'] = array();
        }   
        
       if($validCodes) {
           $saveProduct = json_decode($smt_smart_commerce_pro_updateProduct($id, $data), true);

           if($saveProduct['error']) $error = $saveProduct['message'];
        };


        if(!$error) header("Location: " . $link . "/" . $products_page . "?id=1");
    } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo esc_url(get_site_icon_url()) ?>">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" integrity="sha512-yFjZbTYRCJodnuyGlsKamNE/LlEaEAxSUDe5+u61mV8zzqJVFOH7TnULE2/PP/l5vKWpUNnF4VGVkXh3MjgLsg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/addproduct.php"?>">
    <!-- <script src="<?php // echo dirname(plugin_dir_url(__FILE__), 1) . "/public/js/editproduct.js"?>" defer></script> -->
    <?php wp_head() ?>
    <title>Edit Product</title>
</head>
<body>
    <header>
        <?php if(get_option("wp_smart_products_logo_url") !== null): ?>
            <div>
                <img src="<?php echo esc_url(get_option("wp_smart_products_logo_url")) ?>"/>
            </div>
        <?php endif;?>
        <a href="<?php echo esc_url($add_page) ?>">Add New Product</a>
        <a href="<?php echo esc_url($products_page . "?id=1") ?>">Go back to products</a>
    </header>
    <?php if($validCodes): ?>

        <?php if($error): ?>
            <div id="errors"><?php echo esc_html($error) ?></div>
        <?php endif; ?>

        <form enctype="multipart/form-data" action="" method="post" id="addeditproduct-form">

            <div id="title-price" class="flex-fields">
                <!-- This is the name field input -->
                <div>
                    <label for="product-name" class="label-block">Product Name</label>
                    <input type="text" name="product-name" id="name" value="<?php echo esc_html($product['name'])?>">
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
            
            <div id="product-description">
                <label for="product-description" class="label-block">Long Description</label>
                <?php wp_editor($product['description'], "wp-commerce-product-description")?>
            </div>

            <div id="product-settings">

                <label class="label-block">
                    Product Settings 
                    <span class="help">
                        <i class="fa-regular fa-circle-question"></i>
                        <div>Set the product type, if the product is downloadable or virtual (i.e. Not a physical product)</div>
                    </span> 
                </label>

                <div class="flex-container">
                    <div >
                        <select name="product-type" id="product-type">
                            <option value=""  disabled>Product Type</option>
                            <option value="simple" selected>Simple Product</option>
                            <option value="grouped">Grouped Product</option>
                            <option value="external">External/Affiliate Product</option>
                            <option value="variable">Variable Product</option>
                        </select>
                    </div>

                    <div>
                        <input type="checkbox" name="product-virtual" id="virtual">
                        <label for="product-virtual" class="inline">Virtual</label>
                    </div>

                    <div>
                        <input type="checkbox" name="product-downloadable" id="downloadable">
                        <label for="product-downloadable" class="inline">Downloadable</label>
                    </div>



                </div>
            </div>

            <!-- GENERAL -->
            <div id="general">
                <label class="label-block">General</label>
                <div class="flex-container">
                    <div class="flex-container-vertical">
                        <label for="product-regular-price" class="">Regular Price</label>
                        <input type="text" name="product-regular-price" id="regular-price" value="<?php echo esc_html($product['regular_price']) ?>">
                    </div>

                    <div class="flex-container-vertical">
                        <label for="product-sale-price" class="">Sale Price</label>
                        <input type="text" name="product-sale-price" id="sale-price" value="<?php echo esc_html($product['sale_price']) ?>">
                    </div>

                    <div class="flex-container-vertical">
                        <label for="tax-class">Tax Class</label>
                        <select name="tax-class" id="tax-class">
                            <option value="" disabled selected>Select Tax Class</option>
                            <?php 
                            
                                foreach($taxClassData as $taxClass){ ?>
                                    <option value="<?php echo esc_html($taxClass["slug"]) ?>"><?php echo esc_html($taxClass['name']) ?></option>
                               <?php }
                            
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- INVENTORY -->
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
                        <label for="product-sku">SKU </label>
                        <input type="text" name="product-sku" id="sku-input" value="<?php echo esc_html($product['sku']) ?>">
                    </div>

                    <div id="stock" class="flex-container">
                        <label for="enable-stock">Enable Stock </label>
                        <span>
                            <input type="checkbox" name="manage-stock" id="manage-stock">
                            This will keep count of the stock in the store
                        </span>
                    </div>

                    <div id="stock-quantity" class="flex-container">
                        <label for="stock-quantity">Stock Quantity </label>
                        <input type="number" name="stock-quantity" value="<?php echo esc_html($product['stock_quantity']) ?>">
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
                        <label for="weight" class="">Weight (<?php echo esc_html($dimensionsUnit) ?>)</label>
                        <input type="text" name="weight" value="<?php echo esc_html($product['weight']) ?>">
                    </div>

                    <div id="dimensions" class="flex-container">
                        <label for="">Dimensions (<?php echo esc_html($dimensionsUnit) ?>)</label>
                        <input type="text" name="length" placeholder="Length" value="<?php echo esc_html($product["dimensions"]["length"]) ?>">
                        <input type="text" name="width" placeholder="Width"  value="<?php echo esc_html($product["dimensions"]["width"]) ?>">
                        <input type="text" name="height" placeholder="Height"  value="<?php echo esc_html($product["dimensions"]["height"]) ?>">
                    </div>

                    <?php if(count($shippingClasses) > 0):?>
                        <div id="shipping-class-container" class="flex-container between">
                            <label for="shipping-class">Shipping Class</label>
                            <select name="shipping-class" id="shipping-class">
                                <option value="" >No Shipping Class</option>
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
                <?php wp_editor($product['short_description'], "wp-commerce-product-short-description")?>
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
                                <option value="<?php echo esc_html($category['id']) ?>" id="<?php echo esc_attr($category['id']) ?>"><?php echo esc_html($category['name']) ?></option>
                        <?php }}
                        
                        ?>
                    </select>

                    <div id="categories-checkboxes"></div>
                </div>

                <div id="tags">
                    <label class="label-block">
                        Add Tags 
                        <span class="help">
                            <i class="fa-regular fa-circle-question"></i>
                            <div>Set the tag for the product to track the product and reference it in other places as well.</div>
                        </span> 
                    </label>
                    <div id="new-tags" class="flex-container">
                        <input type="text" name="new-tag" id="new-tag">
                        <button type="button" id="tag-btn">Add</button>
                    </div>

                    <div id="tags-container">
                        <?php
                            $tags = $product['tags'];
                            foreach($tags as $tag){
                                ?>
                                <div><?php echo esc_html($tag['name']) ?></div>
                            <?php }
                        
                        ?>
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
            <input type="hidden" id="product-data" value='<?php echo esc_html(json_encode($javascriptProductData)) ?>'>
        </form>
    <?php else:?>
        <h1>Please enter the required codes in the WP Smart Commerce plugin.</h1>
    <?php endif;?>
    <div id="errors"></div>
    <?php wp_footer(); ?>
</body>
</html>