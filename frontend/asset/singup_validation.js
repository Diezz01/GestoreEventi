document.addEventListener("DOMContentLoaded", function(){
    const username = document.getElementById("username");
    const usernameError = document.getElementById("usernameError");

    const email = document.getElementById("email");
    const emailError = document.getElementById("emailError");

    const pwd = document.getElementById("password");
    const pwdError = document.getElementById("pwdError");

    const submit_button = document.getElementById("submit_button");

    let isUsernameValid = false;
    let isEmailValid = false;
    let isPwdValid = false;

    function checkFormValid() {
        if (isUsernameValid && isEmailValid && isPwdValid) {
            submit_button.disabled = false;
        } else {
            submit_button.disabled = true;
        }
    }

    username.addEventListener("input", function() {
        if(username.value.trim().length < 3){
            usernameError.textContent = "Minimo 3 caratteri";
            usernameError.style.color = "red";
            isUsernameValid = false;
        }else{
            isUsernameValid = true;
            usernameError.textContent = "";
        }
        checkFormValid();
    });

    email.addEventListener("input", function () {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regex.test(email.value)) {
            emailError.textContent = "Email non valida";
            emailError.style.color = "red";
            isEmailValid = false;
        } else {
            emailError.textContent = "";
            isEmailValid = true;
        }
        checkFormValid();
    });

    pwd.addEventListener("input", function () {
        if (pwd.value.length < 6) {
            pwdError.textContent = "Minimo 6 caratteri";
            pwdError.style.color = "red";
            isPwdValid = false;
        } else {
            pwdError.textContent = "";
            isPwdValid = true;
        }
        checkFormValid();
    });
});
