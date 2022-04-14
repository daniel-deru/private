<?php 
/**
 * 
 *  Template Name: Products
 *  Template Post Type: page
 * 
 */

require __DIR__ . "/woocommerce-api.php";
require  __DIR__ . "/../includes/helpers.php";
// require dirname(plugin_dir_url(__FILE__), 1) . "/includes/helpers.php";

$login_page = "wp-smart-login";
$products_page = "wp-smart-products";
$edit_page = "wp-smart-edit-product";
$add_page = "wp-smart-add-product";

function smt_smart_commerce_pro_displayData($data){
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
    $validCodes = smt_smart_commerce_pro_checkCode();
    if($from_login || $from_self || $from_edit || $from_add){
        if(isset($_GET['id'])){
            $page = intval($_GET['id']);
        }
        
        if($validCodes){
            $productsData = json_decode($smt_smart_commerce_pro_listProducts($page), true);
            $categoriesData = json_decode($smt_smart_commerce_pro_listCategories(), true);
    
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= get_site_icon_url() ?>">
    <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/products.php"?>">
    <script src="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/js/products.js"?>" defer></script>
    <title>Products</title>
</head>
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

                                    if($product['regular_price']):
                                        $full_price = explode(".", $product['regular_price']);

                                        $integer_price = str_split($full_price[0]);

                                        $price_array = array_reverse($integer_price);

                                        
                                       $price_array = array_map(function ($index, $value){
                                            // return ($index + 1) % 3;
                                            if(($index + 1) % 3 == 0) return "  " . $value;
                                            return $value;
                                        }, array_keys($price_array) ,$price_array);
                                        
                                        $integer_price_str = implode("", array_reverse($price_array));
                                        $final_price = count($full_price) > 1 ? $integer_price_str . "." . $full_price[1] : $integer_price_str;
                                        
                                        
                                ?>
                                    <div class="price"> <?php echo $product['price_html']?></div>
                                <?php endif;?>
                                
                                <div class="SKU-categories">
                                    <div class="SKU"><b>SKU: </b><?php echo smt_smart_commerce_pro_displayData($product['sku'])?></div>
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