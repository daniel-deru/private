const filterBtn = document.getElementById("filter")
filterBtn.addEventListener("click", (event) => filterProducts(event))
let productsArray = Array.from(document.querySelectorAll(".product-container"))

const resetBtn = document.getElementById("reset")
resetBtn.addEventListener("click", (event) => resetFilter(event))


function filterProducts(){
    const productContainer = document.getElementById("product-grid")
    const productName = document.getElementById("filter-name")
    const productPrice = document.getElementById("filter-price")
    const productRange = document.getElementById("filter-range")
    let products = productsArray
    
    if(productName.value !== ""){
      products = products.filter(product => product.dataset.name.includes(productName.value))
    }
    if(productPrice.value !== ""){
        if(productRange.value == "more"){
            products = products.filter(product => parseFloat(product.dataset.price) >= parseFloat(productPrice.value))
        }
        else if(productRange.value == "less"){
            products = products.filter(product => parseFloat(product.dataset.price) <= parseFloat(productPrice.value))
        }
    }

    productContainer.innerHTML = ""
    productContainer.append(...products)
}

function resetFilter(event){
    const productContainer = document.getElementById("product-grid")
    productContainer.innerHTML = ''
    productContainer.append(...productsArray)
}

// Set the dynamic color with jQuery
jQuery(document).ready($ => {
    const color = $("#brand-color").val()

    $('header').css('background-color', color)
    $('select option').css('background-color', color)
    $('.product-container a').css('background-color', color)
    $('#pagination a:active').css({
        'color': color,
        'border': `2px solid ${color}`
    })
    $('#pagination a:visited').css({
        'color': color,
        'border': `2px solid ${color}`
    })
})