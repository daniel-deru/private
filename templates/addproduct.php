<?php

/**
 * Template Name: Addproduct
 */
require __DIR__ . "/woocommerce-api.php";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else $link = "http";

$link .= "://" . $_SERVER['HTTP_HOST'];
if(isset($_SERVER['HTTP_REFERER'])){
    $previous_page = $_SERVER['HTTP_REFERER'];
    $from_products = preg_match("/products/", $previous_page);

    if(true){
        $categoriesData = json_decode($listCategories(), true);
        $categories = $categoriesData['data'];

    }
}
else {
    // header("Location: " . $link . "/product/secret/login.php");
    // exit;
}

?>

<?php
    if(isset($_POST['product-name'])){

        $data = [];
        $data['name'] = $_POST['product-name'];

        if(isset($_POST['product-regular-price'])){
            $data['regular_price'] = $_POST['product-regular-price'];
        }
        if(isset($_POST['product-sale-price'])){
            $data['sale_price'] = $_POST['product-sale-price'];
        }
        if(isset($_POST['product-type'])){
            $data['type'] = $_POST['product-type'];
        }
        if(isset($_POST['product-virtual'])){
            $data['virtual'] = true;
        }
        if(isset($_POST['product-downloadable'])){
            $data['downloadable'] = true;
        }
        if(isset($_POST['product-description'])){
            $data['description'] = $_POST['product-description'];
        }
        if(isset($_POST['product-short-description'])){
            $data['short_description'] = $_POST['product-short-description'];
        }
        if(isset($_POST['product-sku'])){
            $data['sku'] = $_POST['product-sku'];
        }

        if($_POST['product-categories']){
            $categoriesArray = [];
            $categoriesSelected = explode("%", $_POST['product-categories']);
            for($i = 0; $i < count($categoriesSelected); $i++){
                $categoriesArray[$i] = array(
                    'id' => $categoriesSelected[$i]
                );
            }
            $data['categories'] = $categoriesArray;
        }

        if($_POST['product-tags']){
            $tagsArray = [];
            $tags = explode("%", $_POST['product-tags']);
            for($i = 0; $i < count($tags); $i++){
                $tagsArray[$i] = array(
                    'name' => $tags[$i]
                );
            }
            $data['tags'] = $tagsArray;
        }

        

        $imageFolder = dirname(__FILE__) . "/images";

        if($_FILES['product-image']['name']){
            $serverImagePath = $imageFolder . "/" . $_FILES['product-image']['name'];
        
            move_uploaded_file($_FILES['product-image']['tmp_name'], $serverImagePath);
            $image = "http://" . $_SERVER['HTTP_HOST'] . "/product" . "/wp-content/plugins/private/templates/images/" . $_FILES['product-image']['name'];

            $data['images'] = array(
                array(
                    'src' => $image
                )
            );

        }
        
        $saveProduct = json_decode($addProduct($data), true);

        $files = glob($imageFolder . "/*");
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/addproduct.css">
    <script src="./js/addproduct.js" defer></script>
    <title>Add Product</title>
</head>
<body>
    <header>
        <a href="products">Go back to products</a>
    </header>
    <form enctype="multipart/form-data" action="" method="post">
        <div id="title-price">
            <!-- This is the name field input -->
            <span>
                <label for="product-name" >Name</label>
                <input type="text" name="product-name" id="name" required>
            </span>
            <span>
                <label for="product-regular-price">Regular Price</label>
                <input type="text" name="product-regular-price" id="regular-price">
            </span>
            <span>
                <label for="product-sale-price">Sale Price</label>
                <input type="text" name="product-sale-price" id="sale-price">
            </span>
        </div>

        <div id="product-settings">
            <span >
                <label for="product-type" ></label>
                <select name="product-type" id="product-type">
                    <option value="" selected disabled>Product Type</option>
                    <option value="simple">Simple Product</option>
                    <option value="grouped">Grouped Product</option>
                    <option value="external">External/Affiliate Product</option>
                    <option value="variable">Variable Product</option>
                </select>
            </span>
            <span >
                <label for="product-virtual" class="inline">Virtual</label>
                <input type="checkbox" name="product-virtual" id="virtual">
            </span>
            <span>
                <label for="product-downloadable" class="inline">Downloadable</label>
                <input type="checkbox" name="product-downloadable" id="downloadable">
            </span>
        </div>

        <div id="product-image">
        <label class="custom-file-upload">
            <input type="file" id="image" name="product-image"/>
            Custom Upload
        </label>
            <div id="image-preview">
                <img src="" alt="" id="img">
            </div>
        </div>

        <div id="product-description">
            <label for="product-description">Description</label>
            <textarea name="product-description" cols="30" rows="10" id="description"></textarea>
        </div>

        <div id="product-short-description">
            <label for="product-short-description"> Short Description</label>
            <textarea name="product-short-description" id="short-description" cols="30" rows="10"></textarea>
        </div>

        <div id="categories-tags">
            <div id="choose-category">
                <label for="product-category">Choose Category</label>
                <select name="product-category" id="category">
                    <option value="" disabled selected>Select Category</option>
                    <?php
                        foreach($categories as $category){?>
                            <option value="<?= $category['name'], $category['id']?>"><?= $category['name']?></option>
                       <?php }
                    ?>
                </select>
                <ul id="category-items"></ul>
            </div>
            <div id="tag-container">
                <div id="tag-form">
                    <label for="product-tags">Add Tags</label>
                    <span>
                        <input type="text" id="tag-input">
                        <button type="button" id="tag-button">Add</button>
                    </span>
                </div>
                <ul id="tag-items"></ul>
            </div>
        </div>

        <div id="sku">
            <label for="product-sku">SKU</label>
            <input type="text" name="product-sku" id="sku-input">
        </div>

        <div id="btn-save">
            <input type="submit" id="save-btn" value="Save">
        </div>
        <input type="hidden" name="product-categories" id="hidden-categories">
        <input type="hidden" name="product-tags" id="hidden-tags">
    </form>
    <div id="errors"></div>
</body>
</html>

<style>
    :root {
    --main-green: #04411c;
    --main-light-green: rgba(53,205,22,1);
    --gradient: linear-gradient(90deg, rgba(53,205,22,1) 0%, rgba(4,65,28,1) 100%);
}

* {
    padding: 0;
    margin: 0;
}

header {
    height: 15vh;
    background: var(--gradient);
    display: flex;
    align-items: center;
}

header a {
    color: white;
    text-decoration: none;
    font-family: sans-serif;
    font-size: 1.2em;
    padding: 1em;
    border: 2px solid white;
    border-radius: 5px;
    margin-left: 1em;
}

form {
    max-width: 80%;
    margin: auto;
    font-size: 1.2em;
    font-family: sans-serif;
    margin-top: 2rem;
}

label {
    color: black;
    margin-bottom: 5px;
}

form label:not(.inline) {
    display: block;
}

.inline {
    display: inline;
}

form button, form input[type="submit"] {
    background: var(--gradient);
    border: none;
    color: white;
    border-radius: 5px;
}

form input {
    font-size: 1.2em;
    font-family: sans-serif;
    padding: 5px 8px;
    border-radius: 5px;
    border: 2px solid var(--main-green);
}

form button {
    font-size: 1.2em;
    font-family: sans-serif;
    padding: 5px 15px;
}

form textarea {
    width: 100%;
    border-radius: 5px;
    border: 2px solid var(--main-green);
    font-size: 1.2em;
    font-family: sans-serif;
    padding: 5px 15px;
    outline: var(--main-light-green);
}

form select {
    font-size: 1.2em;
    font-family: sans-serif;
    padding: 5px 8px;
    border-radius: 5px;
    border: 2px solid var(--main-green);
    color: var(--main-green);

}

form > div {
    margin-top: 2rem;
}

/* This is for the first row of the name, regular price and sale price */
form > div:nth-child(1) {
    display: flex;
    justify-content: space-between;
}

/* This is for the second row that has the product type, virual and downloadable fields */
form > div:nth-child(2) {
    display: flex;
    justify-content: space-between;
}

/* This is for the categories and the tags fields */
form > div:nth-child(6) {
    display: flex;
    justify-content: space-between;
}

#sku input {
    width: 100%;
}

#btn-save input{
    display: block;
    margin: auto;
    margin-bottom: 5rem;
}

input[type="file"] {
    display: none;
}

.custom-file-upload {
    border-radius: 5px;
    display: block;
    padding: 6px 12px;
    cursor: pointer;
    max-width: 10rem;
    text-align: center;
    background: var(--gradient);
    color: white;
}

ul {
    list-style-type: none;
    color: var(--main-green);
}

ul li {
    background: var(--gradient);
    border-radius: 5px;
    margin: 10px 0px;
    width: fit-content;
    padding: 5px;
    color: white;
}

img {
    width: 300px;
}

</style>

<script>
    
const categorySelect = document.getElementById("category")
const categoryList = document.getElementById("category-items")

const tagBtn = document.getElementById("tag-button")
const tagList = document.getElementById("tag-items")

let categoryItems = []
let categoryIDs = []
let tagItems = []
let imageSet = false

categorySelect.addEventListener("change",(event) => addCategories(event))
tagBtn.addEventListener("click", () => addTags())


const saveBtn = document.getElementById("save-btn")
saveBtn.addEventListener("click", () => saveClicked())

const imageUpload = document.getElementById("image")
imageUpload.addEventListener("change", (event) => showImage(event))

function addCategories(event){
    
    let name = (/[a-z]*/gi).exec(event.target.value)[0]
    let id = event.target.value.match(/[0-9]*/g).join("")
    if(!categoryItems.includes(name)){
        
        categoryItems.push(name) 
        categoryIDs.push(id)
        displayCategories()
    }
   
}

function displayCategories(){

    while(categoryList.firstChild){
        categoryList.removeChild(categoryList.firstChild)
    }
    
    if(categoryItems.length > 0){
        for(let i = 0; i < categoryItems.length; i++){

            let listItem = document.createElement("li")
            let text = document.createTextNode(categoryItems[i])
            listItem.appendChild(text)
            listItem.dataset.id = categoryIDs[i]
            listItem.addEventListener("click", (event) => deleteCategories(event))
            categoryList.appendChild(listItem)

        }

        const hiddenCategories = document.getElementById("hidden-categories")
        const categories = categoryIDs.join("%")
        
        hiddenCategories.value = categories
    }
}

function deleteCategories(event){
    categoryItems = categoryItems.filter(item => item != event.target.innerText)
    categoryIDs = categoryIDs.filter(item => item != event.target.dataset.id)
    displayCategories()
}


function addTags(){
    let input = document.getElementById("tag-input")
    let name = input.value

    console.log(name)
    if(!tagItems.includes(name)){
        
        tagItems.push(name) 
        displayTags()
    }
    input.value = ""
   
}

function displayTags(){

    while(tagList.firstChild){
        tagList.removeChild(tagList.firstChild)
    }

    if(tagItems.length > 0){
        for(let i = 0; i < tagItems.length; i++){

            let listItem = document.createElement("li")
            let text = document.createTextNode(tagItems[i])
            listItem.appendChild(text)
            listItem.addEventListener("click", (event) => deleteTags(event))
            tagList.appendChild(listItem)
        }
    }

    const hiddenTags = document.getElementById("hidden-tags")
    const tags = Array.from(document.getElementById("tag-items").children).map(tag => tag.innerText).join("%")
    hiddenTags.value = tags
}

function deleteTags(event){
    tagItems = tagItems.filter(item => item != event.target.innerText)
    displayTags()
}

function showImage(event){
    console.log(event.target.files)
    let output = document.getElementById("img")
    output.src = URL.createObjectURL(event.target.files[0])
    imageSet = true
}

function saveClicked(){
    const name = document.getElementById("name").value
    const regularPrice = document.getElementById("regular-price").value
    const salePrice = document.getElementById("sale-price").value
    const productType = document.getElementById("product-type").value
    const virtual = document.getElementById("virtual").checked
    const downloadable = document.getElementById("downloadable").checked
    const image = document.getElementById("img")
    const description = document.getElementById("description").value
    const shortDescription = document.getElementById("short-description").value
    const sku = document.getElementById("sku-input").value
    const categories = Array.from(document.getElementById("category-items").children).map(category => category.innerText).join("%")
    const tags = Array.from(document.getElementById("tag-items").children).map(tag => tag.innerText).join("%")

    const errors = document.getElementById("errors")

    while(errors.firstChild){
        errors.removeChild(errors.firstChild)
    }

    if(!imageSet){
        let error = document.createElement("div")
        error.appendChild(document.createTextNode("Please set an image."))
        errors.appendChild(error)
    }
}
</script>

