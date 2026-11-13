// Se obtiene el nombre del usuario desde el HTML
const userName = document.getElementById('user_name').textContent.trim();

// Se comprueba si está el nombre del usuario
if (userName && userName !== 'Nombre') {
    document.getElementById('nameButton').style.display = 'inline';  
} else {
    document.getElementById('nameButton').style.display = 'none'; 
}

// Espera a que todo el contenido del DOM esté completamente cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", function () {
    // Se obtiene el botón con el ID "nameButton"
    const nameButton = document.getElementById("nameButton");
    if (nameButton) {
        nameButton.addEventListener("click", function () {
            window.location.href = "profile.php";
        });
    }
});

    function markAsRead(notificationId) {
        // Se redirige al servidor para marcar la notificación como leída
        window.location.href = "?notification_id=" + notificationId;
    }
