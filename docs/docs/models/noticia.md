## add_noticia

### Funcionamiento

La función `add_noticia` se encarga de insertar una nueva noticia en la base de datos. 

Recibe tres parámetros:

- `asunto` (string): El asunto de la noticia.
- `mensaje` (string): El mensaje de la noticia.
- `url` (string, opcional): Un enlace asociado a la noticia. Puede ser `null` si no se proporciona.

La función construye una consulta SQL para insertar estos valores en la tabla `tm_noticia` y luego ejecuta la consulta utilizando el método `ejecutarAccion`.

### Return

La función devuelve un array con el estado de la operación y un mensaje correspondiente:

- **Éxito**:
  ```js
  [
      'status' => 'success',
      'message' => "Se agregó la noticia"
  ]
  ```

- **Advertencia** (en caso de error):
  ```js
  [
      'status' => 'warning',
      'message' => "Problemas al agregar dato"
  ]
  ```

### Código Original

```php
<?php
public function add_noticia(string $asunto, string $mensaje, string $url=null){
  $sql = "INSERT INTO tm_noticia (asunto, mensaje, url) VALUES (:asunto,:mensaje,:url)";
  $params = [
    ':asunto'=> $asunto,
    ':mensaje'=> $mensaje,
    ':url'=> $url,
  ];
  $result = $this->ejecutarAccion($sql, $params);
  if ($result){
      return [
          'status' => 'success',
          'message' => "Se agregó la noticia"
      ];
  }
  return [
      'status' => 'warning',
      'message' => "Problemas al agregar dato"
  ];
}
```

## lista_posibles_envios_por_ids

### Funcionamiento

La función `lista_posibles_envios_por_ids` recupera un conjunto de registros de la tabla `tm_usuario` basado en una lista de identificadores y un nombre de columna.

Recibe dos parámetros:

- `ids` (string): Una cadena de identificadores separados por comas (por ejemplo, `"1,2,3"`). Estos identificadores se utilizan en la cláusula `IN` de la consulta SQL.
- `id_name` (string): El nombre de la columna en la tabla `tm_usuario` que se utilizará para la condición de filtrado (por ejemplo, `"usu_id"` o `"usu_tipo"`).

La función construye una consulta SQL para seleccionar todos los registros de la tabla `tm_usuario` donde el valor de `id_name` esté en la lista de `ids`. Luego, ejecuta la consulta utilizando el método `ejecutarConsulta`.


> [!WARNING]
> Debido a que `ids` y `id_name` son parámetros de la consulta SQL, se requiere la preparación de la consulta para evitar problemas de inyección SQL.

### Return

La función devuelve todos los datos de tm_usuario que coincida con la `:id_name` y `:ids`

### Código Original

```php
<?php
public function lista_posibles_envios_por_ids(string $ids, string $id_name){
  $sql = "SELECT * FROM tm_usuario WHERE :id_name IN ( :ids );";
  $params = [
    ":id_name" => $id_name,
    ":ids" => $ids,
  ];
  return $this->ejecutarConsulta($sql, $params);
}
```


## check_mensaje_leido

### Funcionamiento

La función `check_mensaje_leido` actualiza el estado de lectura de una noticia para un usuario específico. Marca la noticia como leída y establece la fecha de lectura en la base de datos.

Recibe dos parámetros:
- `noticia_id` (int): El identificador de la noticia que se marca como leída.
- `usuario_id` (int): El identificador del usuario que marca la noticia como leída.

La función obtiene la fecha y hora actual en formato `Y-m-d H:i:s`. Luego, construye una consulta SQL para actualizar el campo `leido` a `1` y el campo `fecha_lectura` con la fecha actual en la tabla `tm_noticia_usuario`. La consulta se ejecuta solo si el `usu_id` y `noticia_id` coinciden con los valores proporcionados.

### Return

La función devuelve un array con el estado de la operación y un mensaje correspondiente:

- **Éxito**:
  ```js
  [
      'status' => 'success',
      'message' => "Se marca mensaje como leído"
  ]
  ```

- **Error** (en caso de fallo):
  ```php
  [
      'status' => 'error',
      'message' => "Problemas al actualizar estado del mensaje noticia"
  ]
  ```

### Código Original

```php
<?php
public function check_mensaje_leido($noticia_id, $usuario_id){
  $fecha_lectura = date('Y-m-d H:i:s');
  $sql = "UPDATE tm_noticia_usuario SET leido=1, fecha_lectura=:fecha_lectura WHERE usu_id=:usuario_id AND noticia_id=:noticia_id";
  $params = [
    ":usuario_id" => $usuario_id,
    ":noticia_id" => $noticia_id,
    ":fecha_lectura" => $fecha_lectura,
  ];
  $result = $this->ejecutarAccion($sql, $params);
  if ($result){
    return [
      'status' => 'success',
      'message' => "Se marca mensaje como leído"
    ];
  }
  return [
    'status' => 'error',
    'message' => "Problemas al actualizar estado del mensaje noticia"
  ];
}
```

## lista_posibles_envios_por_ids

Obtiene una lista de registros de la tabla `tm_usuario` cuyas IDs coinciden con los proporcionados. Esta función utiliza una consulta preparada para evitar problemas de inyección SQL y para manejar múltiples IDs de manera segura.

### Funcionamiento

1. **Conexión a la Base de Datos**: Se establece una conexión a la base de datos mediante la función `parent::conexion()`.
2. **Preparación de Consulta**: La cadena de IDs proporcionada se divide en un array utilizando `explode()`. Se generan los placeholders necesarios para la consulta utilizando `array_fill()` y `implode()`.
3. **Ejecución de Consulta**: Se prepara la consulta SQL con placeholders (`?`) y se ejecuta utilizando el array de IDs.
4. **Obtención de Resultados**: Se recuperan los resultados de la consulta en formato de array asociativo usando `fetchAll(PDO::FETCH_ASSOC)`.

### Parámetros

- `string $ids`: Una cadena de IDs separadas por comas (e.g., "1,2,3").
- `string $id_name`: El nombre de la columna en la tabla `tm_usuario` que se usará para comparar con las IDs proporcionadas.

### Return

- `array`: Un array asociativo con los registros que coinciden con los IDs proporcionados, donde cada elemento es un array asociativo representando una fila de la tabla `tm_usuario`.

## eliminarDuplicadosPorUsuId

Elimina duplicados en base al campo `usu_id` de una o más listas de elementos. La función combina todas las listas proporcionadas en una sola y elimina los elementos duplicados, conservando solo el primer elemento encontrado para cada ID de usuario.

### Funcionamiento

1. **Combina Listas**: Se combinan todas las listas de elementos proporcionadas en una sola lista usando `array_merge()`.
2. **Eliminación de Duplicados**: Se recorre la lista combinada y se utiliza un array asociativo para eliminar duplicados basados en el campo `usu_id`. El valor del array asociativo es el elemento completo, mientras que la clave es el `usu_id`.
3. **Retorno de Resultados**: Se devuelven los valores del array asociativo como un array indexado usando `array_values()`, lo que elimina las claves asociativas y devuelve una lista con los elementos únicos.

### Parámetros

- `...$listas`: Una o más listas (arrays) de elementos. Cada elemento debe ser un array asociativo que contenga al menos el campo `usu_id`.

### Return

- `array`: Una lista de elementos únicos basada en el campo `usu_id`. Cada elemento es un array asociativo que representa un único usuario.

## get_regla_envio_por_asunto

Obtiene una o más reglas de envío de la tabla `tm_regla_envio` que coinciden con el asunto proporcionado. Si no se proporciona un asunto, la función no devuelve resultados.

### Funcionamiento

1. **Preparación de Consulta**: Se prepara una consulta SQL que selecciona todos los registros de `tm_regla_envio` donde el campo `asunto` coincide con el valor proporcionado.
2. **Ejecución de Consulta**: Se ejecuta la consulta con el parámetro `:asunto` utilizando la función `ejecutarConsulta()`.

### Parámetros

- `string $asunto` (opcional): El asunto de las reglas de envío a buscar. Si no se proporciona, la consulta no devolverá resultados.

### Return

- `array`: Un array asociativo con los registros que coinciden con el asunto proporcionado. Cada elemento es un array asociativo representando una fila de la tabla `tm_regla_envio`.

## get_reglas

Obtiene todas las reglas de envío de la tabla `tm_regla_envio`.

### Funcionamiento

1. **Preparación de Consulta**: Se prepara una consulta SQL que selecciona todos los registros de `tm_regla_envio`.
2. **Ejecución de Consulta**: Se ejecuta la consulta utilizando la función `ejecutarConsulta()`.

### Return

- `array`: Un array asociativo con todos los registros de la tabla `tm_regla_envio`. Cada elemento es un array asociativo representando una fila de la tabla.

## update_regla_envio

Actualiza una regla de envío en la tabla `tm_regla_envio` con los valores proporcionados para `unidad`, `seccion`, `usuario`, y `tipo_usuario`, basándose en el campo `asunto`.

### Funcionamiento

1. **Preparación de Consulta**: Se prepara una consulta SQL que actualiza los campos `unidad`, `seccion`, `usuario`, y `tipo_usuario` en la tabla `tm_regla_envio` para el registro cuyo campo `asunto` coincida con el valor proporcionado.
2. **Ejecución de Consulta**: Se ejecuta la consulta con los parámetros proporcionados utilizando la función `ejecutarAccion()`.
3. **Retorno de Resultado**: Se devuelve un array que indica el estado de la operación:
    - `"status" => "success"` si la actualización fue exitosa.
    - `"status" => "warning"` si no se hizo ningún cambio.

### Parámetros
- `array $args`: Un array asociativo con los siguientes elementos:
    - `"unidad"`: El nuevo valor para el campo `unidad`.
    - `"seccion"`: El nuevo valor para el campo `seccion`.
    - `"usuario"`: El nuevo valor para el campo `usuario`.
    - `"tipo_usuario"`: El nuevo valor para el campo `tipo_usuario`.
    - `"asunto"`: El valor del campo `asunto` que identifica el registro a actualizar.

### Return
- `array`: Un array asociativo con los siguientes campos:
    - `"status"`: El estado de la operación, puede ser `"success"` o `"warning"`.
    - `"message"`: Un mensaje descriptivo de la operación, indicando si la actualización fue exitosa o si no se hizo ningún cambio.
