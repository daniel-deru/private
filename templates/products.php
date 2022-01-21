<?php 
/**
 * 
 * 
 *  Template Name: Products
 */
require __DIR__ . "/woocommerce-api.php";
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
    $from_login = preg_match("/access/", $previous_page);
    $from_self = preg_match("/products\/\?id=[1-9]{1,5}/", $previous_page);
    $from_edit = preg_match("/editproduct\/\?id=[1-9]{1,5}/", $previous_page);
    $from_add = preg_match("/addproduct/", $previous_page);

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
        header("Location: " . $link . /* "/product" .*/ "/access");
        exit;
    }
}
else {
    // Remove the product part
    header("Location: " . $link . /* "/product" .*/ "/access");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/products.css">
    <title>Products</title>
</head>
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
        <a href="<?php echo $link . /*"/product" .*/ "/addproduct"?>">Add New Product</a>
    </header>
    <main id="product-grid">
        <?php
        
                if(isset($products)){
                    foreach($products as $product){?>

                        <div class="product-container">
                            <img src="<?php echo $product['images'][0]['src']?>" alt="" class="product-image">
                            <div class="title"><?php echo $product['name']?></div>
                            <div class="price">R <?php echo $product['regular_price']?></div>
                            <div class="SKU-categories">
                                <span class="SKU"><b>SKU: </b><?php echo displayData($product['sku'])?></span>
                                <span class="Categories"><b>Categories: </b><?php echo $product['categories'][0]['name']?></span>
                            </div>
                            <a href="editproduct?id=<?= $product['id']?>" class="edit-product">Edit Product</a>
                        </div>
                   <?php }
                }
        
        ?>
    </main>
    <div id="pagination">
        <div>Showing page <?=$page?> of 
            <?= $productsHeaders['X-WP-TotalPages']?>
        </div>
            <ul id="page-list">
                <?php 
                    for($i = 1; $i <= $productsHeaders['X-WP-TotalPages']; $i++){?>
                        <li class="page">
                            <a href="products?id=<?= $i ?>"><?php echo $i ?></a>
                        </li>
                <?php
                    }
                ?>
            </ul>
    </div>
</body>
</html>

<style>
    :root {
    --main-green: #21759B;
    --main-light-green: #21759B;
    --gradient: #21759B;
    /* --main-green: rgba(4,65,28,1);
    --main-light-green: rgba(53,205,22,1);
    --gradient: linear-gradient(90deg, rgba(53,205,22,1) 0%, rgba(4,65,28,1) 100%); */
}

* {
    margin: 0;
    padding: 0;
}

header {
    height: 15vh;
    background: var(--gradient);
    display: flex;
    align-items: center;
    justify-content: center;
}

header > * {
    margin: 1em;
}

header select {
    font-size: 1.5em;
    outline: none;
    color: white;
    padding: 10px;
    border-radius: 5px;
    background-color: transparent;
    border: 2px solid white;
}

select option {
    background-color: var(--main-green);
    color: white;
}


header a {
    font-size: 1.5em;
    outline: none;
    color: white;
    padding: 10px;
    border-radius: 5px;
    border: 2px solid white;
    background: transparent;
    text-decoration: none;
    font-family: sans-serif;
}

#product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    max-width: 80%;
    grid-gap: 2rem;
    margin: auto;
    margin-top: 5rem;
    font-family: sans-serif;
}
.product-image {
    max-width: 300px;
    max-height: 300px;
    width: 300px;
    height: 300px;
}

.product-container {
    box-shadow: 0px 0px 5px 3px hsla(90, 0%, 0%, 0.2);
    max-width: 300px;

    margin: auto;
    padding: 1em;
    border-radius: 10px;
}

.product-container > * {
    margin-bottom: 1em;
}

.product-container .title {
    font-weight: bolder;
    font-size: 1.2em;
}

.product-container a {
    display: block;
    margin: auto;
    font-family: sans-serif;
    font-size: 1.2em;
    padding: 10px 16px;
    border-radius: 5px;
    border: none;
    background: var(--gradient);
    color: white;
    text-decoration: none;
    font-family: sans-serif;
    text-align: center;
}

#pagination {
    text-align: center;
    margin: 3rem auto;
    font-size: 1.5em;
    font-family: sans-serif;
}

#pagination ul {
    justify-content: center;
    display: flex;
    list-style-type: none;
}

#pagination ul li {
    padding: 10px;
}

#pagination a {
    text-decoration: none;
    color: black;
    border: 2px solid black;
    padding: 5px;
    border-radius: 5px;
}

#pagination a:active {
    color: var(--main-green);
    border: 2px solid var(--main-green);
}

#pagination a:visited {
    color: var(--main-green);
    border: 2px solid var(--main-green);
}

</style>