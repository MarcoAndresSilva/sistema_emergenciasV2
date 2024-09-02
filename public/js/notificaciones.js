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

    setInterval(fetchNotifications, 10000);

    // Detectar clic en cualquier botón y ejecutar fetchNotifications
    document.addEventListener("click", function(event) {
        if (event.target.tagName.toLowerCase() === "button") {
            fetchNotifications();
        }
    });

    fetchNotifications();
    // Actualiza el contador de notificaciones
    function updateNotificationCount() {
        const unreadNotifications = notificaciones.filter(n => !n.leido);
        notificationCountElement.textContent = unreadNotifications.length;
    }

    // Muestra las notificaciones en el menú
    function renderNotifications() {
        const now = new Date();
        const time_hrs_limite_live_noticia = 8;
        notificationListElement.innerHTML = '';
        notificaciones.forEach((notif, index) => {
            if (!notif.leido) {
                // Mostrar notificación no leída
                const notifElement = document.createElement("a");
                notifElement.className = "dropdown-item font-weight-bold";
                notifElement.href = notif.url;
                notifElement.innerHTML = `<i class="fa fa-info-circle" aria-hidden="true"></i> ${notif.mensaje}`;
                notifElement.addEventListener("click", () => markAsRead(index));
                notificationListElement.appendChild(notifElement);
            } else if (notif.leido && notif.fecha_leido) {
                // Calcular la diferencia de horas entre la fecha actual y la fecha de lectura
                const fechaLeido = new Date(notif.fecha_leido);
                const diffTime = Math.abs(now - fechaLeido);
                const diffHours = Math.ceil(diffTime / (1000 * 60 * 60)); 

                if (diffHours <= time_hrs_limite_live_noticia) {
                    // Mostrar notificación leída si está dentro del tiempo límite
                    const notifElement = document.createElement("a");
                    notifElement.className = "dropdown-item";
                    notifElement.href = notif.url;
                    notifElement.innerHTML = `<i class="fa fa-info-circle" aria-hidden="true"></i> ${notif.mensaje}`;
                    notifElement.addEventListener("click", () => markAsRead(index));
                    notificationListElement.appendChild(notifElement);
                }
            }
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
