<?php
    $absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
    $wp_load = $absolute_path[0] . 'wp-load.php';
    require_once($wp_load);

    $color = get_option("wp_smart_products_brand_color") ? get_option("wp_smart_products_brand_color") : "#21759b";


    header('Content-Type: text/css');
    header("Cache-control: must-revalidate");
?>
/*<style>*/
:root {
    --main-blue: <?php echo $color ?>;
}

* {
    padding: 0;
    margin: 0;
    font-family: sans-serif;
    font-size: 1.1rem;
}

body {
    margin-bottom: 100px;
}

header {
    height: 15vh;
    padding: 0px 1rem;
    background: var(--main-blue);
    display: flex;
    align-items: center;
    display: flex;
    justify-content: space-between;
}

header img {
    height: 100px;
}

header a {
    color: white;
    text-decoration: none;
    font-family: sans-serif;
    font-size: 1em;
    padding: 0.5em 1em;
    border: 2px solid white;
    border-radius: 5px;
    margin-left: 1em;
}
h1 {
    text-align: center;
    font-family: sans-serif;
    margin-top: 3rem;
}

form {
    width: 60vw;
    margin: auto;
}

form > div {
    margin-top: 40px;
    border-radius: 5px;
    border: 1px solid darkgray;
    padding: 20px;
    background-color: hsla(0, 0%, 90%, 1);
}

.label-block {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
}

label {
    display: inline-block;
    width: max-content;
    min-width: 150px;
    color: var(--main-blue);
}


.flex-container {
    display: flex;
    width: 100%;
    justify-content: space-between;
}

.around {
    justify-content: space-around;
}

.padding-left {
    padding-left: 10px;
}

select,
input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    border: none;
    border-radius: 3px;
    padding: 5px;
}

select,
input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus {
    outline: 1px solid var(--main-blue);
}

/* The box where the divs with product images will be in */
#addproduct-form #image-viewer {
    margin-top: 1rem;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-gap: 1rem;
}

#addproduct-form #image-viewer > div {
    position: relative;
    width: 90%;
}

#addproduct-form #image-viewer svg {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: var(--main-blue);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    z-index: 5;
    cursor: pointer;
    user-select: none;
}

#addproduct-form #image-viewer div input[type="radio"]{
    position: absolute;
    top: -10px;
    left: -10px;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid var(--main-blue);
    display: grid;
    place-content: center;
}

#addproduct-form #image-viewer div input[type="radio"]::before{
    content: '';
    width: 10px;
    height: 10px;
    border-radius: 50%;
    transform: scale(0);
    transform: 120ms transform ease-in-out;
    box-shadow: inset 1em 1em var(--main-blue);
}
#addproduct-form #image-viewer div input[type="radio"]:checked::before {
    transform: scale(1);
}


#addproduct-form img {
    width: 100%;
}

#product-settings  {
    padding: 20px;
}

.inventory-container > div {
    margin: 20px 0px;
}

.inventory-container > div:nth-child(2) span {
    margin-right: auto
}

#dimensions > *:nth-child(3) {
    margin: 0px 20px;
}

#shipping div div {
    margin: 10px 0px;
}

#categories-tags-container {
    padding: 20px 0px
}

#categories-tags-container > div > * {
    margin: 10px 0px;
}

#new-tag {
    width: auto;
}

#addproduct-form button {
    margin: 0px 10px;
    padding: 5px 30px;
    color: white;
    background-color: var(--main-blue);
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

#addproduct-form input[type="submit"] {
    display: block;
    margin: auto;
    padding: 10px 40px;
    background-color: var(--main-blue);
    color: white;
    border-radius: 3px;
    border: none;
}

.subcategory-checkbox {
    margin-left: 20px;
}

.checkbox-label {
    display: inline-block;
    margin-left: 10px;
}

#categories-checkboxes {
    height: 300px;
    overflow: auto;
}

.help {
    position: relative;
    cursor: pointer;
}

.help div {
    position: absolute;
    top: 100%;
    left: 100%;
    width: 300px;
    background-color: black;
    color: white;
    opacity: 0;
    padding: 10px;
    border-radius: 5px;
    font-size: 0.75em;
    transition: all 0.3s ease-in-out;
    display: none;
}

.help:hover div {
    opacity: 1;
    display: block;
}

#general .flex-container:nth-child(2) label {
    display: inline-block;
    margin-left: 20px;
}