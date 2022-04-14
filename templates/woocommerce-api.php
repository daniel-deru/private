<?php
require __DIR__ . '/vendor/autoload.php';

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$host = "https";
else $host = "http";

$host .= "://" . $_SERVER['HTTP_HOST'];

use Automattic\WooCommerce\Client;
$key = get_option('wp_smart_products_consumer_key');
$secret = get_option('wp_smart_products_consumer_secret');
$woocommerce = new Client(
    $host, 
    $key, 
    $secret,
    [
        'version' => 'wc/v3',
        'timeout' => 120,
        'wp_api' => true
    ]
);

?>

<?php
 
 $smt_smart_commerce_pro_getHeaders = function() use ($woocommerce){
    $lastResponse = $woocommerce->http->getResponse();
    return $lastResponse->getHeaders();
 };

 $smt_smart_commerce_pro_listCategories = function ($page=1) use ($woocommerce, $smt_smart_commerce_pro_getHeaders){
    $data = array(
        'data' => $woocommerce->get("products/categories", array(
            'per_page' => 100,
            'page' => $page)),
        'headers' => $smt_smart_commerce_pro_getHeaders());

    $data['headers'] = $smt_smart_commerce_pro_getHeaders();

    return json_encode($data);
 };

 $smt_smart_commerce_pro_listProducts = function ($page=1) use ($woocommerce, $smt_smart_commerce_pro_getHeaders){
    $data = array(
        'data'=>$woocommerce->get("products", array(
            "per_page" => 20,
            "page" => $page)),
        'headers' => $smt_smart_commerce_pro_getHeaders());

    return json_encode($data);
 };

$smt_smart_commerce_pro_addProduct = function($data) use ($woocommerce){

    if($data['name']){
        try{
            $request = $woocommerce->post('products', $data);
        } 
        catch (Exception $e){
            return json_encode(array("error" => true, 'message' => $e->getMessage()));
        }
        return json_encode(($request));

    }

};

$smt_smart_commerce_pro_getProduct = function($id) use ($woocommerce){
    if($id){
        $data = $woocommerce->get('products/' . $id);
        return json_encode($data);
    }
};

$smt_smart_commerce_pro_updateProduct = function($id, $data) use ($woocommerce){
    if($id && $data){
        try {
            $data = $woocommerce->put('products/' . $id, $data);
            return json_encode($data);
        } 
        catch (Exception $e){
            return json_encode(array('error' => true, "message" => $e->getMessage()));
        }

    }
};

$smt_smart_commerce_pro_deleteProduct = function($id) use ($woocommerce){
    if($id){
        $data = $woocommerce->delete("products/" . $id);
        return json_encode($data);
    }
};

$smt_smart_commerce_pro_units = function() use ($woocommerce){
    $data = $woocommerce->get("settings/products");
    return json_encode($data);
};

$smt_smart_commerce_pro_createCategory = function($data) use ($woocommerce) {
    if($data){
        try {
            $data = $woocommerce->post('products/categories', $data);
            return json_encode($data);
        }
        catch (Exception $e) {
            return json_encode(array("error" => true, 'message' => $e->getMessage()));
        }
    }

};

$smt_smart_commerce_pro_getTaxClasses = function() use ($woocommerce) {
    try {
        $data = $woocommerce->get("taxes/classes");
        return json_encode($data);
    } catch (Exception $e){
        return json_encode(array('error' => true, 'message' => $e->getMessage()));
    }
};

$smt_smart_commerce_pro_getShippingClasses = function() use ($woocommerce){
    try {
        $data = $woocommerce->get("products/shipping_classes");
        return json_encode($data);
    } catch (Exception $e) {
        return json_encode(array('error' => true, 'message' => $e->getMessage()));
    }
};

 ?>


