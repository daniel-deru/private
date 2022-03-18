// Global variable to check if the image is set
let imageSet = false
let tagsArray = []
let sortedCategories = []
let imageArray = []


// const imageUpload = document.getElementById("image")
const saveBtn = document.getElementById("save-btn")
const tagBtn = document.getElementById("tag-btn")
const chooseBtn = document.getElementById("image-selector")
const imageContainer = document.getElementById("image-viewer")
const imageInput = document.getElementById("image-urls")
const deleteBtns = document.getElementsByClassName("delete-icon")

saveBtn.addEventListener("click", (event) => saveClicked(event))
// imageUpload.addEventListener("change", (event) => showImage(event))
tagBtn.addEventListener("click", () => addTags())
chooseBtn.addEventListener("click", (e) => openMedia(e))

parseCategories()
addDeleteListener()

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
    console.log("This is the event and files from the image upload event listener", event.target.files)
    output.src = URL.createObjectURL(event.target.files[0])
    imageSet = true
}

function saveClicked(event){
    // event.preventDefault()
    const errors = document.getElementById("errors")

    errors.innerHTML = ""

    // if(!imageSet){
    //     let error = document.createElement("div")
    //     error.appendChild(document.createTextNode("Please set an image."))
    //     errors.appendChild(error)
    // }

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
        const existingImage = imageArray.filter(image => image.url == attachment.url)
        if(existingImage.length <= 0) imageArray.push({id: attachment.id, url: attachment.url})

        displayImages()
       
    })
}

function displayImages(){
    imageContainer.innerHTML = ""
    console.log(imageArray)
    if(imageArray){
        for(let image of imageArray){

            // create image container
            const imageItem = document.createElement("div")

            // create image
            const imgElement = `<img src="${image.url}"/>`

            // Create the delete icon
            const deleteIcon = `<div class="delete-icon">
                                    <i class="fa fa-times"></i>
                                </div>`

            const featuredRadio = `<input type="radio" name="featured" value="${image.id}" id="${image.id}" ${image.id == imageArray[0].id ? "checked" : ""}>`

            // Put the image in the container
            imageItem.innerHTML = featuredRadio + imgElement + deleteIcon

            // Put the container in the DOM
            imageContainer.appendChild(imageItem)
        }
    }

    // Set the hidden input data to the image urls to process on server
    const imageIDs = imageArray.map(image => image.id).join(";")
    imageInput.value = imageIDs
    addDeleteListener()
}   


function handleDeleteImage(e){
    // Get the img tag and  the src value of that image
    let targetNode = e.target.parentElement
    if(targetNode.nodeName !== "DIV") targetNode = targetNode.parentElement
    const imgURL = targetNode.previousSibling.src
    // remove image from image array
    imageArray = imageArray.filter(image => image.url !== imgURL)
    // Rerender the images
    displayImages()
}

function addDeleteListener(){
    for(let i = 0; i < deleteBtns.length; i++){
        deleteBtns[i].addEventListener("click", (e) => handleDeleteImage(e))
    }
}
