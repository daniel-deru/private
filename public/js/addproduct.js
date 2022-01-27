// Global variable to check if the image is set
let imageSet = false
let tagsArray = []

const imageUpload = document.getElementById("image")
const saveBtn = document.getElementById("save-btn")
const tagBtn = document.getElementById("tag-btn")


saveBtn.addEventListener("click", () => saveClicked())
imageUpload.addEventListener("change", (event) => showImage(event))
tagBtn.addEventListener("click", () => addTags())


displayCategories()

function addTags(){
    const tagInput = document.getElementById("")
    let tag = tagInput.value
    tagsArray.push(tag)

    displayTags()
}

function deleteTags(event){
    tagsArray = tagsArray.filter(tag => tag == event.innerText)
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

            tagContainer.push(tagElement)
        }
    }
}


// This function will display the categories
function displayCategories(){
    const categoryHiddenData = document.getElementById("php-categories-data")
    const categoryContainer = document.getElementById("categories-checkboxes")
    let categories = JSON.parse(categoryHiddenData.value)

    let sortedCategories = sortCategories(categories)

    for(let i = 0; i < sortedCategories.length; i++){

        let checkboxContainer = document.createElement("div")
        checkboxContainer.classList.add("checkbox-container")

        let categoryCheckbox = document.createElement('input')
        categoryCheckbox.type = "checkbox"
        categoryCheckbox.id = sortedCategories[i].id
        categoryCheckbox.classList.add("category-checkbox")
        categoryCheckbox.classList.add("checkbox")

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
                categoryCheckbox.classList.add("checkbox")

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
    console.log(categoriesList)

    return categoriesList
    
}

function showImage(event){
    let output = document.getElementById("img")
    output.src = URL.createObjectURL(event.target.files[0])
    imageSet = true
}

function saveClicked(){

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