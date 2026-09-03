// Browser validation helps immediately; PHP validates every submission as well.
const signupForm = document.getElementById("signup");

if (signupForm) {
    const password = document.getElementById("password");
    const confirmation = document.getElementById("password_confirmation");

    function validatePasswords() {
        const containsLetterAndNumber = /[a-z]/i.test(password.value) && /[0-9]/.test(password.value);
        password.setCustomValidity(
            password.value && !containsLetterAndNumber
                ? "Salasanassa pitää olla vähintään yksi kirjain ja yksi numero."
                : ""
        );
        confirmation.setCustomValidity(
            confirmation.value && confirmation.value !== password.value
                ? "Salasanat eivät täsmää."
                : ""
        );
    }

    password.addEventListener("input", validatePasswords);
    confirmation.addEventListener("input", validatePasswords);
}
