<?php

/**
 * Template Name: Addproduct
 */

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

<?php
require __DIR__ . "/woocommerce-api.php";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";

$link .= "://" . $_SERVER['HTTP_HOST'];
if(isset($_SERVER['HTTP_REFERER'])){
    $previous_page = $_SERVER['HTTP_REFERER'];
    $from_products = preg_match("/products\/\?id=[1-9]{1,5}/", $previous_page);
    $from_self = preg_match("/addproduct/", $previous_page);

    if($from_products || $from_self){
        $categoriesData = json_decode($listCategories(), true);
        $categories = $categoriesData['data'];

    }
    else {
        // Add product to test on local
        header("Location: " . $link . /*"/product" .*/ "/access");
        exit;
    }
}
else {
    // Add product to test on local
    header("Location: " . $link ./* "/product" . */ "/access");
    exit;
}

?>

<?php
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

        if($_POST['product-categories']){
            $categoriesArray = [];
            $categoriesSelected = explode("%", $_POST['product-categories']);
            for($i = 0; $i < count($categoriesSelected); $i++){
                $categoriesArray[$i] = array(
                    'id' => $categoriesSelected[$i]
                );
            }
            $data['categories'] = $categoriesArray;
        }

        if($_POST['product-tags']){
            $tagsArray = [];
            $tags = explode("%", $_POST['product-tags']);
            for($i = 0; $i < count($tags); $i++){
                $tagsArray[$i] = array(
                    'name' => $tags[$i]
                );
            }
            $data['tags'] = $tagsArray;
        }

        

        $imageFolder = dirname(__FILE__) . "/images";

        if($_FILES['product-image']['name']){
            $serverImagePath = $imageFolder . "/" . $_FILES['product-image']['name'];
        
            move_uploaded_file($_FILES['product-image']['tmp_name'], $serverImagePath);
            // Add product to test on local
            $image = "https://" . $_SERVER['HTTP_HOST'] . /* "/product" . */ "/wp-content/plugins/private/templates/images/" . $_FILES['product-image']['name'];

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
        // Add product to test on local
        header("Location: " . $link . /* "product/" . */ "/products?id=1");
    }

?>
<body>
    <header>
        <a href="/products?id=1">Go back to products</a>
    </header>
    <form enctype="multipart/form-data" action="" method="post">
        <div id="title-price">
            <!-- This is the name field input -->
            <span>
                <label for="product-name" >Name</label>
                <input type="text" name="product-name" id="name" required>
            </span>
            <span>
                <label for="product-regular-price">Regular Price</label>
                <input type="text" name="product-regular-price" id="regular-price">
            </span>
            <span>
                <label for="product-sale-price">Sale Price</label>
                <input type="text" name="product-sale-price" id="sale-price">
            </span>
        </div>

        <div id="product-settings">
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
                <label for="product-virtual" class="inline">Virtual</label>
                <input type="checkbox" name="product-virtual" id="virtual">
            </span>
            <span>
                <label for="product-downloadable" class="inline">Downloadable</label>
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
            <label for="product-description">Description</label>
            <textarea name="product-description" cols="30" rows="10" id="description"></textarea>
        </div>

        <div id="product-short-description">
            <label for="product-short-description"> Short Description</label>
            <textarea name="product-short-description" id="short-description" cols="30" rows="10"></textarea>
        </div>

        <div id="categories-tags">
            <div id="choose-category">
                <label for="product-category">Choose Category</label>
                <select name="product-category" id="category">
                    <option value="" disabled selected>Select Category</option>
                    <?php
                        foreach($categories as $category){?>
                            <option value="<?= $category['name'], $category['id']?>"><?= $category['name']?></option>
                       <?php }
                    ?>
                </select>
                <ul id="category-items"></ul>
            </div>
            <div id="tag-container">
                <div id="tag-form">
                    <label for="product-tags">Add Tags</label>
                    <span>
                        <input type="text" id="tag-input">
                        <button type="button" id="tag-button">Add</button>
                    </span>
                </div>
                <ul id="tag-items"></ul>
            </div>
        </div>

        <div id="sku">
            <label for="product-sku">SKU</label>
            <input type="text" name="product-sku" id="sku-input">
        </div>

        <div id="btn-save">
            <input type="submit" id="save-btn" value="Save">
        </div>
        <input type="hidden" name="product-categories" id="hidden-categories">
        <input type="hidden" name="product-tags" id="hidden-tags">
    </form>
    <div id="errors"></div>
</body>
</html>