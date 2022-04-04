<?php

/**
 * Template Name: addproduct
 */


// Get the woocommerce api functions
require __DIR__ . "/woocommerce-api.php";
require  __DIR__ . "/../includes/helpers.php";

// get the correct protocol
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";
$link .= "://" . $_SERVER['HTTP_HOST'];

// Global variables for the pages
$login_page = "wp-smart-login";
$products_page = "wp-smart-products";
$add_page = "wp-smart-add-product";
$validCode = checkCode();
wp_enqueue_media();




// Check where the request for the current page is coming from
if(isset($_SERVER['HTTP_REFERER'])){
    $previous_page = $_SERVER['HTTP_REFERER'];
    $from_products = preg_match("/" . $products_page . "\/\?id=[1-9]{1,5}/", $previous_page);
    $from_self = preg_match("/" . $add_page ."/", $previous_page);

    if($from_products || $from_self){
        if($validCode){
            $categoriesData = json_decode($listCategories(), true);
            $categories = $categoriesData['data'];

            $unitData = json_decode($units(), true);            

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

            $taxClassData = json_decode($getTaxClasses(), true);

            $shippingClasses = json_decode($getShippingClasses(), true);
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

          if($validCode) {
              $newCategory = json_decode($createCategory($categortArray), true);
              if($newCategory['error']) $error = $newCategory['message'];
            };
        }
        
        


        $data = [];
        $data['name'] = $_POST['product-name'];

        if(isset($_POST['product-regular-price'])) $data['regular_price'] = $_POST['product-regular-price'];

        if(isset($_POST['product-sale-price'])) $data['sale_price'] = $_POST['product-sale-price'];

        if(isset($_POST['tax-class'])) $data['tax_class'] = $_POST['tax-class'];

        if(isset($_POST['product-type'])) $data['type'] = $_POST['product-type'];

        if(isset($_POST['product-virtual'])) $data['virtual'] = true;

        if(isset($_POST['product-downloadable'])) $data['downloadable'] = true;

        if(isset($_POST['draft'])) $data["status"] = "draft";

        if(isset($_POST['wp-commerce-product-description'])) $data['description'] = $_POST['wp-commerce-product-description'];

        if(isset($_POST['wp-commerce-product-short-description'])) $data['short_description'] = $_POST['wp-commerce-product-short-description'];

        if(isset($_POST['product-sku'])) $data['sku'] = $_POST['product-sku'];

        if(isset($_POST['manage-stock'])) $data['manage_stock'] = true;

        if(isset($_POST['stock-quantity'])){
            $data['stock-quantity'] = $_POST['stock-quantity'];
            if(!$_POST['stock-quantity']){
                $data['stock-quantity'] = 0;
            }
        }

        if(isset($_POST['stock-status'])) $data['stock_status'] = $_POST['stock-status'];

        // check if the dimensions are filled in and add them to the data object
        if(isset($_POST['length']) && isset($_POST['width']) && isset($_POST['height'])){
            $dimensions = array(
                'length' => $_POST['length'],
                'width' => $_POST['width'],
                'height' => $_POST['height']
            );

            $data['dimensions'] = $dimensions;
        }

        // Check if the weight is filled
        if(isset($_POST['weight'])) $data["weight"] = $_POST["weight"];


        if(isset($_POST['shipping-class'])) $data['shipping_class'] = $_POST['shipping-class'];


        // Handle the image uploads
        if($_POST['image-urls']){
            
            $imageArray = explode(";", $_POST['image-urls']);
            $featuredImage = $_POST['featured'];
            echo "THis is the featured image => " . $featuredImage;
            if($featuredImage !== $imageArray[0]){
                $featuredIndex = array_search($featuredImage, $imageArray);
                array_splice($imageArray, $featuredIndex, 1);
                array_unshift($imageArray, $featuredImage);
            };

            $imageArray = array_map(function($item){return array('id' => $item);}, $imageArray);
            $data['images'] = $imageArray;

        }


        if(isset($_POST['product-categories']) && $_POST['product-categories']){
            $productCategories = explode("%", $_POST['product-categories']);
            if($newCategory){
                array_push($productCategories, $newCategory['id']);
            }
            $productCategories = array_map(function($c){
                return array('id' => $c);
            }, $productCategories);
            $data['categories'] = $productCategories;
        }

        if(isset($_POST['product-tags']) && $_POST['product-tags']){
            $productTags = explode("%", $_POST['product-tags']);
            $productTags = array_map(function($t){
                return array('name' => $t);
            }, $productTags);
            $data['tags'] = $productTags;
        }

       if($validCode) {

            $saveProduct = json_decode($addProduct($data), true);
            if(isset($saveProduct['error'])) $error = $saveProduct['message'];  
       }

       if(!$error)  header("Location: " . $link . "/" . $products_page . "?id=1");

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= get_site_icon_url() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" integrity="sha512-yFjZbTYRCJodnuyGlsKamNE/LlEaEAxSUDe5+u61mV8zzqJVFOH7TnULE2/PP/l5vKWpUNnF4VGVkXh3MjgLsg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/addproduct.php"?>">
    <script src="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/js/addproduct.js"?>" defer></script>
    <title>Add Product</title>
</head>
<body>
    <header>
        <?php if(get_option("wp_smart_products_logo_url") !== null): ?>
            <div>
                <img src="<?= get_option("wp_smart_products_logo_url")?>"/>
            </div>
        <?php endif;?>
        <a href="<?= $products_page?>?id=1">Go back to products</a>
    </header>
    <?php if($validCode): ?>
        <?php if($error):?>
            <div id="errors"><?= $error?></div>
        <?php endif;?>
        <form enctype="multipart/form-data" action="" method="post" enctype='multipart/form-data' id="addeditproduct-form">

            <!-- Name Input field -->
            <div id="title-price" class="flex-fields">
                <div>
                    <label for="product-name" class="label-block">Product Name</label>
                    <input type="text" name="product-name" id="name" value="<?php if(isset($_POST['product-name'])) echo htmlentities($_POST['product-name'])?>">
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
                        <input type="text" name="product-regular-price" id="regular-price" value="<?php if(isset($_POST['product-regular-price'])) echo htmlentities($_POST['product-regular-price']) ?>">
                    </div>

                    <div class="flex-container-vertical">
                        <label for="product-sale-price" class="padding-left">Sale Price</label>
                        <input type="text" name="product-sale-price" id="sale-price" value="<?php if(isset($_POST['product-sale-price'])) echo htmlentities($_POST['product-sale-price']) ?>">
                    </div>

                    <div class="flex-container-vertical">
                        <label for="tax-class">Tax Class</label>
                        <select name="tax-class" id="tax-class">
                            <option value="" disabled selected>Select Tax Class</option>
                            <?php 
                            
                                foreach($taxClassData as $taxClass){ ?>
                                    <option value="<?= $taxClass["slug"]?>"><?= $taxClass['name']?></option>
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
                        <input type="text" name="product-sku" id="sku-input" value="<?php if(isset($_POST['product-sku'])) echo htmlentities($_POST['product-sku']) ?>">
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
                        <input type="number" value="0" name="stock-quantity" value="<?php if(isset($_POST['stock-quantity'])) echo htmlentities($_POST['stock-quantity']) ?>">
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
                        <label for="weight" class="">Weight</label>
                        <input type="text" name="weight" value="<?php if(isset($_POST['weight'])) echo htmlentities($_POST['weight']) ?>">
                    </div>

                    <div id="dimensions" class="flex-container">
                        <label for="">Dimensions</label>
                        <input type="text" name="length" placeholder="Length" value="<?php if(isset($_POST['length'])) echo htmlentities($_POST['length']) ?>">
                        <input type="text" name="width" placeholder="Width" value="<?php if(isset($_POST['width'])) echo htmlentities($_POST['width']) ?>">
                        <input type="text" name="height" placeholder="Height" value="<?php if(isset($_POST['height'])) echo htmlentities($_POST['height']) ?>">
                    </div>

                    <?php if(count($shippingClasses) > 0):?>
                        <div id="shipping-class-container" class="flex-container between">
                            <label for="shipping-class">Shipping Class</label>
                            <select name="shipping-class" id="shipping-class">
                                <option value="">No Shipping Class</option>
                                <?php foreach($shippingClasses as $shippingClass):?>
                                    <option value="<?= $shippingClass['slug']?>"><?= $shippingClass['name']?></option>
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
                                <option value="<?= $category['id']?>" id="<?= $category['id']?>"><?= $category['name']?></option>
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
            <input type="hidden" id="php-categories-data" value='<?php echo json_encode($categories)?>'>
        </form>
    <?php else: ?>
        <h1>Please enter the required codes in the WP Smart Commerce plugin.</h1>
    <?php endif; ?>

</body>
<?php wp_footer(); ?>
</html>