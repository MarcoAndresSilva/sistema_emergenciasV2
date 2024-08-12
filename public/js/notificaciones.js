document.addEventListener("DOMContentLoaded", function() {
    const notificationCountElement = document.getElementById("notification-count");
    const notificationListElement = document.getElementById("notification-list");

    // Recupera las notificaciones del localStorage o usa un valor predeterminado
    let notificaciones = JSON.parse(localStorage.getItem('notificaciones')) || [
        { asunto: "Nuevos Eventos", mensaje: "Tienes Eventos sin Derivar", leido: false, url: "../ControlEventos/" },
        { asunto: "Actualización de perfil", mensaje: "Tu perfil ha sido actualizado", leido: true, url: "../perfil" },
    ];

    // Guarda las notificaciones en el localStorage
    function saveNotifications() {
        localStorage.setItem('notificaciones', JSON.stringify(notificaciones));
    }

    // Actualiza el contador de notificaciones
    function updateNotificationCount() {
        const unreadNotifications = notificaciones.filter(n => !n.leido);
        notificationCountElement.textContent = unreadNotifications.length;
    }

    // Muestra las notificaciones en el menú
    function renderNotifications() {
        notificationListElement.innerHTML = '';
        notificaciones.forEach((notif, index) => {
            const notifElement = document.createElement("a");
            notifElement.className = "dropdown-item";
            notifElement.href = notif.url; // Usa el campo 'url'
            notifElement.innerHTML = `<i class="fa fa-info-circle" aria-hidden="true"></i> ${notif.mensaje}`;
            if (!notif.leido) {
                notifElement.classList.add("font-weight-bold");
            }
            // Agrega el manejador de eventos para marcar la notificación como leída
            notifElement.addEventListener("click", () => markAsRead(index));
            notificationListElement.appendChild(notifElement);
        });
    }

    // Marca una notificación como leída
    function markAsRead(index) {
        if (!notificaciones[index].leido) {
            notificaciones[index].leido = true;
            saveNotifications(); // Guarda las notificaciones actualizadas
            updateNotificationCount(); // Actualiza el contador después de marcar como leído
            renderNotifications(); // Vuelve a renderizar las notificaciones
        }
    }

    // Función para agregar una nueva notificación
    function addNotification(asunto, mensaje, url) {
        const nuevaNotificacion = {
            asunto: asunto,
            mensaje: mensaje,
            leido: false,
            url: url
        };
        notificaciones.push(nuevaNotificacion); // Agrega la nueva notificación al array
        saveNotifications(); // Guarda el nuevo estado en localStorage
        updateNotificationCount(); // Actualiza el contador
        renderNotifications(); // Vuelve a renderizar las notificaciones
    }

    // Ejemplo de uso: Agrega una nueva notificación

    // Inicializa las notificaciones
    updateNotificationCount();
    renderNotifications();
    window.addNotification = addNotification;
});
