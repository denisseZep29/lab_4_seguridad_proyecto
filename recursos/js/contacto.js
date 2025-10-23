const contactForm = document.querySelector('.contact-form');
const messageBox = document.createElement('p');
messageBox.classList.add('message');
contactForm.appendChild(messageBox);

contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(contactForm);

    // Enviar la solicitud al servidor
    const response = await fetch('/acciones/contacto.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    // Mostrar mensaje según el resultado
    if (result.status === "success") {
        messageBox.classList.add('success');
        messageBox.classList.remove('error');
    } else {
        messageBox.classList.add('error');
        messageBox.classList.remove('success');
    }
    messageBox.textContent = result.message;
});