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
    if(isset($_POST['product-name'])){

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
        if(isset($_POST['weigth'])){
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
        <div id="title-price" class="inline-fields">
            <!-- This is the name field input -->
            <span>
                <label for="product-name" ><h4>Name</h4></label>
                <input type="text" name="product-name" id="name" required>
            </span>
            <span>
                <label for="product-regular-price"><h4>Regular Price</h4></label>
                <input type="text" name="product-regular-price" id="regular-price">
            </span>
            <span>
                <label for="product-sale-price"><h4>Sale Price</h4></label>
                <input type="text" name="product-sale-price" id="sale-price">
            </span>
        </div>

        <div id="product-settings" class="inline-fields">
            <span >
                <label for="product-type" ></label>
                <select name="product-type" id="product-type">
                    <option value="" selected disabled>Product Type</option>
                    <option value="simple">Simple Product</option>
                    <option value="grouped">Grouped Product</option>
                    <option value="external">External/Affiliate Product</option>
                    <option value="variable">Variable Product</option>
                </select>
            </span>
            <span >
                <label for="product-virtual" class="inline"><h4>Virtual</h4></label>
                <input type="checkbox" name="product-virtual" id="virtual">
            </span>
            <span>
                <label for="product-downloadable" class="inline"><h4>Downloadable</h4></label>
                <input type="checkbox" name="product-downloadable" id="downloadable">
            </span>
        </div>

        <div id="product-image">
        <label class="custom-file-upload">
            <input type="file" id="image" name="product-image"/>
            Custom Upload
        </label>
            <div id="image-preview">
                <img src="" alt="" id="img">
            </div>
        </div>

        <div id="product-description">
            <label for="product-description"><h4>Description</h4></label>
            <textarea name="product-description" cols="30" rows="10" id="description"></textarea>
        </div>

        <div id="product-short-description">
            <label for="product-short-description"><h4>Short Description</h4></label>
            <textarea name="product-short-description" id="short-description" cols="30" rows="10"></textarea>
        </div>

        <div id="sku">
            <label for="product-sku"><h4>SKU</h4></label>
            <input type="text" name="product-sku" id="sku-input">
        </div>

        <div id=weight-dimensions >
            <div class="inline-fields">
                <div>
                    <label for="length">
                        <h4>Length (<?= $dimensionsUnit?>)</h4>
                    </label>
                    <input type="text" name="length">
                </div>
                <div>
                    <label for="length">
                        <h4>Width (<?= $dimensionsUnit?>)</h4>
                    </label>
                    <input type="text" name="width">
                </div>
                <div>
                    <label for="length">
                        <h4>Height (<?= $dimensionsUnit?>)</h4>
                    </label>
                    <input type="text" name="height">
                </div>
                <div>
                    <label for="length">
                        <h4>Weight (<?= $weightUnit?>)</h4>
                    </label>
                    <input type="text" name="weight">
                </div>
            </div>

        </div>

       <div id="categories-tags-container" class="inline-fields">

            <div id="categories">
                <h4>Choose Categories</h4>
                <div id="new-categories">
                    <input type="text" name="category" placeholder="Make a new category for your product">
                    <button type="button">Add</button>
                </div>
                <select name="parent-category" id="parent-categories">
                    <option value="" selected disabled>None</option>
                    <?php
                        foreach($categories as $category){?>
                            <option value="<?= $category['name']?>" id="<?= $category['id']?>"><?= $category['name']?></option>
                       <?php }
                    
                    ?>
                </select>
                <div id="categories-checkboxes">

                </div>
            </div>

            <div id="tags">
                <h4>Add Tags</h4>
                <div id="new-tags">
                    <input type="text" name="new-tag">
                    <button type="button">Add</button>
                </div>
                <div id="tags-container">

                </div>
            </div>

       </div>

       

        <div id="btn-save">
            <input type="submit" id="save-btn" value="Save">
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