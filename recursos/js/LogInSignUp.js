const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
const forgotPasswordLink = document.getElementById('forgotPasswordLink');
const backToSignIn = document.getElementById('backToSignIn');

// Mostrar el formulario de "Forgot Password"
forgotPasswordLink.addEventListener('click', (e) => {
    e.preventDefault();
    container.classList.add("forgot-password-active");
});

// Volver al formulario de inicio de sesión
backToSignIn.addEventListener('click', (e) => {
    e.preventDefault();
    container.classList.remove("forgot-password-active");
});

// Mostrar el formulario de registro
signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
    container.classList.remove("forgot-password-active");
});

// Mostrar el formulario de inicio de sesión
signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
    container.classList.remove("forgot-password-active");
});


// Mostrar el formulario correspondiente según el parámetro en la URL
document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const action = urlParams.get('action');

    if (action === 'signup') {
        container.classList.add("right-panel-active");
    } else if (action === 'login') {
        container.classList.remove("right-panel-active");
    }
});
