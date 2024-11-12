// guest_login.js

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    form.addEventListener("submit", function (event) {
        let isValid = true;
        let errorMessage = "";

        // Email validation
        const email = emailInput.value;
        if (!email.endsWith("@gmail.com")) {
            isValid = false;
            errorMessage += "Email must be a @gmail.com address.\n";
        }

        // Password validation
        const password = passwordInput.value;
        if (password.length < 8) {
            isValid = false;
            errorMessage += "Password must be at least 8 characters long.\n";
        }

        // If validation fails, prevent form submission and display errors
        if (!isValid) {
            alert(errorMessage);
            event.preventDefault();
        }
    });
});
