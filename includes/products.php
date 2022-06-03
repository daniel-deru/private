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

function create_product($data){
    // Set the product type initially
    if(isset($data['type'])){
        switch($data['type']){
            case 'grouped':
                $product = new WC_Product_Grouped();
                break;
            case 'external':
                $product = new WC_Product_External();
                break;
            case 'variable':
                $product = new WC_Product_Variable();
                break;
            default:
                $product = new WC_Product();
        }
    } else $product = new WC_Product();

    // Set the name
    if(isset($data['name'])) $product->set_name($data['name']);

    // Set the product images and gallery images
    if(isset($data['images']) && count($data['images']) > 0) {
        $product->set_image_id($data['images'][0]);
        if(count($data['images']) > 1) $product->set_gallery_image_ids($data['images']);
    }

    // Set the long description
    if(isset($data['description'])) $product->set_description($data['description']);

    // Set if the product is virtual or not
    if(isset($data['virtual'])){
        $virtual = false;
        if($data['virtual'] == '1') $virtual = true;
        $product->set_virtual($virtual);
    }

    // Set if the product is downloadable
    if(isset($data['downloadable'])){
        $downloadable = false;
        if($data['downloadable'] == '1') $downloadable = true;
        $product->set_downloadable($downloadable);
    }

    // Set the regular price
    if(isset($data['regular_price'])) $product->set_regular_price($data['regular_price']);

    // Set the sale price
    if(isset($data['sale_price'])) $product->set_sale_price($data['sale_price']);

    // Set the tax class
    if(isset($data['tax_class'])) $product->set_tax_class($data['tax_class']);

    // Set the sku
    if(isset($data['sku'])) $product->set_sku($data['sku']);

    // Set if the stock should be counted or not
    if(isset($data['manage_stock'])){
        $manage_stock = false;
        if($data['manage_stock'] == '1') $manage_stock = true;
        $product->set_manage_stock($manage_stock);
    }

    // Set if the stock quantity
    if(isset($data['stock-quantity'])) $product->set_stock_quantity($data['stock-quantity']);

    // Set the stock status
    if(isset($data['stock_status'])) $product->set_stock_status($data['stock_status']);

    // Set the shipping dimensions
    if(isset($data['dimensions'])){
        $product->set_length($data['dimensions']['length']);
        $product->set_height($data['dimensions']['width']);
        $product->set_width($data['dimensions']['height']);
    }

    // Set the product weight
    if(isset($data['weight'])) $product->set_weight($data['weight']);

    // set the shipping class
    if(isset($data['shipping_class'])) $product->set_shipping_class_id($data['shipping_class']);

    // Set the short description
    if(isset($data['short_description'])) $product->set_short_description($data['short_description']);

    // Set the product categories
    if(isset($data['categories'])){
        $category_ids = array_map(function($id){ return intval($id['id']); }, $data['categories']);
        $product->set_category_ids($category_ids);
    } 

    // Set the product as a draft
    if(isset($data['status']) && $data['status'] == 'draft') $product->set_status('draft');

    // Save the product to get the product id
    $product_id = $product->save();

    // Set the product tags
    if(isset($data['tags'])){
        $tags = array_map(function($tag){ return $tag['name']; }, $data['tags']);
        // Save the tags using the product id
        wp_set_object_terms($product_id, $tags, 'product_tag');
    }
    

}

function update_product($data, $product_id){
    // Create the type of product
    if($data['type'] == 'simple') $product = new WC_Product($product_id);
    else {
        switch($data['type']){
            case 'grouped':
                $product = new WC_Product_Grouped($product_id);
                break;
            case 'external':
                $product = new WC_Product_External($product_id);
                break;
            case 'variable':
                $product = new WC_Product_Variable($product_id);
                break;
            default:
                $product = new WC_Product($product_id);
        }
    }

        // Set the name
        if(isset($data['name'])) $product->set_name($data['name']);

        // Set the product images and gallery images
        if(isset($data['images']) && count($data['images']) > 0) {
            $product->set_image_id($data['images'][0]);
            if(count($data['images']) > 1) $product->set_gallery_image_ids($data['images']);
        }
    
        // Set the long description
        if(isset($data['description'])) $product->set_description($data['description']);
    
        // Set if the product is virtual or not
        if(isset($data['virtual'])){
            $virtual = false;
            if($data['virtual'] == '1') $virtual = true;
            $product->set_virtual($virtual);
        }
    
        // Set if the product is downloadable
        if(isset($data['downloadable'])){
            $downloadable = false;
            if($data['downloadable'] == '1') $downloadable = true;
            $product->set_downloadable($downloadable);
        }
    
        // Set the regular price
        if(isset($data['regular_price'])) $product->set_regular_price($data['regular_price']);
    
        // Set the sale price
        if(isset($data['sale_price'])) $product->set_sale_price($data['sale_price']);
    
        // Set the tax class
        if(isset($data['tax_class'])) $product->set_tax_class($data['tax_class']);
    
        // Set the sku
        if(isset($data['sku'])) $product->set_sku($data['sku']);
    
        // Set if the stock should be counted or not
        if(isset($data['manage_stock'])){
            $manage_stock = false;
            if($data['manage_stock'] == '1') $manage_stock = true;
            $product->set_manage_stock($manage_stock);
        }
    
        // Set if the stock quantity
        if(isset($data['stock-quantity'])) $product->set_stock_quantity($data['stock-quantity']);
    
        // Set the stock status
        if(isset($data['stock_status'])) $product->set_stock_status($data['stock_status']);
    
        // Set the shipping dimensions
        if(isset($data['dimensions'])){
            $product->set_length($data['dimensions']['length']);
            $product->set_height($data['dimensions']['width']);
            $product->set_width($data['dimensions']['height']);
        }
    
        // Set the product weight
        if(isset($data['weight'])) $product->set_weight($data['weight']);
    
        // set the shipping class
        if(isset($data['shipping_class'])) $product->set_shipping_class_id($data['shipping_class']);
    
        // Set the short description
        if(isset($data['short_description'])) $product->set_short_description($data['short_description']);
    
        // Set the product categories
        if(isset($data['categories'])){
            $category_ids = array_map(function($id){ return intval($id['id']); }, $data['categories']);
            $product->set_category_ids($category_ids);
        } 
        
        // Set the product as a draft
        if(isset($data['status']) && $data['status'] == 'draft') $product->set_status('draft');
    
        // Save the product to get the product id
        $product_id = $product->save();
    
        // Set the product tags
        if(isset($data['tags'])){
            $tags = array_map(function($tag){ return $tag['name']; }, $data['tags']);
            // Save the tags using the product id
            wp_set_object_terms($product_id, $tags, 'product_tag');
        }
}