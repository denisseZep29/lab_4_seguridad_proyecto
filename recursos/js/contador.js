// Función de contador
function animateCounter(element, endValue) {
    let startValue = 0;
    let duration = 2000; // Duración de la animación en milisegundos
    let increment = endValue / (duration / 16);

    function updateCounter() {
        startValue += increment;
        if (startValue < endValue) {
            element.innerText = Math.floor(startValue) + "+";
            requestAnimationFrame(updateCounter);
        } else {
            element.innerText = endValue + "+";
        }
    }

    updateCounter();
}

// Observador de intersección para activar el contador cuando esté en la vista
const statsObserver = new IntersectionObserver(
    (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumberElements = document.querySelectorAll(".stat-number");
                statNumberElements.forEach(stat => {
                    let endValue = parseInt(stat.textContent);
                    animateCounter(stat, endValue);
                });
                observer.disconnect(); // Dejar de observar después de activar el contador
            }
        });
    },
    { threshold: 0.5 } // Activar cuando el 50% de la sección esté visible
);

// Iniciar el observador en la sección de estadísticas
document.addEventListener("DOMContentLoaded", () => {
    const statsSection = document.querySelector(".right-content");
    statsObserver.observe(statsSection);
});
