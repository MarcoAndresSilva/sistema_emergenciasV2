## get_evento
Esta función obtiene una lista de eventos de la base de datos, junto con detalles relacionados, como el nombre del usuario, categoría, estado, y ubicación.

### Parámetros

- No tiene parámetros.

### Retorno

- Retorna un arreglo asociativo que contiene los registros de los eventos, con campos como nombre, dirección, descripción, categoría, estado, fechas, y coordenadas. En caso de que no se encuentren registros o se produzca un error, se retorna un arreglo vacío.

### Comportamiento

**La función:**

1. Establece una conexión a la base de datos heredando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Construye una consulta SQL compleja que une varias tablas relacionadas (`tm_evento`, `tm_categoria`, `tm_ev_niv`, `tm_estado`, `tm_usuario`) para obtener toda la información relevante de los eventos.
4. Ejecuta la consulta y obtiene todos los registros resultantes.
5. Si hay resultados, los retorna como un arreglo asociativo. Si no hay resultados o se produce un error durante la ejecución, se retorna un arreglo vacío.
6. En caso de no encontrar eventos o si ocurre un error, se envía un mensaje de depuración a la consola del navegador.


```php
<?php
  public function get_evento() {
      try {
          $conectar = parent::conexion();
          parent::set_names();
          $sql = 'SELECT
                      us.usu_name as "ev_nom", us.usu_ape as "ev_ape",
                      tme.ev_direc as "ev_direc",
                      tmc.cat_nom as "cat_nom",
                      tme.ev_desc as "ev_desc",
                      tme.ev_inicio as "ev_inicio",
                      tmc.cat_id as "cat_id",
                      tme.ev_direc as "ev_direc",
                      tme.ev_id as "ev_id",
                      ten.ev_niv_id as "ev_niv_id",
                      tme.ev_est as "ev_est",
                      tme.ev_final as "ev_final",
                      tme.ev_latitud as "ev_latitud",
                      tme.ev_longitud as "ev_longitud"
                  FROM
                      tm_evento tme
                  INNER JOIN tm_categoria tmc ON
                      tmc.cat_id = tme.cat_id
                  INNER JOIN tm_ev_niv ten ON
                      tme.ev_niv = ten.ev_niv_id
                  INNER JOIN tm_estado te ON
                      te.est_id = tme.ev_est
                  INNER JOIN tm_usuario us
                  on us.usu_id=tme.usu_id
                  ORDER BY
                      ev_id
                  DESC;';
          $sql = $conectar->prepare($sql);
          $sql->execute();
          $resultado = $sql->fetchAll();
           
          if (is_array($resultado) && count($resultado) > 0) {
              return $resultado;
          } else {
              ?> <script>console.log("No se encontraron Eventos")</script><?php
              return [];
          } 
      } catch (Exception $e) {
          ?> 
          <script>console.log("Error catch     get_evento")</script>
          <?php
           return [];
      } 
  } 
```

## get_evento_nivel

Esta función obtiene una lista de eventos filtrados por un nivel específico.

### Parámetros

- **$ev_niv**: Nivel de evento por el cual se filtrarán los registros. Tipo: `int`.

### Retorno

- Retorna un arreglo asociativo que contiene los registros de los eventos que coinciden con el nivel especificado.
Si no se encuentran registros, retorna `0`. En caso de una excepción, lanza el error.

### Comportamiento

**La función:**

1. Establece una conexión a la base de datos heredando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Construye una consulta SQL que une las tablas `tm_evento`, `tm_categoria`, `tm_ev_niv`, y `tm_estado` para obtener toda la información relevante de los eventos, filtrando por el nivel de evento (`$ev_niv`).
4. Ejecuta la consulta y obtiene todos los registros resultantes.
5. Si se encuentran resultados, los retorna como un arreglo asociativo. Si no se encuentran resultados, retorna `0`.
6. En caso de que no se encuentren eventos o si ocurre un error, se envía un mensaje de depuración a la consola del navegador. Si se produce una excepción, esta se lanza para su manejo fuera de la función.

```php
<?php
public function get_evento_nivel($ev_niv) {
   try {
       $conectar = parent::conexion();
       parent::set_names();
       $sql = "SELECT * FROM tm_evento tme inner join tm_categoria tmc on tmc.cat_id=tme.cat_id inner join tm_ev_niv ten on tme.ev_niv=ten.ev_niv_id inner join tm_estado te on te.est_id=tme.ev_est where tme.ev_niv= ". $ev_niv ."  ";
       $sql = $conectar->prepare($sql);
       $sql->execute();
       $resultado = $sql->fetchAll();

       if (is_array($resultado) && count($resultado) > 0) {
           return $resultado;
       } else {
           ?> <script>console.log("No se encontraron Eventos")</><?php
           return 0;
       }
    } catch (Exception $e) {
      ?> 
      <script>console.log("Error catch     get_evento_nivel")</script>
      <?php
      throw $e;
   }

}

```
## get_eventos_por_dia
Esta función obtiene la cantidad de eventos agrupados por día de inicio.

### Parámetros
- No tiene parámetros.

### Retorno
- Retorna un arreglo asociativo donde cada elemento contiene el día del mes (`dia`) y la cantidad de eventos (`cantidad`) que comienzan en ese día. En caso de una excepción, lanza el error.

### Comportamiento
La función:
1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Construye una consulta SQL que selecciona el día del mes (`DAY(ev_inicio)`) en el que comienzan los eventos y cuenta la cantidad de eventos que inician en cada día. Los resultados son agrupados por el día de inicio.
4. Ejecuta la consulta y obtiene el conjunto de resultados, que es retornado como un arreglo asociativo.
5. Si se produce una excepción durante la ejecución, esta se lanza para su manejo fuera de la función.

```php
<?php
public function get_eventos_por_dia() {
  try {
      $conectar = parent::conexion();
      parent::set_names();
      $sql = "SELECT DAY(ev_inicio) as dia, COUNT(*) as cantidad FROM tm_evento GROUP BY DAY(ev_inicio)";
      $sql = $conectar->prepare($sql);
      $sql->execute();
      $resultado = $sql->fetchAll();
      return $resultado;
  } catch (Exception $e) {
      throw $e;
  }
}
```
## get_cantidad_eventos_por_nivel

Esta función obtiene la cantidad de eventos por nivel de emergencia dentro de un rango de fechas especificado.

### Parámetros

- **$ev_niv_array**: Arreglo de niveles de emergencia por los cuales se filtrarán los eventos. Tipo: `array` de `int`.
- **$fecha_actual**: Fecha final del rango en formato `YYYY-MM-DD`. Tipo: `string`.
- **$fecha_mes_anterior**: Fecha inicial del rango en formato `YYYY-MM-DD`. Tipo: `string`.

### Retorno

- Retorna un arreglo asociativo que contiene:
  - **total**: La cantidad total de eventos en el rango de fechas y niveles especificados.
  - **porcentaje**: Un valor inicializado a `0`, preparado para cálculos posteriores.
  - **cantidad{n}**: Para cada nivel `n` presente en `$ev_niv_array`, se incluye una clave con la cantidad de eventos de ese nivel.

### Comportamiento

La función:
1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Construye una condición SQL basada en los niveles de emergencia (`$ev_niv_array`) proporcionados.
3. Construye y prepara una consulta SQL que cuenta la cantidad de eventos agrupados por nivel (`ev_niv`), que ocurrieron dentro del rango de fechas especificado por `$fecha_mes_anterior` y `$fecha_actual`.
4. Ejecuta la consulta, enlazando las fechas de inicio y fin como parámetros.
5. Procesa los resultados para calcular la cantidad total de eventos y asignar cada cantidad al nivel correspondiente en el arreglo de datos.
6. Retorna el arreglo de datos, que incluye la cantidad total de eventos y las cantidades específicas por nivel.

Si se produce una excepción durante la ejecución, esta se lanza para su manejo fuera de la función.

```php
<?php
public function get_cantidad_eventos_por_nivel($ev_niv_array, $fecha_actual, $fecha_mes_anterior) {
  try {
      $conectar = parent::conexion();
      // Construir la condición para los niveles de emergencia
      $ev_niv_condition = implode(',', $ev_niv_array);
      // Consulta SQL para obtener la cantidad de eventos por nivel
      $sql = "SELECT ev_niv, COUNT(*) AS cantidad FROM tm_evento WHERE ev_niv IN ($ev_niv_condition) AND ev_inicio BETWEEN :fecha_inicio AND :fecha_fin GROUP BY ev_niv";
      $sql = $conectar->prepare($sql);
      $sql->bindParam(':fecha_inicio', $fecha_mes_anterior, PDO::PARAM_STR);
      $sql->bindParam(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
      $sql->execute();
      $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
      // Inicializar el array de datos
      $datos = array(
          'total' => 0,
          'porcentaje' => 0
      );
      // Procesar los resultados
      foreach ($resultados as $resultado) {
          $nivel = $resultado['ev_niv'];
          $cantidad = $resultado['cantidad'];
          // Sumar la cantidad total
          $datos['total'] += $cantidad;
          // Asignar la cantidad al nivel correspondiente en el array de datos
          $datos["cantidad$nivel"] = $cantidad;
      }
      // Devolver los datos
      return $datos;
  } catch (Exception $e) {
      throw $e;
  }
}
```

## get_eventos_por_rango

Esta función obtiene la cantidad de eventos por día dentro de un rango de fechas especificado, junto con el estado máximo de esos eventos.

### Parámetros

- **$fecha_actual**: Fecha final del rango en formato `YYYY-MM-DD`. Tipo: `string`.
- **$fecha_desde_mes_anterior**: Fecha inicial del rango en formato `YYYY-MM-DD`. Tipo: `string`.

### Retorno

- Retorna un arreglo asociativo donde cada elemento contiene:
  - **fecha**: La fecha de inicio de los eventos en formato `YYYY-MM-DD`.
  - **cantidad**: La cantidad de eventos que inician en esa fecha.
  - **ev_est**: El valor máximo del estado (`ev_est`) de los eventos que ocurren en esa fecha.

### Comportamiento
**La función:**

1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Construye y prepara una consulta SQL que selecciona la fecha de inicio (`DATE(ev_inicio)`), cuenta la cantidad de eventos que inician en cada fecha, y obtiene el valor máximo del estado (`MAX(ev_est)`) de esos eventos.
4. Filtra los eventos por el rango de fechas especificado, usando `$fecha_desde_mes_anterior` como fecha de inicio y `$fecha_actual` como fecha de fin.
5. Ejecuta la consulta y obtiene todos los registros resultantes, que son retornados como un arreglo asociativo.
6. Si se produce una excepción durante la ejecución, esta se lanza para su manejo fuera de la función.

#### codigo

```php
<?php
public function get_eventos_por_rango($fecha_actual, $fecha_desde_mes_anterior) {
    try {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT DATE(ev_inicio) AS fecha, COUNT(*) AS cantidad, MAX(ev_est) AS ev_est
            FROM tm_evento tme 
            INNER JOIN tm_categoria tmc ON tmc.cat_id = tme.cat_id 
            INNER JOIN tm_ev_niv ten ON tme.ev_niv = ten.ev_niv_id 
            INNER JOIN tm_estado te ON te.est_id = tme.ev_est 
            WHERE ev_inicio BETWEEN :fecha_inicio AND :fecha_fin 
            GROUP BY DATE(ev_inicio) 
            ORDER BY DATE(ev_inicio) ";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(':fecha_inicio', $fecha_desde_mes_anterior, PDO::PARAM_STR);
        $sql->bindValue(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
        $sql->execute();
        $resultado = $sql->fetchAll();
        return $resultado;
    } catch (Exception $e) {
        throw $e;
    }
}
```

## get_eventos_por_rango_sin_cantidad

Esta función obtiene los eventos dentro de un rango de fechas especificado, 
mostrando la fecha de inicio y el estado de cada evento, sin contar la cantidad total de eventos.

### Parámetros

- **$fecha_actual**: Fecha final del rango en formato `YYYY-MM-DD`. Tipo: `string`.
- **$fecha_desde_mes_anterior**: Fecha inicial del rango en formato `YYYY-MM-DD`. Tipo: `string`.

### Retorno

- Retorna un arreglo asociativo donde cada elemento contiene:
  - **fecha**: La fecha de inicio de los eventos en formato `YYYY-MM-DD`.
  - **ev_est**: El estado del evento (`ev_est`) en esa fecha.

### Comportamiento

**La función:**

1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Construye y prepara una consulta SQL que selecciona la fecha de inicio (`DATE(ev_inicio)`) y el estado del evento (`ev_est`), ordenados por la fecha de inicio.
4. Filtra los eventos por el rango de fechas especificado, usando `$fecha_desde_mes_anterior` como fecha de inicio y `$fecha_actual` como fecha de fin.
5. Ejecuta la consulta y obtiene todos los registros resultantes, que son retornados como un arreglo asociativo.
6. Si se produce una excepción durante la ejecución, esta se lanza para su manejo fuera de la función.

#### codigo
```php
<?php
public function get_eventos_por_rango_sin_cantidad($fecha_actual, $fecha_desde_mes_anterior) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT DATE(ev_inicio) as fecha, ev_est FROM tm_evento tme 
            inner join tm_categoria tmc on tmc.cat_id=tme.cat_id 
            inner join tm_ev_niv ten on tme.ev_niv=ten.ev_niv_id 
            inner join tm_estado te on te.est_id=tme.ev_est 
            WHERE ev_inicio BETWEEN :fecha_inicio AND :fecha_fin ORDER BY ev_inicio";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(':fecha_inicio', $fecha_desde_mes_anterior, PDO::PARAM_STR);
            $sql->bindValue(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
            $sql->execute();
            $resultado = $sql->fetchAll();
            return $resultado;
        } catch (Exception $e) {
            throw $e;
        }
    }
```

## datos_eventos_por_rango

Esta función obtiene todos los registros de eventos dentro de un rango de fechas especificado.

### Parámetros
- **$fecha_actual**: Fecha final del rango en formato `YYYY-MM-DD`. Tipo: `string`.
- **$fecha_desde_mes_anterior**: Fecha inicial del rango en formato `YYYY-MM-DD`. Tipo: `string`.

### Retorno
- Retorna un arreglo asociativo que contiene todos los campos de los registros de eventos que ocurren dentro del rango de fechas especificado. El arreglo contiene todos los datos de los eventos que cumplen con el criterio.

### Comportamiento

La función:

1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Construye y prepara una consulta SQL que selecciona todos los campos (`*`) de la tabla `tm_evento`, filtrando los eventos que comienzan dentro del rango de fechas especificado por `$fecha_desde_mes_anterior` y `$fecha_actual`.
4. Ejecuta la consulta y obtiene todos los registros resultantes, que son retornados como un arreglo asociativo.
5. Si se produce una excepción durante la ejecución, esta se lanza para su manejo fuera de la función.

#### codigo
```php
<?php
public function datos_eventos_por_rango($fecha_actual, $fecha_desde_mes_anterior) {
    try {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT *
        FROM tm_evento 
        WHERE ev_inicio BETWEEN :fecha_inicio AND :fecha_fin";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(':fecha_inicio', $fecha_desde_mes_anterior, PDO::PARAM_STR);
        $sql->bindValue(':fecha_fin', $fecha_actual, PDO::PARAM_STR);
        $sql->execute();
        $resultado = $sql->fetchAll();
        return $resultado;
    } catch (Exception $e) {
        throw $e;
    }
}
```

## add_evento

Esta función agrega un nuevo evento a la base de datos, validando que todos los campos obligatorios estén completos antes de proceder.

### Parámetros

- **$usu_id**: ID del usuario que crea el evento. Tipo: `int`.
- **$ev_desc**: Descripción del evento. Tipo: `string`.
- **$ev_est**: Estado del evento. Tipo: `int`.
- **$ev_inicio**: Fecha y hora de inicio del evento en formato `YYYY-MM-DD HH:MM:SS`. Tipo: `string`.
- **$ev_direc**: Dirección del evento. Tipo: `string`.
- **$ev_latitud**: Latitud geográfica del evento. Tipo: `float`.
- **$ev_longitud**: Longitud geográfica del evento. Tipo: `float`.
- **$cat_id**: ID de la categoría del evento. Tipo: `int`.
- **$ev_niv**: Nivel de emergencia del evento. Tipo: `int`.
- **$ev_img**: Ruta o nombre de la imagen del evento. Tipo: `string`.

### Retorno

- Retorna un arreglo asociativo con los siguientes elementos:
  - **status**: Indica el resultado de la operación (`success`, `warning`, `error`).
  - **message**: Mensaje descriptivo del resultado de la operación.

### Comportamiento

La función:

1. Verifica que los campos obligatorios (`$usu_id`, `$ev_desc`, `$ev_est`, `$ev_inicio`, `$ev_direc`, `$cat_id`) estén presentes y no estén vacíos.
   - Si falta algún campo obligatorio, retorna un mensaje de advertencia.
2. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
3. Configura los nombres de los campos (`parent::set_names()`).
4. Prepara una consulta SQL para insertar un nuevo registro en la tabla `tm_evento` con los valores proporcionados.
5. Vincula los parámetros de la consulta con las variables correspondientes.
6. Intenta ejecutar la consulta:
      - Si la ejecución es exitosa, retorna un mensaje de éxito.
      - Si ocurre un error en la ejecución, captura la excepción y retorna un mensaje de error con los detalles.
7. Si se produce una excepción general durante el proceso, esta es capturada y se retorna un mensaje de error.

### Validaciones
- Se asegura que todos los campos obligatorios están completos antes de realizar la inserción.

#### codigo

```php
<?php
public function add_evento($usu_id, $ev_desc, $ev_est, $ev_inicio, $ev_direc, $ev_latitud, $ev_longitud, $cat_id, $ev_niv, $ev_img) {
    if (empty($usu_id) || empty($ev_desc) || empty($ev_est) || empty($ev_inicio) || empty($ev_direc) || empty($cat_id)) {
        return [
            'status' => 'warning',
            'message' => 'Faltan datos obligatorios. Por favor, asegúrate de completar todos los campos necesarios.'
        ];
    }

    try {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO tm_evento (usu_id, ev_desc, ev_est, ev_inicio, ev_final, ev_direc, ev_latitud, ev_longitud, cat_id, ev_niv, ev_img) 
        VALUES (:usu_id, :ev_desc, :ev_est, :ev_inicio, NULL, :ev_direc, :ev_latitud, :ev_longitud, :cat_id, :ev_niv, :ev_img)";

        $consulta = $conectar->prepare($sql);

        $consulta->bindParam(':usu_id', $usu_id);
        $consulta->bindParam(':ev_desc', $ev_desc);
        $consulta->bindParam(':ev_est', $ev_est);
        $consulta->bindParam(':ev_inicio', $ev_inicio);
        $consulta->bindParam(':ev_direc', $ev_direc);
        $consulta->bindParam(':ev_latitud', $ev_latitud);
        $consulta->bindParam(':ev_longitud', $ev_longitud);
        $consulta->bindParam(':cat_id', $cat_id);
        $consulta->bindParam(':ev_niv', $ev_niv);
        $consulta->bindParam(':ev_img', $ev_img);

        try {
            $consulta->execute();
            return [
                'status' => 'success',
                'message' => 'Evento agregado exitosamente.'
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Error al ejecutar la consulta: ' . $e->getMessage()
            ];
        }

    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Error catch add_evento: ' . $e->getMessage()
        ];
    }
} 
```

## update_imagen_evento
Esta función actualiza la imagen de un evento en la base de datos.

### Parámetros
- **$ev_id**: ID del evento cuya imagen se desea actualizar. Tipo: `int`.
- **$ev_img**: Ruta o nombre de la nueva imagen del evento. Tipo: `string`.

### Retorno
- **bool**: Retorna `true` si la actualización fue exitosa, o `0` si no se logró actualizar la imagen.

### Comportamiento
La función:

1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Prepara una consulta SQL para actualizar el campo `ev_img` del registro en la tabla `tm_evento` correspondiente al `ev_id` proporcionado.
4. Vincula los parámetros de la consulta con las variables `$ev_id` y `$ev_img`.
5. Intenta ejecutar la consulta:
     - Si la ejecución es exitosa, retorna `true`.
     - Si la ejecución falla, retorna `0` e imprime un mensaje de error en la consola.
6. Si se produce una excepción durante la ejecución, esta es capturada y lanzada nuevamente para su manejo fuera de la función.

### Validaciones
- La función no realiza validaciones adicionales, asumiendo que los parámetros recibidos son válidos.

```php
<?php
	public function update_imagen_evento($ev_id, $ev_img) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_evento SET  ev_img = :ev_img WHERE ev_id = :ev_id ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':ev_id',$ev_id);
            $consulta->bindParam(':ev_img',$ev_img);
			
			if ($consulta->execute()) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar la imagen del evento")</script><?php
                return 0;
            }
    } catch (Exception $e) {
			?> 
      <script>console.log("Error catch     update_imagen_evento")</script>
      <?php
      throw $e;
    }
	}
```
## update_imagen_cierre
Esta función actualiza la imagen adjunta al cierre de un evento en la base de datos.

### Parámetros
- **$ev_id**: ID del evento cuyo cierre se desea actualizar con una nueva imagen. Tipo: `int`.
- **$adjunto**: Ruta o nombre del nuevo archivo adjunto para el cierre del evento. Tipo: `string`.

### Retorno
- **bool**: Retorna `true` si la actualización fue exitosa, o `0` si no se logró actualizar la imagen.

### Comportamiento

**La función:**

1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Prepara una consulta SQL para actualizar el campo `adjunto` en la tabla `tm_ev_cierre` para el registro que coincide con el `ev_id` proporcionado.
4. Vincula los parámetros de la consulta con las variables `$ev_id` y `$adjunto`.
5. Intenta ejecutar la consulta:
     - Si la ejecución es exitosa, retorna `true`.
     - Si la ejecución falla, retorna `0` y muestra un mensaje de error en la consola.
6. Si se produce una excepción durante la ejecución, esta es capturada y lanzada nuevamente para su manejo externo.

#### codigo

```php
<?php
public function update_imagen_cierre($ev_id, $ev_img) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_ev_cierre SET  adjunto = :adjunto WHERE ev_id = :ev_id ";
			$consulta = $conectar->prepare($sql);

      $consulta->bindParam(':ev_id',$ev_id);
      $consulta->bindParam(':adjunto',$adjunto);
			
			if ($consulta->execute()) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar la imagen del evento")</script><?php
                return 0;
            }
    } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_imagen_evento")</script>
            <?php
            throw $e;
    }
}
```

## update_nivelpeligro_evento
Esta función actualiza el nivel de peligro de un evento específico en la base de datos.

### Parámetros
- **$ev_id**: ID del evento cuyo nivel de peligro se desea actualizar. Tipo: `int`.
- **$ev_niv**: Nuevo nivel de peligro para el evento. Tipo: `int`.

### Retorno
- **bool**: Retorna `true` si la actualización fue exitosa, o `0` si no se logró actualizar el nivel de peligro.

### Comportamiento
**La función:**

1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Prepara una consulta SQL para actualizar el campo `ev_niv` en la tabla `tm_evento` para el registro que coincide con el `ev_id` proporcionado.
4. Vincula el parámetro `:ev_niv` en la consulta con la variable `$ev_niv`.
5. Ejecuta la consulta:
      - Si la consulta afecta al menos una fila, retorna `true`.
      - Si no se afecta ninguna fila, retorna `0` y muestra un mensaje de error en la consola.
6. Si se produce una excepción durante la ejecución, esta es capturada y lanzada nuevamente para su manejo externo.

### Validaciones
- La función no realiza validaciones adicionales, asumiendo que los parámetros recibidos son válidos.

#### codigo

```php
<?php
  public function update_nivelpeligro_evento($ev_id, $ev_niv) {
		try {
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE tm_evento SET  ev_niv=:ev_niv WHERE ev_id = " . $ev_id . " ";
			$consulta = $conectar->prepare($sql);

            $consulta->bindParam(':ev_niv',$ev_niv);

            $consulta->execute();
			
			if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se logro actualizar la asignacion del evento")</script><?php
                return 0;
            }
        } catch (Exception $e) {
			?> 
            <script>console.log("Error catch     update_asignacion_evento")</script>
            <?php
            throw $e;
        }
	}
```

## get_evento_id
Esta función recupera un evento específico de la base de datos utilizando su ID.

### Parámetros
- **$ev_id**: ID del evento que se desea obtener. Tipo: `int` o `string`.

### Retorno
- **array**: Retorna un array con los detalles del evento si se encuentra.
- **int**: Retorna `0` si no se encuentra ningún evento con el ID proporcionado.

### Comportamiento
La función:
1. Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
2. Configura los nombres de los campos (`parent::set_names()`).
3. Prepara una consulta SQL para seleccionar todos los campos del evento con el `ev_id` proporcionado.
4. Ejecuta la consulta y almacena el resultado.
5. Verifica si el resultado es un array y contiene al menos un registro:
   - Si el evento se encuentra, retorna el array con los datos del evento.
   - Si no se encuentra, retorna `0` y muestra un mensaje en la consola.
6. Si se produce una excepción durante la ejecución, esta es capturada y lanzada nuevamente para su manejo externo.

### Validaciones
- La función asume que el parámetro `$ev_id` es válido y no realiza validaciones adicionales sobre su formato o contenido.

## cerrar_evento
Esta función cierra un evento actualizando su estado y registrando los detalles del cierre en la base de datos.

### Parámetros
- **$ev_id**: ID del evento que se desea cerrar. Tipo: `int`.
- **$ev_final**: Fecha y hora de cierre del evento. Tipo: `string`.
- **$ev_est**: Nuevo estado del evento (por ejemplo, cerrado). Tipo: `int`.
- **$detalle_cierre**: Detalles adicionales sobre el cierre del evento. Tipo: `string`.
- **$motivo_cierre**: Motivo del cierre del evento. Tipo: `string`.
- **$usu_id**: ID del usuario que realiza el cierre del evento. Tipo: `int`.

### Retorno
- **bool**: Retorna `true` si la transacción se realiza con éxito.
- **Exception**: Lanza una excepción si ocurre un error durante la transacción.

### Comportamiento
La función realiza las siguientes acciones:
1. **Conexión y configuración**:
     - Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
     - Configura los nombres de los campos (`parent::set_names()`).
  
2. **Transacción**:
     - Inicia una transacción para asegurar que todas las operaciones se realicen de manera atómica.

3. **Actualización del evento**:
     - Prepara y ejecuta una consulta SQL para actualizar la tabla `tm_evento` con la fecha de cierre (`$ev_final`) y el nuevo estado (`$ev_est`) del evento identificado por `$ev_id`.

4. **Inserción del registro de cierre**:
     - Prepara y ejecuta una consulta SQL para insertar un nuevo registro en la tabla `tm_ev_cierre` con el ID del usuario (`$usu_id`), el ID del evento (`$ev_id`), los detalles del cierre (`$detalle_cierre`) y el motivo del cierre (`$motivo_cierre`).

5. **Confirmación de la transacción**:
     - Si ambas operaciones (actualización e inserción) se ejecutan correctamente, se confirma la transacción.

6. **Manejo de errores**:
     - Si ocurre un error durante la transacción, esta se revierte (rollback) para evitar inconsistencias en la base de datos.
     - Se captura la excepción y se lanza nuevamente para su manejo externo.

### Validaciones

- La función asume que los parámetros son válidos y no realiza validaciones adicionales sobre su formato o contenido.

## get_evento_where

Esta función realiza una búsqueda flexible en la tabla `tm_evento` y sus tablas relacionadas utilizando un criterio de búsqueda proporcionado por el usuario. Los resultados se agrupan por evento y muestran las unidades asociadas en una cadena concatenada.

### Parámetros
- **$where**: Cadena de texto que se utiliza como criterio de búsqueda en varias columnas de la base de datos. Tipo: `string`.

### Retorno
- **array**: Retorna un array de resultados si se encuentran coincidencias.
- **int**: Retorna `0` si no se encuentran resultados.

### Comportamiento
La función realiza las siguientes acciones:

1. **Conexión y configuración**:
     - Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
     - Configura los nombres de los campos (`parent::set_names()`).

2. **Construcción de la consulta SQL**:
     - La consulta SQL selecciona varios campos de la tabla `tm_evento` y de sus tablas relacionadas (`tm_asignado`, `tm_unidad`, `tm_categoria`, `tm_ev_niv`, `tm_estado`).
     - Se utiliza una cláusula `WHERE` con múltiples condiciones para buscar coincidencias en diferentes columnas utilizando el valor del parámetro `$where`.
     - Los resultados se agrupan por evento utilizando la cláusula `GROUP BY` y las unidades asociadas a cada evento se concatenan en una cadena separada por guiones (`-`).

3. **Preparación y ejecución**:
     - La consulta se prepara utilizando la conexión establecida.
     - Se vincula el parámetro de búsqueda (`:where`) con el valor proporcionado por el usuario, que se rodea con caracteres `%` para permitir la búsqueda de coincidencias parciales.
     - Se ejecuta la consulta y se almacenan los resultados.

4. **Procesamiento de resultados**:
     - Si se encuentran resultados, la función retorna el array de resultados.
     - Si no se encuentran resultados, la función retorna `0`.

5. **Manejo de errores**:
     - Si ocurre un error durante la ejecución, se captura la excepción y se lanza nuevamente para su manejo externo.

## get_id_ultimo_evento
Esta función obtiene el identificador (`ev_id`) del último evento registrado en la tabla `tm_evento`, es decir, el evento más reciente según su orden de inserción en la base de datos.

### Parámetros
- **Ninguno**: La función no recibe parámetros.

### Retorno
- **int**: Retorna el valor del `ev_id` del último evento si se encuentra uno.
- **int**: Retorna `0` si no se logra obtener el último ID o si la tabla está vacía.

### Comportamiento
La función realiza las siguientes acciones:

1. **Conexión y configuración**:
     - Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
     - Configura los nombres de los campos (`parent::set_names()`).

2. **Construcción de la consulta SQL**:
     - La consulta SQL selecciona el campo `ev_id` del último evento registrado en la tabla `tm_evento`.
     - La consulta utiliza `ORDER BY ev_id DESC LIMIT 1` para ordenar los eventos de forma descendente y limitar el resultado a un solo registro, obteniendo así el ID más alto.

3. **Preparación y ejecución**:
     - La consulta se prepara utilizando la conexión establecida.
     - Se ejecuta la consulta y se obtiene el resultado usando `fetchColumn()`, que retorna el valor del primer (y único) campo en el resultado.

4. **Procesamiento de resultados**:
     - Si se encuentra un resultado, la función retorna el valor del `ev_id`.
     - Si no se encuentra ningún resultado (por ejemplo, si la tabla está vacía), la función retorna `0` y genera un mensaje de error en la consola.

5. **Manejo de errores**:
     - Si ocurre un error durante la ejecución, se captura la excepción y se lanza nuevamente para su manejo externo, además de mostrar un mensaje en la consola.

## get_evento_por_categoria
Esta función recupera todos los eventos asociados a una categoría específica en la tabla `tm_evento` de la base de datos.

### Parámetros
- **$cat_id** (int): El identificador de la categoría (`cat_id`) cuyos eventos se desean obtener.

### Retorno
- **array**: Retorna un arreglo asociativo con los datos de los eventos encontrados.
- **array**: Retorna un arreglo vacío si no se encuentran eventos asociados a la categoría especificada.

### Comportamiento
La función realiza las siguientes acciones:

1. **Conexión y configuración**:
     - Establece una conexión a la base de datos utilizando la conexión de la clase padre (`parent::conexion()`).
     - Configura los nombres de los campos (`parent::set_names()`).

2. **Construcción de la consulta SQL**:
     - La consulta SQL selecciona todos los campos de la tabla `tm_evento` donde el `cat_id` coincida con el valor proporcionado en el parámetro.

3. **Preparación y ejecución**:
     - La consulta se prepara utilizando la conexión establecida.
     - Se vincula el valor del parámetro `:cat_id` con el valor proporcionado.
     - Se ejecuta la consulta y se obtienen los resultados usando `fetchAll(PDO::FETCH_ASSOC)`, que retorna un arreglo asociativo con todos los eventos que coinciden con la categoría.

4. **Procesamiento de resultados**:
     - Si se encuentran eventos asociados a la categoría, la función retorna un arreglo con los datos de estos eventos.
     - Si no se encuentran eventos, la función retorna un arreglo vacío y registra un mensaje de error en el log de errores de PHP (`error_log`).

5. **Manejo de errores**:
     - Si ocurre un error durante la ejecución, se captura la excepción y se lanza nuevamente para su manejo externo. Además, se registra un mensaje de error en el log de errores de PHP (`error_log`).

## get_eventos_categoria_latitud_longitud
Esta función obtiene una lista de eventos con información detallada, incluyendo su ubicación geográfica y otros atributos relevantes, tales como la descripción, fecha de inicio y cierre, nivel de peligro, categoría, y la unidad responsable.

### Retorno
- **array**: Un arreglo asociativo con la información de los eventos que incluye:
    - **latitud** (float): Latitud del evento.
    - **longitud** (float): Longitud del evento.
    - **id** (int): Identificador único del evento.
    - **detalles** (string): Descripción del evento.
    - **img** (string): URL o ruta de la imagen asociada al evento.
    - **fecha_inicio** (datetime): Fecha de inicio del evento.
    - **fecha_cierre** (datetime|string): Fecha de cierre del evento o "En Proceso" si no ha sido cerrado.
    - **nivel** (string): Nivel de peligro del evento.
    - **categoria** (string): Categoría del evento.
    - **unidad** (string): Nombre de la unidad responsable del evento.

### Descripción de la Consulta SQL
La consulta SQL utilizada hace lo siguiente:

- **Selecciona**:
    - La latitud y longitud (`ev_latitud`, `ev_longitud`) del evento.
    - El identificador del evento (`ev_id`).
    - La descripción del evento (`ev_desc`).
    - La imagen asociada al evento (`ev_img`).
    - La fecha de inicio del evento (`ev_inicio`).
    - La fecha de cierre del evento (`ev_final`) o "En Proceso" si no se ha cerrado.
    - El nombre del nivel de peligro (`ev_niv_nom`), la categoría (`cat_nom`), y la unidad responsable (`unid_nom`).

- **Une** varias tablas relacionadas para obtener los datos completos:
    - `tm_evento` (`ev`) con `tm_categoria` (`cat`), `tm_usuario` (`usu`), `tm_ev_niv` (`nv`), y `tm_unidad` (`un`).

## datos_categorias_eventos

Esta función obtiene la cantidad de eventos por categoría a partir de una fecha de inicio dada.

### Parámetros

- **`$fecha_inicio`** (string): La fecha a partir de la cual se cuentan los eventos. Debe estar en formato de fecha SQL (`YYYY-MM-DD`).

### Retorno

- **array**: Un arreglo asociativo con los datos de las categorías y la cantidad de eventos asociados:
    - **cat_nom** (string): Nombre de la categoría.
    - **cantidad_eventos** (int): Cantidad de eventos asociados a esa categoría que han comenzado a partir de la fecha proporcionada.

### Descripción de la Consulta SQL

La consulta SQL realiza las siguientes acciones:
- **Selecciona**:
    - El nombre de la categoría (`tm_categoria.cat_nom`).
    - La cantidad de eventos (`COUNT(tm_evento.ev_id)`) para cada categoría.

- **Une** las tablas `tm_categoria` y `tm_evento`:
    - La unión se realiza mediante `LEFT JOIN` para incluir todas las categorías, incluso aquellas sin eventos asociados.
    - La condición de la unión asegura que solo se cuenten los eventos cuyo inicio sea igual o posterior a la fecha proporcionada (`tm_evento.ev_inicio >= :fecha_inicio`).

- **Agrupa** los resultados por el identificador de la categoría (`tm_categoria.cat_id`).

## listar_eventosdetalle_por_evento

Esta función obtiene los detalles de emergencias asociadas a un evento específico.

### Parámetros

- **`$ev_id`** (int): El identificador del evento para el cual se desean obtener los detalles de emergencias.

### Retorno

- **array**: Un arreglo asociativo con los detalles de emergencias asociadas al evento:
  - **emergencia_id** (int): Identificador de la emergencia.
  - **ev_desc** (string): Descripción de la emergencia.
  - **ev_inicio** (string): Fecha y hora de inicio de la emergencia.
  - **usu_nom** (string): Nombre del usuario asociado con la emergencia.
  - **usu_ape** (string): Apellido del usuario asociado con la emergencia.
  - **usu_tipo** (string): Tipo de usuario asociado con la emergencia.

### Descripción de la Consulta SQL

La consulta SQL realiza las siguientes acciones:
- **Selecciona**:
  - Identificador de la emergencia (`tm_emergencia_detalle.emergencia_id`).
  - Descripción de la emergencia (`tm_emergencia_detalle.ev_desc`).
  - Fecha y hora de inicio de la emergencia (`tm_emergencia_detalle.ev_inicio`).
  - Nombre del usuario (`tm_usuario.usu_nom`).
  - Apellido del usuario (`tm_usuario.usu_ape`).
  - Tipo de usuario (`tm_usuario.usu_tipo`).

- **Une** las tablas `tm_emergencia_detalle` y `tm_usuario`:
  - La unión se realiza mediante `INNER JOIN` basándose en el identificador del usuario (`usu_id`) para obtener la información del usuario asociado con cada detalle de emergencia.

- **Filtra** los resultados para aquellos que están asociados con el identificador del evento proporcionado (`tm_emergencia_detalle.ev_id = ?`).

## listar_evento_por_id

Esta función obtiene los detalles de un evento específico basado en su identificador.

### Parámetros

- **`$ev_id`** (int): El identificador del evento para el cual se desea obtener la información.

### Retorno

- **array|false**: 
    - **array**: Un arreglo asociativo con los detalles del evento:
        - **ev_id** (int): Identificador del evento.
        - **usu_id** (int): Identificador del usuario que reportó el evento.
        - **cat_id** (int): Identificador de la categoría del evento.
        - **ev_direc** (string): Dirección del evento.
        - **ev_desc** (string): Descripción del evento.
        - **ev_est** (int): Estado del evento.
        - **ev_inicio** (string): Fecha y hora de inicio del evento.
        - **usu_nom** (string): Nombre del usuario que reportó el evento.
        - **usu_ape** (string): Apellido del usuario que reportó el evento.
        - **cat_nom** (string): Nombre de la categoría del evento.
    - **false**: Devuelto si ocurre un error durante la consulta.

### Descripción de la Consulta SQL

La consulta SQL realiza las siguientes acciones:
- **Selecciona**:
    - Información del evento (`tm_evento.ev_id`, `tm_evento.usu_id`, `tm_evento.cat_id`, `tm_evento.ev_direc`, `tm_evento.ev_desc`, `tm_evento.ev_est`, `tm_evento.ev_inicio`).
    - Información del usuario que reportó el evento (`tm_usuario.usu_nom`, `tm_usuario.usu_ape`).
    - Nombre de la categoría del evento (`tm_categoria.cat_nom`).

- **Une** las tablas `tm_evento`, `tm_categoria`, y `tm_usuario`:
    - La unión con `tm_categoria` se realiza a través del identificador de categoría (`tm_evento.cat_id = tm_categoria.cat_id`).
    - La unión con `tm_usuario` se realiza a través del identificador del usuario (`tm_evento.usu_id = tm_usuario.usu_id`).

- **Filtra** los resultados para obtener solo el evento con el identificador proporcionado (`tm_evento.ev_id = ?`).

## insert_emergencia_detalle

Esta función inserta un nuevo detalle de emergencia en la base de datos.

### Parámetros

- **`$ev_id`** (int): Identificador del evento al que se está asociando el detalle de emergencia.
- **`$usu_id`** (int): Identificador del usuario que reporta el detalle de emergencia.
- **`$ev_desc`** (string): Descripción del detalle de emergencia.

### Retorno

- **bool**: 
    - **true**: Si el detalle de emergencia se insertó correctamente en la base de datos.
    - **false**: Si no se insertó ningún detalle (puede ser debido a un problema en la ejecución).

### Descripción de la Consulta SQL

La consulta SQL realiza las siguientes acciones:
- **Inserta** un nuevo registro en la tabla `tm_emergencia_detalle` con los siguientes campos:
    - `ev_id`: Identificador del evento.
    - `usu_id`: Identificador del usuario que reporta el detalle.
    - `ev_desc`: Descripción del detalle de emergencia.
    - `ev_inicio`: Fecha y hora actual (`now()`).
    - `ev_est`: Estado del detalle (establecido en 1).

## update_evento

Actualiza el estado de un evento en la base de datos a un valor fijo (2).

### parámetros
- **int $ev_id**: El identificador del evento que se desea actualizar.

### retorno
- **bool**: Devuelve `true` si la actualización fue exitosa (se actualizó al menos una fila), o `false` si no se encontró ningún evento con el identificador proporcionado.

### comportamiento
- La función establece la conexión con la base de datos y prepara una consulta SQL para actualizar el estado del evento especificado.
- El estado del evento se establece en `2`, que puede indicar un estado específico como "cerrado" o "completado".
- Ejecuta la consulta SQL y verifica si se actualizó alguna fila en la base de datos.
- Si la actualización fue exitosa, devuelve `true`; de lo contrario, devuelve `false`.
- Si ocurre un error durante la operación, se lanza una excepción y se registra el error.
