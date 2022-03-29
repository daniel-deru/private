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

#errors {
    color: red;
    text-align: center;
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
    /* margin: auto; */
    justify-content: space-between;
}

.flex-container-vertical {
    display: flex;
    /* width: 100%; */
    flex-direction: column;
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

#product-image div {
    display: flex;
    justify-content: space-between;
}

/* The box where the divs with product images will be in */
#addeditproduct-form #image-viewer {
    margin-top: 1rem;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-gap: 1rem;
}

#addeditproduct-form #image-viewer > div {
    position: relative;
    width: 90%;
}

#addeditproduct-form #image-viewer svg {
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

#addeditproduct-form #image-viewer div input[type="radio"]{
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

#addeditproduct-form #image-viewer div input[type="radio"]::before{
    content: '';
    width: 10px;
    height: 10px;
    border-radius: 50%;
    transform: scale(0);
    transform: 120ms transform ease-in-out;
    box-shadow: inset 1em 1em var(--main-blue);
}
#addeditproduct-form #image-viewer div input[type="radio"]:checked::before {
    transform: scale(1);
}


#addeditproduct-form img {
    width: 100%;
}

.wp-media-buttons:not(#insert-media-button) {
    margin: 10px 0px;

}

#insert-media-button {
    padding: 5px 15px;
    background-color: var(--main-blue);
    border: none;
    border-radius: 3px;
    color: white;
    cursor: pointer;
}

#product-description label,
#product-short-description label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
}

#product-description  button:not(#insert-media-button),
#product-short-description button:not(#insert-media-button)  {
    padding: 5px 15px;
    margin: 0px 5px;
    background-color: var(--main-blue);
    border: none;
    border-radius: 3px;
    color: white;
    cursor: pointer;
}

.mce-container-body div[role="button"] {
    border-color: transparent !important;
    border: none !important;
}

#product-description button i,
#product-short-description button i  {
    color: white;
}
#product-description input[type="button"], 
#product-short-description input[type="button"] {
    color: white;
    background-color: var(--main-blue);
    border: none;
}

.mce-preview {
    left: 0 !important;
    transform: translateX(100%) !important;
    

}

#general > div {
    display: flex;

    justify-content: space-between;
}
#general label {
    margin-bottom: 10px;
}

#general .flex-container-vertical {
    width: 80%;
}

#general .flex-container-vertical select, 
#general .flex-container-vertical input {
    width: 80%;
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

#addeditproduct-form button:not(#product-description button, #product-short-description button) {
    margin: 0px 10px;
    padding: 5px 30px;
    color: white;
    background-color: var(--main-blue);
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

#addeditproduct-form input[type="submit"] {
    display: block;
    /* margin: auto; */
    padding: 10px 40px;
    background-color: var(--main-blue);
    color: white;
    border-radius: 3px;
    border: none;
    cursor: pointer;
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

#btn-save {
    display: flex;
    justify-content: space-around;
    align-items: center;
}

#btn-save label {
    font-weight: 700;
    background-color: var(--main-blue);
    text-align: center;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    user-select: none;
    cursor: pointer;
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
    /* margin-left: 20px; */
}