<?php

function checkCode(){
    $key = get_option("wp_smart_products_consumer_key");
    $secret = get_option("wp_smart_products_consumer_secret");
    
    if(!(isset($key) && isset($secret))) return false;
    return true;
}


?>