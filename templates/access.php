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
$loggedIn = wp_get_current_user();
echo "<pre>";
print_r($loggedIn);
echo "</pre>";

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
            // Remove the product from the url
            header("Location:" . $link . "/product/products?id=1");
            exit;
        }
    }
}
else if( is_user_logged_in() ){
    $user = wp_get_current_user();
    if(in_array("product_manager", $user->roles)){
        header("Location:" . $link . "/product/products?id=1");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
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
            <input type="submit" name="products-login" value="login">
        </div>
    </form>
    <div id="display errors"><?php if($error) echo $error?></div>
</body>
</html>

<style>
    :root {
    --main-green: #9ecd16;
    --main-blue: #051456;
    --gradient: linear-gradient(90deg, rgba(158,205,22,1) 0%, rgba(5,20,86,1) 85%);
}

#login-form {
    margin: 10rem auto;
    box-shadow: 0px 0px 5px 1px hsla(60, 0%, 0%, 0.5);
    width: 50%;
    font-size: 1.5em;
    font-family: sans-serif;
    padding: 1em 0em;
    border-radius: 10px;
}

label {
    display: block;
    color: var(--main-blue);
}

#login-form div {
    margin: 2rem auto;
    width: 60%;
}


#login-form div > *:not(input[type="submit"]) {
    font-size: 1.5em;
    width: 100%;
    border-radius: 5px;
    outline-color: var(--main-green);
    padding: 10px;
    color: var(--main-blue);
}

#login-form div input:not(input[type="submit"]){
    border: 2px solid var(--main-blue);
}

#login-form div input[type="submit"]{
    border: 2px solid var(--gradient);
    border-radius: 5px;
    display: block;
    font-size: 1.5em;
    background: var(--gradient);
    color: white;
    width: 20rem;
    margin: auto;
    padding: 10px; 
}
</style>


