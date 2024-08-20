let notificaciones = [];
function addNotification(asunto, mensaje, url) {
        const formData = new URLSearchParams();
        formData.append('asunto', asunto);
        formData.append('mensaje', mensaje);
        formData.append('url', url);

        fetch('../../controller/noticia.php?op=add_noticia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData.toString()
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
document.addEventListener("DOMContentLoaded", function() {
    const notificationCountElement = document.getElementById("notification-count");
    const notificationListElement = document.getElementById("notification-list");

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

    fetchNotifications();
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
            notifElement.href = notif.url;
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
            updateNotificationCount();
            renderNotifications();

        const formData = new URLSearchParams();
        formData.append('noticia_id', notificaciones[index].id );

            fetch('../../controller/noticia.php?op=read_noticia', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al marcar como leída: ' + response.status);
                }
            })
            .catch(error => console.error('Error al marcar como leída:', error));
        }
    }

});
