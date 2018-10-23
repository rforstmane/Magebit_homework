"use strict";
let inputs =  document.getElementsByClassName("input-group__input")
console.log(inputs);

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

// $('.login--signup').hide();
$('.button').on('click',
  function() {
    $('.login--login, .form').toggle(2000);
  }
);

