<?php
    $absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
    $wp_load = $absolute_path[0] . 'wp-load.php';
    require_once($wp_load);

    $color = get_option("wp_smart_products_brand_color") ? get_option("wp_smart_products_brand_color") : "#21759b";


    header('Content-Type: text/css');
    header("Cache-control: must-revalidate");
?>

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

input[type="file"] {
    display: none;
}


.custom-file-upload {
    border-radius: 5px;
    display: block;
    padding: 0.5rem 2rem;
    cursor: pointer;
    text-align: center;
    background: var(--main-blue);
    color: white;
    margin-bottom: 1rem;
    width: fit-content;
}

#img {
    max-width: 400px;
    max-height: 400px;
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

button {
    margin: 0px 10px;
    padding: 5px 30px;
    color: white;
    background-color: var(--main-blue);
    border: none;
    border-radius: 3px;
}

input[type="submit"] {
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

/* :root {
    --main-blue: <?php echo $color ?>;
}

* {
    padding: 0;
    margin: 0;
}

header {
    height: 15vh;
    background: var(--main-blue);
    display: flex;
    align-items: center;
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

form {
    font-size: 1.25em;
    border-radius: 10px;
    color: var(--main-blue);
    font-family: sans-serif;
    width: 70%;
    margin: auto;
}

input, textarea, select {
    font-size: 1em;
    border-radius: 5px;
    color: var(--main-blue);
    font-family: sans-serif;
    border: 2px solid var(--main-blue);
    padding: 5px;
    margin: 10px 0px;
}

textarea {
    width: calc(100% - 20px);
    border-radius: 10px;
}

select {
    width: 100%;
}



button {
    background-color: var(--main-blue);
    color: white;
    font-size: 1em;
    border-radius: 10px;
    font-family: sans-serif;
    border: 2px solid var(--main-blue);
    padding: 5px 20px;
    cursor: pointer;
    width: 26%;
    text-align: center;
}

input[type="submit"]{
    display: block;
    background-color: var(--main-blue);
    color: white;
    font-size: 1em;
    border-radius: 5px;
    font-family: sans-serif;
    border: 2px solid var(--main-blue);
    padding: 5px 50px;
    cursor: pointer;
    text-align: center;
    margin: auto;
}


form > div {
    margin: 2rem 0rem;
}

form .flex-fields {
    display: flex;
    justify-content: space-between;
}

.label-block {
    display: block;
}

label {
    margin: 5px;
}


input[type="file"] {
    display: none;
}


.custom-file-upload {
    border-radius: 5px;
    display: block;
    padding: 1rem 2rem;
    cursor: pointer;
    text-align: center;
    background: var(--main-blue);
    color: white;
    margin-bottom: 1rem;
    width: fit-content;
}

#img {
    max-width: 400px;
    max-height: 400px;
}

#sku-input {
    width: calc(100% - 20px);
}

#categories-tags-container > div {
    width: 40%;
}

#categories-tags-container input[type="text"]:not(#new-tag) {
    width: calc(100% - 20px);
}
#categories-checkboxes {
    max-height: 200px;
    overflow: auto;
}

#tags-container {
    max-height: 200px;
    overflow: auto;
    display: grid;
}

#new-tag {
    width: 70%;
}

#categories-tags-container button {
    width: calc(26%);
    padding: 5px 20px;
    border-radius: 5px;
}

.subcategory-checkbox {
    margin-left: 20px;
}

#weight-dimensions:first-child {
    margin-bottom: 20px;
} */