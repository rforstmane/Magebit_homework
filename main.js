let inputs = document.getElementsByClassName("input-group__input");

    for(let i=0; i<inputs.length; i++){
        $(inputs[i]).on("focus", focusHandler);
        $(inputs[i]).on("blur", blurHandler);

        if (inputs[i].value !== "") {
            $(inputs[i]).parent.addClass("input-group--active");
        }
    }

function focusHandler() {
    $(this).parent().addClass("input-group--active");
}
function blurHandler() {
    if (this.value === ""){
        $(this).parent().removeClass("input-group--active");
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