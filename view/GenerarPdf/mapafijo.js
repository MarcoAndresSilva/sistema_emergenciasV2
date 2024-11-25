// funcion para generar imagenes de mapas
export function mapafijo(latitud, longitud, size = "350x250", zoom=16) {
     const claveApi = "AIzaSyAQrYCFSz7Q-a-WONxo4yymu9SAPgmaA6c";
     const markers = `color:red|label:X|${latitud},${longitud}`;
     const style = "feature:poi|visibility:off&style=feature:business|visibility:off";
     const urlMapa = `https://maps.googleapis.com/maps/api/staticmap?center=${latitud},${longitud}&zoom=${zoom}&size=${size}&style=${style}&markers=${markers}&key=${claveApi}`;
     return urlMapa;
}


