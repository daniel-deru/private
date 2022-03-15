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