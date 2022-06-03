// Global variable to check if the image is set
// let imageSet = false
let tagsArray = []
let sortedCategories = []
let imageArray = []

// const imageUpload = document.getElementById("image")
const saveBtn = document.getElementById("save-btn")
const tagBtn = document.getElementById("tag-btn")
const hiddenProduct = document.getElementById("product-data")
const imageViewer = document.getElementById("image-viewer")
const hiddenInputImages = document.getElementById("image-urls")
const chooseBtn = document.getElementById("image-selector")
const deleteBtns = document.getElementsByClassName("delete-icon")


saveBtn.addEventListener("click", (event) => saveClicked(event))
// imageUpload.addEventListener("change", (event) => showImage(event))
tagBtn.addEventListener("click", () => addTags())
chooseBtn.addEventListener("click", (e) => openMedia(e))


parseCategories()
getTags()
setCheckboxes()
getImages()
setProductType()
setTaxClass()
setStockStatus()
setShippingClass()

function setShippingClass(){
    const shippingClass = JSON.parse(hiddenProduct.value).shipping_class

    if(shippingClass) {
        const shippingClassSelect = document.getElementById("shipping-class")

        for(let i = 0; i < shippingClassSelect.children.length; i++){
            let option = shippingClassSelect.children[i]
            if(option.value == shippingClass) option.selected = true
        }
    }
}

function setStockStatus(){
    const stockStatus = JSON.parse(hiddenProduct.value).stock_status
    const StockStatusSelect = document.getElementById("stock-status")

    for(let i = 0; i < StockStatusSelect.children.length; i++){
        let option = StockStatusSelect.children[i]
        if(option.value === stockStatus) option.selected = true
    }
}

function setTaxClass(){

    const taxClass = JSON.parse(hiddenProduct.value).tax_class
    const taxSelect = document.getElementById("tax-class")

    for(let i = 0; i < taxSelect.children.length; i++){ 
        let option = taxSelect.children[i]
        if(taxClass === option.value) option.selected = true
    }
}

function setProductType(){
    let productType = JSON.parse(hiddenProduct.value).product_type
    const productTypes = document.getElementById("product-type").children
    for(let i = 0; i < productTypes.length; i++){
        if(productTypes[i].value == productType) productTypes[i].selected = true
    }
}

function getImages(){
    // Get an array of image urls to display
    imageArray = JSON.parse(JSON.parse(hiddenProduct.value).product_images)
    displayImages()
}

function displayImages(){
    imageViewer.innerHTML = ""
    if(imageArray){
        for(let image of imageArray){
            // console.log("inside the image loop", image)
            // create image container
            const imageItem = document.createElement("div")

            // create image
            const imgElement = `<img src="${image.src}"/>`

            // Create the delete icon
            const deleteIcon = `<div class="delete-icon">
                                    <i class="fa fa-times"></i>
                                </div>`

            const featuredRadio = `<input type="radio" name="featured" value="${image.id}" id="${image.id}" ${image.id == imageArray[0].id ? "checked" : ""}>`

            // Put the image in the container
            imageItem.innerHTML = featuredRadio + imgElement + deleteIcon

            // Put the container in the DOM
            imageViewer.appendChild(imageItem)
        }
    }

    // Set the hidden input data to the image urls to process on server
    hiddenInputImages.value = imageArray.map(image => image.id).join(";")
    addDeleteListener()
    // console.log(imageInput.value)
    setColors()
}

function addDeleteListener(){
    for(let i = 0; i < deleteBtns.length; i++){
        deleteBtns[i].addEventListener("click", (e) => handleDeleteImage(e))
    }
}

function handleDeleteImage(e){

    // Get the img tag and  the src value of that image
    let targetNode = e.target.parentElement
    
    if(targetNode.nodeName !== "DIV") targetNode = targetNode.parentElement
    
    const imgURL = targetNode.previousSibling.src
    console.log("This is the target nodesibling", targetNode.previousSibling)
    // remove image from image array
    imageArray = imageArray.filter(image => image.src !== imgURL)

    // Rerender the images
    displayImages()
}

function openMedia(e){
    e.preventDefault()
    const frame = wp.media({ 
        title: 'Upload Image',
        button: {
            text: "Select"
        },
        multiple: false
    }).open()

    frame.on('select', function(){
        // This will return the selected image from the Media Uploader, the result is an object
        let attachment = frame.state().get('selection').first().toJSON()
        if(!imageArray.includes(attachment.url)){
            imageArray.push({src: attachment.url, id: attachment.id})
        }
        displayImages()
       
    })
}

function setCheckboxes(){
    const downloadableCheck = document.getElementById("downloadable")
    const virtualCheck = document.getElementById("virtual")
    const manageStockCheck = document.getElementById("manage-stock")
    const categories = document.querySelectorAll(".checkbox")
    const draft = document.getElementById("draft")

    // This fetches all the product data that requires the setting of checkboxes and images
    let productData = JSON.parse(hiddenProduct.value)
    
    if(productData.downloadable) downloadableCheck.checked = true

    if(productData.virtual) virtualCheck.checked = true

    if(productData.draft) draft.checked = true

    if(productData["manage_stock"]) manageStockCheck.checked = true
    
    for(let i = 0; i < categories.length; i++){
        for(let j = 0; j < productData.categories.length; j++){
            if(productData.categories[j].name == categories[i].dataset.name){
                categories[i].checked = true
            }
        }
    }
}


function getTags(){
    const tagContainer = document.getElementById('tags-container')
    let tags = tagContainer.children
    if(tags){
        for(let i = 0; i < tags.length; i++){
            tagsArray.push(tags[i].innerText)
        }
    }
    displayTags()
}

function addTags(){
    const tagInput = document.getElementById("new-tag")
    let tag = tagInput.value

    if(!tagsArray.includes(tag)){
        tagsArray.push(tag)
        displayTags()
    }
    tagInput.value = ''

}

function deleteTags(event){
    console.log(event.target.innerText)
    tagsArray = tagsArray.filter(tag => tag !== event.target.innerText)
    displayTags()
}

function displayTags(){
    const tagContainer = document.getElementById("tags-container")
    
    tagContainer.innerHTML = ""

    if(tagsArray.length > 0){
        
        for(let i = 0; i < tagsArray.length; i++){
            let tagElement = document.createElement("div")
            tagElement.addEventListener("click", (event) => deleteTags(event))
            tagElement.innerText = tagsArray[i]

            tagContainer.appendChild(tagElement)
        }
    }
}


// This function will display the categories
function displayCategories(){
    const categoryContainer = document.getElementById("categories-checkboxes")
    categoryContainer.innerHTML = ""
    for(let i = 0; i < sortedCategories.length; i++){

        let checkboxContainer = document.createElement("div")
        checkboxContainer.classList.add("checkbox-container")

        let categoryCheckbox = document.createElement('input')
        categoryCheckbox.type = "checkbox"
        categoryCheckbox.id = sortedCategories[i].term_id
        categoryCheckbox.classList.add("category-checkbox")
        categoryCheckbox.classList.add("checkbox")
        categoryCheckbox.dataset.parent = 0
        categoryCheckbox.dataset.name = sortedCategories[i].name
        categoryCheckbox.dataset.id = sortedCategories[i].term_id

        checkboxContainer.appendChild(categoryCheckbox)

        let checkboxLabel = document.createElement("label")
        checkboxLabel.setAttribute("for", sortedCategories[i].name)
        checkboxLabel.innerText = sortedCategories[i].name
        checkboxLabel.classList.add("checkbox-label")
        checkboxLabel.classList.add("inline")

        checkboxContainer.appendChild(checkboxLabel)

        categoryContainer.appendChild(checkboxContainer)

        

        if(sortedCategories[i].children.length > 0){

            let subcategories = sortedCategories[i].children

            for(let j = 0; j < subcategories.length; j++){

                let subCheckboxContainer = document.createElement("div")
                subCheckboxContainer.classList.add("checkbox-container")

                let subcategoryCheckbox = document.createElement("input")
                subcategoryCheckbox.type = "checkbox"
                subcategoryCheckbox.id = subcategories[j].term_id
                subcategoryCheckbox.classList.add("subcategory-checkbox")
                subcategoryCheckbox.classList.add("checkbox")
                subcategoryCheckbox.dataset.parent = subcategories[j].parent
                subcategoryCheckbox.dataset.name = subcategories[j].name
                subcategoryCheckbox.dataset.id = subcategories[j].term_id

                subCheckboxContainer.appendChild(subcategoryCheckbox)

                let subcategoryLabel = document.createElement("label")
                subcategoryLabel.innerText = subcategories[j].name
                subcategoryLabel.id = subcategories[j].term_id
                subcategoryLabel.classList.add("checkbox-label")
                subcategoryLabel.classList.add("inline")

                subCheckboxContainer.appendChild(subcategoryLabel)

                categoryContainer.appendChild(subCheckboxContainer)
            }
        }
    }
}

// Parse the data from the hidden input and put it in the sortedCategories array
function parseCategories(){
    const categoryHiddenData = document.getElementById("php-categories-data")
    let categories = JSON.parse(categoryHiddenData.value)
    sortedCategories = sortCategories(categories)
    displayCategories()
}

// Sort the categories according to parent and child relationships. subcategories cannot have children
function sortCategories(categories){
    categoriesList = []
    for(let i = 0; i < categories.length; i++){
        if(categories[i].parent == 0){
            let parent = {
                name: categories[i].name,
                term_id: categories[i].term_id,
                children: [],
                parent: categories[i].parent
            }
            categoriesList.push(parent)
            categories.splice(i, 1)
            i--
        }
    }

    for(let i = 0; i < categories.length; i++){
        categoriesList.filter((category, index) => {
            if( categories[i].parent && category.term_id == categories[i].parent){
                let child = {
                    name: categories[i].name,
                    term_id: categories[i].term_id,
                    children: [],
                    parent: categories[i].parent
                }
                categoriesList[index].children.push(child)
            }
        })

    }
    return categoriesList
    
}


function saveClicked(event){
    const errors = document.getElementById("errors")

    errors.innerHTML = ""

    let categories = document.querySelectorAll(".checkbox")
    let categoryList = []
    for(let i = 0; i < categories.length; i++){
        if(categories[i].checked){
             categoryList.push(categories[i].dataset.id)
        }
    }

    const hiddenCategories = document.getElementById("hidden-categories")
    const hiddenTags = document.getElementById("hidden-tags")

    hiddenCategories.value = categoryList.join("%")
    hiddenTags.value = tagsArray.join("%")
}

// jQuery for stylesheet

function setColors(){
    jQuery(document).ready(function($){
        const color = $("#brand-color").val()
    
        $("header").css("background-color", color)
        $("label:not(#smt-smart-commerce-hide)").css("color", color)
        $('select, input[type="text"]:focus, input[type="number"]:focus, textarea:focus').css("outline", `1px solid ${color}`)
        $('#addeditproduct-form #image-viewer svg').css('background-color', color)
        // $('#addeditproduct-form #image-viewer div input[type="radio"]').css('border', `2px solid ${color}`)
        // $('#addeditproduct-form #image-viewer div input[type="radio"]::before').css('box-shadow', `inset 1em 1em ${color}`)
        $('#insert-media-button').css('background-color', color)
        // $('#product-description button:not(#insert-media-button), #product-short-description button:not(#insert-media-button)').css('background-color', color)
        $('#product-description button, #product-short-description button').css('background-color', color)
        $('#product-description input[type="button"], #product-short-description input[type="button"]').css('background-color', color)
        $('#addeditproduct-form button:not(#product-description button, #product-short-description button)').css('background-color', color)
        $('#addeditproduct-form input[type="submit"]').css('background-color', color)
        $('#btn-save div').css('background-color', color)

    })
}

setColors()

