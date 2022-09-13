<?php

if(isset($_POST['form'])){
    if(!isset($_POST['name']) || !isset($_POST['email'])) return;

    $name = sanitize_title($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $url = "https://users.smartmetatec.com";
    $result = wp_remote_post('https://users.smartmetatec.com/api/products/commerce', ['body' => ['name' => $name, 'email' => $email]]);

    echo "<pre>";
    print_r($result);
    echo "</pre>";
}


?>

<form method="post" action="">
    <div>
        <label for="full_name">Full Name</label>
        <input type="text" name="name">
    </div>
    <div>
        <label for="full_name">Email</label>
        <input type="email" name="email">
    </div>
    <div>
        <input type="submit" name="form">
    </div>
</form>