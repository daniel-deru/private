<?php
    $absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
    $wp_load = $absolute_path[0] . 'wp-load.php';
    require_once($wp_load);

    $color = get_option("wp_smart_products_brand_color") ? get_option("wp_smart_products_brand_color") : "#21759b";


    header('Content-Type: text/css');
    header("Cache-control: must-revalidate");
?>

:root {
    --main-green: <?php echo $color ?>;
    --main-blue:<?php echo $color ?>;
    --gradient: <?php echo $color ?>;
}

#login-form {
    margin: 10rem auto;
    box-shadow: 0px 0px 5px 1px hsla(60, 0%, 0%, 0.5);
    width: 40%;
    font-size: 1.2em;
    font-family: sans-serif;
    padding: 1em 0em;
    border-radius: 10px;
    box-sizing: border-box;
}

label {
    display: block;
    color: var(--main-blue);
}

#login-form > div {
    margin: 2rem auto;
    width: 60%;
}

#login-form input {
    padding: 10px;
}

#login-form label {
    margin-bottom: 20px;
}


#login-form > div > *:not(input[type="submit"], button) {
    font-size: 1.2em;
    width: 100%;
    border-radius: 5px;
    outline-color: var(--main-green);
    /* padding: 10px; */
    color: var(--main-blue);
}

#login-form > div input:not(input[type="submit"]){
    border: 2px solid var(--main-blue);
}

#login-form > div input[type="submit"]{
    border: 2px solid var(--gradient);
    border-radius: 5px;
    display: block;
    font-size: 1.2em;
    background: var(--gradient);
    color: white;
    width: 20rem;
    margin: auto;
    padding: 10px; 
}

#login-btn{
    cursor: pointer;
}

#display_errors {
    color: red;
    font-size: 2em;
    text-align: center;
    font-family: sans-serif;
}

.password-container {
    position: relative;
}

.password-container input {
    font-size: 1.2em;
    border-radius: 5px;
    width: 100%;
}

.hide {
    display: none;
}

.show {
    display: block;
}

#icon-button {
    font-size: 1em;
    width: fit-content;
    height: fit-content;
    cursor: pointer;
    position: absolute;
    cursor: pointer;
    top: 0px;
    right: 0;
    z-index: 5;
    background-color: transparent;
    border: none;
    transform: translateY(calc(50% + 5px));
}