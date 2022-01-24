let categoryItems = []
let categoryIDs = []
let tagItems = []
let imageSet = false
// document.addEventListener("DOMContentLoaded", (event) => {

    
    const categorySelect = document.getElementById("category")
    const categoryList = document.getElementById("category-items")

    const tagBtn = document.getElementById("tag-button")
    const tagList = document.getElementById("tag-items")


    categorySelect.addEventListener("change",(event) => addCategories(event))
    tagBtn.addEventListener("click", () => addTags())


    const saveBtn = document.getElementById("save-btn")
    saveBtn.addEventListener("click", () => saveClicked())

    const imageUpload = document.getElementById("image")
    imageUpload.addEventListener("change", (event) => showImage(event))

    addCategoryListener()
    addTagListener()



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
        }
        
    }

    function deleteCategories(event){
        categoryItems = categoryItems.filter(item => item != event.target.innerText)
        categoryIDs = categoryIDs.filter(item => item != event.target.dataset.id)
        console.log("Inside the delete categories function", categoryIDs)
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

        let hiddenTags = document.getElementById("hidden-tags")
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

        const errors = document.getElementById("errors")

        while(errors.firstChild){
            errors.removeChild(errors.firstChild)
        }

        if(!imageSet){
            let error = document.createElement("div")
            error.appendChild(document.createTextNode("Please set an image."))
            errors.appendChild(error)
        }

        const hiddenCategories = document.getElementById("hidden-categories")
        const categories = categoryIDs.join("%")
        hiddenCategories.value = categories
            
        if(categoryIDs.length == 0 || categoryItems.length == 0){
            console.log("Inside the if check")
            hiddenCategories.value = ""
        }

    }


    // This function adds the categories displayed to the categoryItems and categoryIDs arrays and adds the click event listener
    function addCategoryListener(){
        let categories = categoryList.children
        for(let i = 0; i < categories.length; i++){
            
            categories[i].addEventListener('click', (event) => deleteCategories(event))
            categoryItems.push(categories[i].innerText)
            categoryIDs.push(categories[i].id)
        }
        displayCategories()
    }

    // This function adds the tags from the displayed list to the tagItems array and adds the delete event listener
    function addTagListener(){
        let tags = tagList.children
        for(let i = 0; i < tags.length; i++){
            tags[i].addEventListener('click', (event) => deleteTags(event))
            tagItems.push(tags[i].innerText)
        }

        displayTags()
    }