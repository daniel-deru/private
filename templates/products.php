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
    <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/products.css"?>">
    <title>Products</title>
</head>

<?php

require __DIR__ . "/woocommerce-api.php";

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
    if($from_login || $from_self || $from_edit || $from_add){
        if(isset($_GET['id'])){
            $page = intval($_GET['id']);
        }
        
        $productsData = json_decode($listProducts($page), true);
        $categoriesData = json_decode($listCategories(), true);

        $products = $productsData['data'];
        $categories = $categoriesData['data'];
        $productsHeaders = $productsData['headers'];

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
        <select name="categories" id="category-select">
            <?php
                if(isset($categories)){
                    foreach($categories as $category){?>

                        <option value="<?php echo $category['slug']?>"><?php echo $category['name']?></option>
                    <?php }
                }
            ?>
        </select>
        <!-- Add the product for testing on local -->
        <a href="<?php echo $link . "/" . $add_page?>">Add New Product</a>
    </header>
    <main id="product-grid">
        <?php
        
                if(isset($products)){
                    foreach($products as $product){
                        $categoryList = implode(" ", array_map(function ($category){ return $category['name'];}, $product['categories']));
                        ?>
                         
                        <div class="product-container <?= $categoryList?>">
                            <img src="<?php echo $product['images'][0]['src']?>" alt="" class="product-image">
                            <div class="title"><?php echo $product['name']?></div>
                            <div class="price">R <?php echo $product['regular_price']?></div>
                            <div class="SKU-categories">
                                <span class="SKU"><b>SKU: </b><?php echo displayData($product['sku'])?></span>
                                <span class="Categories"><b>Categories: </b><?php echo $product["categories"][0]['name']?></span>
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
</body>
</html>