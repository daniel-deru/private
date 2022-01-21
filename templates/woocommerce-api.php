<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'http://localhost/product', 
    'ck_f080b780b60ceb0c57b979bc787a623ab1aa9acb', 
    'cs_567fe8a081c27d16236154469f9d7cd703d09061',
    [
        'version' => 'wc/v3',
    ]
);

?>

<?php
 
 $getHeaders = function() use ($woocommerce){
    $lastResponse = $woocommerce->http->getResponse();
    return $lastResponse->getHeaders();
 };

 $listCategories = function ($page=1) use ($woocommerce, $getHeaders){
    $data = array(
        'data' => $woocommerce->get("products/categories", array(
            'per_page' => 100,
            'page' => $page)),
        'headers' => $getHeaders());

    $data['headers'] = $getHeaders();

    return json_encode($data);
 };

 $listProducts = function ($page=1) use ($woocommerce, $getHeaders){
    $data = array(
        'data'=>$woocommerce->get("products", array(
            "per_page" => 4,
            "page" => $page)),
        'headers' => $getHeaders());

    
    return json_encode($data);
 };

$addProduct = function($data) use ($woocommerce){

    if($data['name']){
        $request = $woocommerce->post('products', $data);

        return json_encode(($request));
    }

};

$getProduct = function($id) use ($woocommerce){
    if($id){
        $data = $woocommerce->get('products/' . $id);
        return json_encode($data);
    }
};

$updateProduct = function($id, $data) use ($woocommerce){
    if($id && $data){
        $data = $woocommerce->put('products/' . $id, $data);
        return json_encode($data);
    }
}

 ?>


