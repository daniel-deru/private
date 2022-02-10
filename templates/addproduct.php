<?php

/**
 * Template Name: Addproduct
 */


// Get the woocommerce api functions
require __DIR__ . "/woocommerce-api.php";
// get the correct protocol
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";
$link .= "://" . $_SERVER['HTTP_HOST'];

// Global variables for the pages
$login_page = "wp-smart-login";
$products_page = "wp-smart-products";
$add_page = "wp-smart-add-product";

// Check where the request for the current page is coming from
if(isset($_SERVER['HTTP_REFERER'])){
    $previous_page = $_SERVER['HTTP_REFERER'];
    $from_products = preg_match("/" . $products_page . "\/\?id=[1-9]{1,5}/", $previous_page);
    $from_self = preg_match("/" . $add_page ."/", $previous_page);

    if($from_products || $from_self){
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

        if(isset($_POST['category']) && $_POST['category']){
            $categortArray = array(
                'name' => $_POST['category']
            );

            $data['name'] = $_POST['category'];

            if(isset($_POST['parent-category']) && $_POST['parent-category']){
                $categortArray['parent'] = $_POST['parent-category'];
            }

            $newCategory = json_decode($createCategory($categortArray), true);
        }
        
        


        $data = [];
        $data['name'] = $_POST['product-name'];

        if(isset($_POST['product-regular-price'])){
            $data['regular_price'] = $_POST['product-regular-price'];
        }

        if(isset($_POST['product-sale-price'])){
            $data['sale_price'] = $_POST['product-sale-price'];
        }

        if(isset($_POST['product-type'])){
            $data['type'] = $_POST['product-type'];
        }

        if(isset($_POST['product-virtual'])){
            $data['virtual'] = true;
        }

        if(isset($_POST['product-downloadable'])){
            $data['downloadable'] = true;
        }

        if(isset($_POST['product-description'])){
            $data['description'] = $_POST['product-description'];
        }

        if(isset($_POST['product-short-description'])){
            $data['short_description'] = $_POST['product-short-description'];
        }

        if(isset($_POST['product-sku'])){
            $data['sku'] = $_POST['product-sku'];
        }

        if(isset($_POST['manage-stock'])){
            $data['manage_stock'] = true;
        }

        if(isset($_POST['stock-quantity'])){
            $data['stock-quantity'] = $_POST['stock-quantity'];
        }

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
        if(isset($_POST['weight'])){
            $data["weight"] = $_POST["weight"];
        }
        

        $imageFolder = dirname(__FILE__) . "/images";

        if($_FILES['product-image']['name']){
            $serverImagePath = $imageFolder . "/" . $_FILES['product-image']['name'];
        
            move_uploaded_file($_FILES['product-image']['tmp_name'], $serverImagePath);

            $image = "https://" . $_SERVER['HTTP_HOST'] .  "/wp-content/plugins/private/templates/images/" . $_FILES['product-image']['name'];

            $data['images'] = array(
                array(
                    'src' => $image
                )
            );

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
        
        $saveProduct = json_decode($addProduct($data), true);

        $files = glob($imageFolder . "/*");
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
        header("Location: " . $link . "/" . $products_page . "?id=1");
    } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/addproduct.css"?>">
    <script src="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/js/addproduct.js"?>" defer></script>
    <title>Add Product</title>
</head>
<body>
    <header>
        <a href="<?= $products_page?>?id=1">Go back to products</a>
    </header>
    <form enctype="multipart/form-data" action="" method="post">

        <!-- Name Input field -->
        <div id="title-price" class="flex-fields">
            <div>
                <label for="product-name" class="label-block">Name</label>
                <input type="text" name="product-name" id="name">
            </div>
        </div>

        <!-- Image Input Field -->
        <div id="product-image">
            <label class="custom-file-upload">
                <input type="file" id="image" name="product-image"/>
                Upload Image
            </label>
            <div id="image-preview">
                <img src="" alt="" id="img">
            </div>
        </div>

        <!-- Product Long Description -->
        <div id="product-description">
            <label for="product-description" class="label-block">Long Description</label>
            <textarea name="product-description" cols="30" rows="10" id="description"></textarea>
        </div>

        <!-- Product Data -->
        <div id="product-settings" class="flex-fields">
            <label class="label-block">Product Data</label>
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
                    <label for="product-virtual" class="inline">Virtual</label>
                </div>
    
                <div>
                    <input type="checkbox" name="product-downloadable" id="downloadable">
                    <label for="product-downloadable" class="inline">Downloadable</label>
                </div>
            </div>
        </div>

        <!-- General -->
        <div id="general">
            <label class="label-block">General</label>
            <div class="flex-container">
                <div class="flex-container">
                    <label for="product-regular-price" class="">Regular Price</label>
                    <input type="text" name="product-regular-price" id="regular-price">
                </div>

                <div class="flex-container">
                    <label for="product-sale-price" class="padding-left">Sale Price</label>
                    <input type="text" name="product-sale-price" id="sale-price">
                </div>
            </div>

        </div>

        <!-- Inventory -->
        <div id="inventory">
            <label class="label-block">Inventory</label>
            <div class="inventory-container">
                <div id="sku" class="flex-container">
                    <label for="product-sku" class="">SKU</label>
                    <input type="text" name="product-sku" id="sku-input">
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
                    <input type="number" value="0" name="stock-quantity">
                </div>
            </div>

        </div>

        <!-- Shipping -->
        <div id="shipping">
            <label class="label-block">Shipping</label>
            <div>
                <div id="weight" class="flex-container between">
                    <label for="weight" class="">Weight</label>
                    <input type="text" name="weight">
                </div>
                <div id="dimensions" class="flex-container">
                    <label for="">Dimensions</label>
                    <input type="text" name="length" placeholder="Length">
                    <input type="text" name="width" placeholder="Width">
                    <input type="text" name="height" placeholder="Height">
                </div>
            </div>
        </div>

        <div id="product-short-description">
            <label for="product-short-description" class="label-block">Short Description</label>
            <textarea name="product-short-description" id="short-description" cols="30" rows="10"></textarea>
        </div>

       <div id="categories-tags-container" class="flex-container around">

            <div id="categories">
                <label class="label-block">Choose Categories</label>
                <div id="new-categories">
                    <input type="text" name="category" placeholder="Make a new category" id="category-input">
                </div>
                <select name="parent-category" id="parent-categories">
                    <option value="" selected >None</option>
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
            <label class="label-block">Add Tags</label>
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
        </div>
        <?php //This will get the data from the form to submit to the api?>
        <input type="hidden" name="product-categories" id="hidden-categories">
        <input type="hidden" name="product-tags" id="hidden-tags">
        <?php // This will pass the data to javascript to handle the displaying of the categories?>
        <input type="hidden" id="php-categories-data" value='<?php echo json_encode($categories)?>'>
    </form>
    <div id="errors"></div>

</body>
</html>