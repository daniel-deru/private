<?php
/**
 * 
 * Template Name: Access
 * 
 *
 * 
 * 
 * 
 */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo dirname(plugin_dir_url(__FILE__), 1) . "/public/css/access.css"?>">
    <title>Login</title>
</head>



<?php
$error = null;

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";

// Here append the common URL characters.
$link .= "://";

// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
// $link .= $_SERVER['REQUEST_URI'];

if(isset($_POST['products-login'])){
    if (empty($_POST['products-name']))
    {
       $error = "Please enter a name";
    }
    else if(empty($_POST["products-password"])){
        $error = "Please enter a password";
    }
    else if(!empty($_POST['products-name']) && !empty($_POST['products-password'])){
        $name = $_POST['products-name'];
        $password = $_POST['products-password'];
        $user = wp_authenticate($name, $password);
        if(in_array('product_manager', $user->roles)){

            header("Location:" . $link . "/wp-smart-products?id=1");
            exit;
        }
    }
}

?>

<body>
    <form action="" method="post" id="login-form">
        <div class="form-field">
            <label for="products-name">Username</label>
            <input type="text" name="products-name">
        </div>
        <div class="form-field">
            <label for="products-password">Password</label>
            <input type="password" name="products-password">
        </div>
        <div class="form-field">
            <input type="submit" name="products-login" value="login" id="login-btn">
        </div>
    </form>
    <div id="display errors"><?php if($error) echo $error?></div>
</body>
</html>


