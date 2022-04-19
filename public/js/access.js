function passwordVisible(){
    const iconButton = document.getElementById("icon-button")
    const icon = document.getElementById("show-password")
    const passwordField = document.getElementById("password")

    iconButton.addEventListener("click", () => {
        console.log("Icon clickd")
        console.log(icon.classList[1])
        if(icon.classList[2] == "show"){

            icon.classList.remove("show")
            icon.classList.add("hide")

            passwordField.type = "text"

        } else if(icon.classList[2] == "hide"){

            icon.classList.remove("hide")
            icon.classList.add("show")

            passwordField.type = "password"
        }
    })
}

passwordVisible()
console.log("hello")
//  Set the dynamic color with jQuery
jQuery(document).ready($ => {

    const color = $('#brand-color').val()
    console.log(color)

    $("#wp-smart-commerce-brand-logo").css('background-color', color)
    $('label').css('color', color)
    $('#login-form > div > *:not(input[type="submit"], button)').css({
        'color': color,
        'outline-color': color
    })
    $('#login-form > div input:not(input[type="submit"])').css('border', `2px solid ${color}`)
    $('#login-form > div input[type="submit"]').css({
        'border': `2px solid ${color}`,
        'background-color': color
    })
})