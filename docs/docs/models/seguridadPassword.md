# funciones

###	get_usuarios_status_passwords
- Realiza la conexión con la base de dato y hace una consulta de los usuarios personalizada
- Se recorre la fila de los datos guardando en un array datos del usuario nombre, apellido y correo, además de que características de una contraseña se cumplen
        - Comprueba si la contraseña tiene mayúsculas
        - Comprueba si la contraseña tiene minúsculas
        - Comprueba si la contraseña tiene números
        - Comprueba si la contraseña tiene caracteres especiales
        - Comprueba que la contraseña tenga más de 7 caracteres
        - Comprueba que la última actualización o creación sea de al menos 3 meses
- El resultado de los usuarios es retornado en un array 
