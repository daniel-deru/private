<?php

function checkCode(){
    $key = get_option("wp_smart_products_consumer_key");
    $secret = get_option("wp_smart_products_consumer_secret");
    
    if(!($key && $secret)) return false;
    return true;
}


?>