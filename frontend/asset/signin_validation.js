document.addEventListener("DOMContentLoaded", function () {

    const username = document.getElementById("username");
    const password = document.getElementById("password");

    const usernameError = document.getElementById("usernameError");
    const passwordError = document.getElementById("pwdError");

    const submit_button = document.getElementById("submit_button");

    let isUsernameValid = false;
    let isPasswordValid = false;

    function checkForm() {
        submit_button.disabled = !(isUsernameValid && isPasswordValid);
    }

    username.addEventListener("input", function () {

        if (username.value.trim().length < 3) {
            usernameError.textContent = "Minimo 3 caratteri";
            usernameError.style.color = "red";
            isUsernameValid = false;
        } else {
            usernameError.textContent = "";
            isUsernameValid = true;
        }

        checkForm();
    });

    password.addEventListener("input", function () {

        if (password.value.trim().length < 6) {
            passwordError.textContent = "Minimo 6 caratteri";
            passwordError.style.color = "red";
            isPasswordValid = false;
        } else {
            passwordError.textContent = "";
            isPasswordValid = true;
        }

        checkForm();
    });

});