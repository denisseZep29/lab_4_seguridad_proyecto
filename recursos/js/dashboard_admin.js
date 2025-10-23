document.addEventListener("DOMContentLoaded", function () {
    fetch("../api/dashboard_stats.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Actualizar estadísticas en las tarjetas
                document.querySelector(".card:nth-child(1) p strong").textContent = data.total_users;
                document.querySelector(".card:nth-child(2) p strong").textContent = data.total_courses;

                // Generar gráficos dinámicos
                renderMonthlyUserStats(data.user_monthly_stats);
                renderMonthlyCourseStats(data.course_monthly_stats);
            } else {
                console.error("Error al obtener estadísticas:", data.error);
            }
        })
        .catch((error) => console.error("Error en la solicitud:", error));
});

function renderMonthlyUserStats(userStats) {
    const ctx = document.getElementById("userChart").getContext("2d");
    const labels = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Usuarios Registrados",
                    data: Object.values(userStats),
                    backgroundColor: "#406ff3",
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
}

function renderMonthlyCourseStats(courseStats) {
    const ctx = document.getElementById("courseChart").getContext("2d");
    const labels = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Cursos Creados",
                    data: Object.values(courseStats),
                    backgroundColor: "rgba(64, 111, 243, 0.2)",
                    borderColor: "#406ff3",
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    });
}
