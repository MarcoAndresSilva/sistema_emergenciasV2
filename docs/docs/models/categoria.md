## add_categoria

Inserta una nueva categoría en la base de datos con un nombre y un nivel especificados.

### Parámetros
- **$cat_nom** (string): Nombre de la categoría.
- **$nivel** (int): Nivel de la categoría.

### Retorno
- `bool`: `true` si la inserción es exitosa; `false` en caso contrario.

### Comportamiento
1. Prepara una consulta SQL para insertar una nueva categoría en la tabla `tm_categoria`.
2. Define los parámetros de la consulta con el nombre y nivel de la categoría.
3. Ejecuta la consulta utilizando el método `ejecutarAccion`.
4. Retorna `true` si la inserción es exitosa; `false` en caso contrario.

### Código
```php
<?php
public function add_categoria($cat_nom, $nivel) {
    $sql = "INSERT INTO tm_categoria (cat_nom, nivel) VALUES (:cat_nom, :nivel)";
    $params = [':cat_nom' => $cat_nom, ':nivel' => $nivel];
    return $this->ejecutarAccion($sql, $params);
}
```
## get_categoria

### Descripción
Obtiene todas las categorías de la base de datos.

### Parámetros
Ninguno.

### Retorno
- `array`: Un array con todas las categorías encontradas en la tabla `tm_categoria`.

### Comportamiento
1. Prepara una consulta SQL para seleccionar todas las categorías de la tabla `tm_categoria`.
2. Ejecuta la consulta utilizando el método `ejecutarConsulta`.
3. Retorna un array con todas las categorías encontradas.

### Código 
```php
<?php
public function get_categoria() {
    $sql = "SELECT * FROM tm_categoria";
    return $this->ejecutarConsulta($sql);
}
```
## get_datos_categoria

### Descripción
Obtiene los datos de una categoría específica basada en su ID.

### Parámetros
- **$cat_id** (int): El ID de la categoría a obtener.

### Retorno
- `array`: Un array con los datos de la categoría especificada.

### Comportamiento
1. Prepara una consulta SQL para seleccionar todas las columnas de la tabla `tm_categoria` donde `cat_id` coincida con el valor proporcionado.
2. Define el parámetro de la consulta con el ID de la categoría.
3. Ejecuta la consulta utilizando el método `ejecutarConsulta`.
4. Retorna el resultado de la consulta como un array.

### Código 
```php
<?php
public function get_datos_categoria($cat_id) {
    $sql = "SELECT * FROM tm_categoria WHERE cat_id = :cat_id";
    $params = [':cat_id' => $cat_id];
    return $this->ejecutarConsulta($sql, $params);
}
```

## update_categoria

Actualiza los datos de una categoría específica basada en su ID.

### Parámetros
- **$cat_id** (int): El ID de la categoría a actualizar.
- **$cat_nom** (string): El nuevo nombre de la categoría.
- **$nivel** (int): El nuevo nivel de la categoría.

### Retorno
- `bool`: `true` si la actualización es exitosa; `false` en caso contrario.

### Comportamiento
1. Prepara una consulta SQL para actualizar el nombre y el nivel de una categoría en la tabla `tm_categoria` donde `cat_id` coincida con el valor proporcionado.
2. Define los parámetros de la consulta con el ID, el nuevo nombre y el nuevo nivel de la categoría.
3. Ejecuta la consulta utilizando el método `ejecutarAccion`.
4. Retorna `true` si la actualización es exitosa; `false` en caso contrario.

### Código 
```php
<?php
public function update_categoria($cat_id, $cat_nom, $nivel) {
    $sql = "UPDATE tm_categoria SET cat_nom = :cat_nom, nivel = :nivel WHERE cat_id = :cat_id";
    $params = [':cat_id' => $cat_id, ':cat_nom' => $cat_nom, ':nivel' => $nivel];
    return $this->ejecutarAccion($sql, $params);
}
```

## delete_categoria

Elimina una categoría específica basada en su ID.

### Parámetros
- **$cat_id** (int): El ID de la categoría a eliminar.

### Retorno
- `bool`: `true` si la eliminación es exitosa; `false` en caso contrario.

### Comportamiento
1. Prepara una consulta SQL para eliminar una categoría en la tabla `tm_categoria` donde `cat_id` coincida con el valor proporcionado.
2. Define el parámetro de la consulta con el ID de la categoría.
3. Ejecuta la consulta utilizando el método `ejecutarAccion`.
4. Retorna `true` si la eliminación es exitosa; `false` en caso contrario.

### Código
```php
<?php
public function delete_categoria($cat_id) {
    $sql = "DELETE FROM tm_categoria WHERE cat_id = :cat_id";
    $params = [':cat_id' => $cat_id];
    return $this->ejecutarAccion($sql, $params);
}
```

## get_cat_nom_by_ev_id

Obtiene el nombre de la categoría asociada a un evento específico basado en su ID.

### Parámetros

- **$ev_id** (int): El ID del evento.

### Retorno

- `string`: Un JSON que contiene el nombre de la categoría asociada al evento. Si no se encuentra la categoría, retorna un JSON con un mensaje de error.

### Comportamiento

1. Prepara una consulta SQL para seleccionar el nombre de la categoría (`cat_nom`) de la tabla `tm_categoria` que está asociada a un evento en la tabla `tm_evento` donde `ev_id` coincida con el valor proporcionado.
2. Define el parámetro de la consulta con el ID del evento.
3. Ejecuta la consulta utilizando el método `ejecutarConsulta` con el tercer parámetro (`false`) para obtener una sola fila.
4. Si el resultado es exitoso, retorna el resultado como un JSON.
5. Si no se encuentra la categoría, retorna un JSON con un mensaje de error.

### Código Original

```php
<?php
public function get_cat_nom_by_ev_id($ev_id) {
    $sql = "SELECT c.cat_nom FROM tm_categoria c INNER JOIN tm_evento e ON c.cat_id = e.cat_id WHERE e.ev_id = :ev_id";
    $params = [':ev_id' => $ev_id];
    $resultado = $this->ejecutarConsulta($sql, $params, false);

    if ($resultado) {
        return json_encode($resultado);
    } else {
        return json_encode(['error' => 'Categoría no encontrada para el evento con ID: ' . $ev_id]);
    }
}
```

## get_categoria_nivel

Obtiene todas las categorías junto con sus niveles asociados.

### Parámetros

- No requiere parámetros.

### Retorno

- `array`: Un array de resultados que contiene todas las categorías junto con sus niveles asociados.

### Comportamiento

1. Prepara una consulta SQL para seleccionar todas las categorías (`tm_categoria`) junto con sus niveles asociados (`tm_ev_niv`).
2. Ejecuta la consulta utilizando el método `ejecutarConsulta`.
3. Retorna los resultados de la consulta en forma de un array.

### Código Original

```php
<?php
public function get_categoria_nivel() {
    $sql = "SELECT * FROM tm_categoria as cat INNER JOIN tm_ev_niv niv ON(cat.nivel = niv.ev_niv_id)";
    return $this->ejecutarConsulta($sql);
}
```

## get_categoria_relacion_motivo

Obtiene las categorías relacionadas con un motivo específico basado en su ID.

### Parámetros
- **$mov_id** (int): El ID del motivo.

### Retorno
- `array`: Un array de resultados que contiene las categorías relacionadas con el motivo especificado. Si no se encuentran categorías relacionadas, retorna todas las categorías.

### Comportamiento
1. Prepara una consulta SQL para seleccionar las categorías (`tm_categoria`) relacionadas con un motivo específico (`tm_motivo_cate`), donde `mov_id` coincida con el valor proporcionado.
2. Define el parámetro de la consulta con el ID del motivo.
3. Ejecuta la consulta utilizando el método `ejecutarConsulta`.
4. Si el resultado es un array y contiene al menos un resultado, lo retorna.
5. Si no se encuentran categorías relacionadas, ejecuta y retorna el resultado de la función `get_categoria` para obtener todas las categorías.

### Código

```php
<?php
public function get_categoria_relacion_motivo($mov_id) {
    $sql = 'SELECT cat.cat_nom as "cat_nom", mc.activo as "activo", mc.mov_id as "mov_id"
            FROM tm_categoria as cat
            JOIN tm_motivo_cate as mc ON mc.cat_id = cat.cat_id
            WHERE mc.mov_id = :mov_id';
    $params = [':mov_id' => $mov_id];
    $resultado = $this->ejecutarConsulta($sql, $params);

    if (is_array($resultado) && count($resultado) > 0) {
        return $resultado;
    } else {
        return $this->get_categoria();
    }
}
```
