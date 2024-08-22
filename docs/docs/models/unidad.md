## get_unidad

Esta función obtiene todos los registros de la tabla `tm_unidad`.

### Parámetros

- No tiene parámetros.

### Retorno

- Retorna un arreglo asociativo que contiene todos los registros de la tabla `tm_unidad`. Este arreglo es obtenido mediante la ejecución de la función `ejecutarConsulta`.

### Comportamiento

La función construye una consulta SQL que selecciona todos los campos de la tabla `tm_unidad`. Luego, invoca a la función `ejecutarConsulta` para ejecutar dicha consulta y obtener los resultados. La función `ejecutarConsulta` devuelve todos los registros como un arreglo asociativo (`fetchAll`), que es lo que retorna `get_unidad`.

```php
<?php
public function get_unidad(){
   $sql = "SELECT * FROM tm_unidad";
   return $this->ejecutarConsulta($sql);
}
```

