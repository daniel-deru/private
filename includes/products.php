<?php 


function get_products(){
    $products_array = [];

    $query_args = [
        'post_type' => 'product',
        'posts_per_page' => -1
    ];

    $products = new WP_Query($query_args);

    if($products->have_posts()){
        while($products->have_posts()){
            $products->the_post();
            global $product;
            array_push($products_array, $product);
        }
    }

    return $products_array;
}