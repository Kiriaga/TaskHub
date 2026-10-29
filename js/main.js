// js/main.js - Obtención y despliegue en tiempo real de Fecha y Hora
document.addEventListener('DOMContentLoaded', () => {
    updateDateTime();
    setInterval(updateDateTime, 1000);
});

function updateDateTime() {
    const dateElement = document.getElementById('current-date');
    const timeElement = document.getElementById('current-time');

    if (dateElement && timeElement) {
        const now = new Date();
        
        const optionsDate = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        dateElement.textContent = now.toLocaleDateString('es-ES', optionsDate);
        timeElement.textContent = now.toLocaleTimeString('es-ES');
    }
}