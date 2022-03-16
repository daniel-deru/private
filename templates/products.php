<?php 
/**
 * 
 * 
 *  Template Name: Products
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/products.php"?>">
    <script src="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/js/products.js"?>" defer></script>
    <title>Products</title>
</head>

<?php

require __DIR__ . "/woocommerce-api.php";
require_once dirname(__FILE__, 1) . "/includes/helpers.php";

$login_page = "wp-smart-login";
$products_page = "wp-smart-products";
$edit_page = "wp-smart-edit-product";
$add_page = "wp-smart-add-product";

function displayData($data){
    if($data){
        return $data;
    }
    else {
        return "Not Set";
    }
}

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";

$link .= "://" . $_SERVER['HTTP_HOST'];


global $categories;
if(isset($_SERVER['HTTP_REFERER'])){
    
    $previous_page = $_SERVER['HTTP_REFERER'];
    $from_login = preg_match("/" . $login_page . "/", $previous_page);
    $from_self = preg_match("/" . $products_page ."\/\?id=[1-9]{1,5}/", $previous_page);
    $from_edit = preg_match("/" . $edit_page . "\/\?id=[1-9]{1,5}/", $previous_page);
    $from_add = preg_match("/" . $add_page . "/", $previous_page);

    $page = 1;
    $validCodes = checkCode();
    if($from_login || $from_self || $from_edit || $from_add){
        if(isset($_GET['id'])){
            $page = intval($_GET['id']);
        }
        if($validCodes){
            $productsData = json_decode($listProducts($page), true);
            $categoriesData = json_decode($listCategories(), true);
    
            $products = $productsData['data'];
            $categories = $categoriesData['data'];
            $productsHeaders = $productsData['headers'];
        }



    }
    else {
            // Remove the product part
        header("Location: " . $link . "/" . $login_page);
        exit;
    }
}
else {
    // Remove the product part
    header("Location: " . $link . "/" . $login_page);
    exit;
}

?>
<body>
    <header>
        
        <?php if(get_option("wp_smart_products_logo_url") !== null): ?>
            <div>
                <img src="<?= get_option("wp_smart_products_logo_url")?>"/>
            </div>
        <?php endif;?>
        
        <div id="filter-container">
            <div>
                <input type="text" name="name" placeholder="Product Name" id="filter-name">
            </div>
            <div>
                <select name="more-less" id="filter-range">
                    <option value="more" selected>More Than</option>
                    <option value="less">Less Than</option>
                </select>
                <input type="text" placeholder="Price" id="filter-price">
            </div>
            <div>
                <button id="filter">Run Filter</button>
            </div>
            <div>
                <button id="reset">Reset Filter</button>
            </div>
        </div>
        <a href="<?php echo $link . "/" . $add_page?>">Add New Product</a>
    </header>
    <section>

    </section>
    <?php if($validCodes): ?>
        <main id="product-grid">
            <?php
            
                    if(isset($products)){
                        foreach($products as $product){
                            $categoryList = implode(" ", array_map(function ($category){ return $category['name'];}, $product['categories']));
                            ?>
                            
                            <div class="product-container <?= $categoryList?>" data-name="<?= $product['name']?>" data-price="<?= $product['regular_price']?>">
                                <img src="<?php echo $product['images'][0]['src']?>" alt="" class="product-image">
                                <div class="title"><?php echo $product['name']?></div>
                                <?php 
                                    if($product['regular_price']){
                                        ?>
                                        <div class="price">R <?php echo $product['regular_price']?></div>
                                        <?php
                                    }
                                
                                ?>
                                
                                <div class="SKU-categories">
                                    <div class="SKU"><b>SKU: </b><?php echo displayData($product['sku'])?></div>
                                    <div class="Categories"><b>Categories: </b><?php echo $categoryList?></div>
                                </div>
                                <a href="<?= $edit_page?>?id=<?= $product['id']?>" class="edit-product">Edit Product</a>
                            </div>
                    <?php }
                    }
            
            ?>
        </main>
        <div id="pagination">
            <div>Showing page <?=$page?> of 
                <?= $productsHeaders['x-wp-totalpages']?>
            </div>
                <ul id="page-list">
                    <?php 
                        for($i = 1; $i <= $productsHeaders['x-wp-totalpages']; $i++){?>
                            <li class="page">
                                <a href="<?= $products_page?>?id=<?= $i ?>"><?php echo $i ?></a>
                            </li>
                    <?php
                        }
                    ?>
                </ul>
        </div>
    <?php else: ?>
        <h1>Please enter the required codes in the WP Smart Commerce plugin.</h1>

    <?php endif;?>
</body>
</html>