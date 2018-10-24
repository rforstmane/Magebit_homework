"use strict";
let inputs =  document.getElementsByClassName("input-group__input")

for(let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener("focus", focusHandler)
    inputs[i].addEventListener("blur", blurHandler)

    if (inputs[i].value !== "") {
        inputs[i].parentElement.classList.add("input-group--active")
    }
}

function focusHandler(){
    this.parentElement.classList.add("input-group--active")
}

function blurHandler(){
    if (this.value === "") {
        this.parentElement.classList.remove("input-group--active")
    }
}

$("#js-form-right").on("click", function() {
    $(".auth-form__modal").addClass("auth-form__modal--login")
    $(".auth-form__modal").removeClass("auth-form__modal--signup")

})

$("#js-form-left").on("click", function() {
    $(".auth-form__modal").addClass("auth-form__modal--signup")
    $(".auth-form__modal").removeClass("auth-form__modal--login")

})