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
    --main-light-green: <?php echo $color ?>;
    --gradient: <?php echo $color ?>;
}

* {
    margin: 0;
    padding: 0;
}

header {
    height: 15vh;
    background: var(--gradient);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

header > * {
    margin: 1em;
}

header img {
    height: 100px;
}

header select {
    font-size: 1em;
    outline: none;
    color: white;
    padding: 5px;
    border-radius: 5px;
    background-color: transparent;
    border: 2px solid white;
}

header input {
    font-size: 1em;
    padding: 5px;
    border-radius: 5px;
    border: 2px solid white;
}

header button {
    font-size: 1em;
    padding: 5px;
    border-radius: 5px;
    border: none;
    background-color: transparent;
    color: white;
    border: 2px solid white;
}

h1 {
    text-align: center;
    font-family: sans-serif;
    margin-top: 3rem;
}

#filter-container {
    display: flex;
    justify-content: space-evenly;
}

#filter-container div {
    margin: 0px 10px;
}

select option {
    background-color: var(--main-green);
    color: white;
}


header a {
    font-size: 1em;
    outline: none;
    color: white;
    padding: 10px;
    border-radius: 5px;
    border: 2px solid white;
    background: transparent;
    text-decoration: none;
    font-family: sans-serif;
}

#product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-row-gap: 2rem;
    margin: 0em 10em;
    margin-top: 5rem;
    font-family: sans-serif;
}
.product-image {
    max-width: 15vw;
    max-height: 15vw;
    width: 15vw;
    height: 15vw;
}

.product-container {
    box-shadow: 0px 0px 5px 3px hsla(90, 0%, 0%, 0.2);
    max-width: 15vw;
    margin: auto;
    padding: 1em;
    border-radius: 10px;
}

.product-container > * {
    margin-bottom: 1em;
}

.product-container .title {
    font-weight: bolder;
    font-size: 1.2em;
}

.product-container a {
    display: block;
    margin: auto;
    font-family: sans-serif;
    font-size: 1.2em;
    padding: 10px 16px;
    border-radius: 5px;
    border: none;
    background: var(--gradient);
    color: white;
    text-decoration: none;
    font-family: sans-serif;
    text-align: center;
}

#pagination {
    text-align: center;
    margin: 3rem auto;
    font-size: 1.5em;
    font-family: sans-serif;
}

#pagination ul {
    justify-content: center;
    display: flex;
    list-style-type: none;
}

#pagination ul li {
    padding: 10px;
}

#pagination a {
    text-decoration: none;
    color: black;
    border: 2px solid black;
    padding: 5px;
    border-radius: 5px;
}

#pagination a:active {
    color: var(--main-green);
    border: 2px solid var(--main-green);
}

#pagination a:visited {
    color: var(--main-green);
    border: 2px solid var(--main-green);
}
