document.addEventListener("DOMContentLoaded", function() {
    const notificationCountElement = document.getElementById("notification-count");
    const notificationListElement = document.getElementById("notification-list");

    let notificaciones = [];

    function fetchNotifications() {
        fetch('../../controller/noticia.php?op=get_noticia')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                notificaciones = data;
                updateNotificationCount();
                renderNotifications();
            })
            .catch(error => {
                console.error('Error al obtener las notificaciones:', error);
            });
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
            updateNotificationCount(); // Actualiza el contador después de marcar como leído
            renderNotifications(); // Vuelve a renderizar las notificaciones
        }
    }

    function addNotification(asunto, mensaje, url) {
        const nuevaNotificacion = {
            asunto: asunto,
            mensaje: mensaje,
            leido: false,
            url: url
        }; 
        fetch('../../controller/noticia.php?op=add_noticia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(nuevaNotificacion)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud POST: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Notificación guardada con éxito:', data);
            notificaciones.push(data);
            updateNotificationCount();
            renderNotifications();
        })
        .catch(error => {
            console.error('Error al guardar la notificación:', error);
        });
    }


    window.addNotification = addNotification;
});
