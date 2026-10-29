// js/calculator.js - Lógica de estimación de proyectos
function calculateProjectHours() {
    const tasks = parseFloat(document.getElementById('calc-tasks').value);
    const hours = parseFloat(document.getElementById('calc-hours').value);
    const devs = parseFloat(document.getElementById('calc-devs').value);
    const resultDiv = document.getElementById('calc-result');

    if (isNaN(tasks) || isNaN(hours) || isNaN(devs) || tasks <= 0 || hours <= 0 || devs <= 0) {
        resultDiv.className = "mt-3 alert alert-danger small fw-bold";
        resultDiv.textContent = "Por favor ingrese valores numéricos válidos mayores a cero.";
        resultDiv.classList.remove('d-none');
        return;
    }

    const totalHours = (tasks * hours) / devs;
    const totalDays = (totalHours / 8).toFixed(1); // Jornada de 8 horas

    resultDiv.className = "mt-3 alert alert-success small fw-bold";
    resultDiv.innerHTML = `Total estimado: <strong>${totalHours.toFixed(1)} hrs</strong> por persona (~${totalDays} días laborales).`;
    resultDiv.classList.remove('d-none');
}

function resetCalculator() {
    document.getElementById('calc-form').reset();
    const resultDiv = document.getElementById('calc-result');
    resultDiv.classList.add('d-none');
}