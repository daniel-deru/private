// Global variable to check if the image is set
let imageSet = false


const saveBtn = document.getElementById("save-btn")
saveBtn.addEventListener("click", () => saveClicked())

const imageUpload = document.getElementById("image")
imageUpload.addEventListener("change", (event) => showImage(event))


displayCategories()


// This function will display the categories
function displayCategories(){
    const categoryHiddenData = document.getElementById("php-categories-data")
    const categoryContainer = document.getElementById("categories-checkboxes")
    let categories = JSON.parse(categoryHiddenData.value)

    sortCategories(categories)
}


function sortCategories(categories){
    categoriesList = []
    for(let i = 0; i < categories.length; i++){
        console.log(categories[i].name, categories[i].parent, categories[i].parent == 0)
        // if(categories[i].parent === 0 || categories[i].name == "Uncategorized"){
        //     let parent = {
        //         name: categories[i].name,
        //         id: categories[i].id,
        //         children: [],
        //         parent: categories[i].parent
        //     }
        //     console.log("This is a top level category", categories[i])
        //     categoriesList.push(parent)
        //     categories.splice(i, 1)
        // }
    }

    // for(let i = 0; i < categories.length; i++){
    //     console.log(categories[i])
    //     categoriesList.filter((category, index) => {
    //         if(category.id == categories[i].parent){
    //             let child = {
    //                 name: categories[i].name,
    //                 id: categories[i].id,
    //                 children: [],
    //                 parent: categories[i].parent
    //             }
    //             categoriesList[index].children.push(child)
    //             categories.splice(i, 1)
    //             // return category.id == categories[i].parent
    //         }
            
    //     })

    // }

    // console.log("This is the subcategories", categories)
    // console.log("This is the parent categories", categoriesList)
    
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