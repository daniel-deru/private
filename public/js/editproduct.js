// Global variable to check if the image is set
let imageSet = false
let tagsArray = []
let sortedCategories = []

const imageUpload = document.getElementById("image")
const saveBtn = document.getElementById("save-btn")
const tagBtn = document.getElementById("tag-btn")


saveBtn.addEventListener("click", (event) => saveClicked(event))
imageUpload.addEventListener("change", (event) => showImage(event))
tagBtn.addEventListener("click", () => addTags())

parseCategories()
getTags()
setCheckboxes()

function setCheckboxes(){
    const downloadableCheck = document.getElementById("downloadable")
    const virtualCheck = document.getElementById("virtual")
    const manageStockCheck = document.getElementById("manage-stock")
    const categories = document.querySelectorAll(".checkbox")
    const hiddenProduct = document.getElementById("product-data")
    let productData = JSON.parse(hiddenProduct.value)

    if(productData.downloadable){
        downloadableCheck.checked = true
    }
    if(productData.virtual){
        virtualCheck.checked = true
    }

    if(productData["manage_stock"]){
        manageStockCheck.checked = true
    }
    
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
        categoryCheckbox.id = sortedCategories[i].id
        categoryCheckbox.classList.add("category-checkbox")
        categoryCheckbox.classList.add("checkbox")
        categoryCheckbox.dataset.parent = 0
        categoryCheckbox.dataset.name = sortedCategories[i].name
        categoryCheckbox.dataset.id = sortedCategories[i].id

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
                subcategoryCheckbox.id = subcategories[j].id
                subcategoryCheckbox.classList.add("subcategory-checkbox")
                subcategoryCheckbox.classList.add("checkbox")
                subcategoryCheckbox.dataset.parent = subcategories[j].parent
                subcategoryCheckbox.dataset.name = subcategories[j].name
                subcategoryCheckbox.dataset.id = subcategories[j].id

                subCheckboxContainer.appendChild(subcategoryCheckbox)

                let subcategoryLabel = document.createElement("label")
                subcategoryLabel.innerText = subcategories[j].name
                subcategoryLabel.id = subcategories[j].id
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
                id: categories[i].id,
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
            if( categories[i].parent && category.id == categories[i].parent){
                let child = {
                    name: categories[i].name,
                    id: categories[i].id,
                    children: [],
                    parent: categories[i].parent
                }
                categoriesList[index].children.push(child)
            }
        })

    }

    return categoriesList
    
}

function showImage(event){
    let output = document.getElementById("img")
    output.src = URL.createObjectURL(event.target.files[0])
    imageSet = true
}

function saveClicked(event){
    // event.preventDefault()
    const errors = document.getElementById("errors")

    errors.innerHTML = ""

    if(!imageSet){
        let error = document.createElement("div")
        error.appendChild(document.createTextNode("Please set an image."))
        errors.appendChild(error)
    }

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