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


```

