<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Categoria.php';

class TestCategoria extends TestCase
{
    private $categoria;

    protected function setUp(): void
    {
        // Crear un mock de la clase Categoria
        $this->categoria = $this->getMockBuilder(Categoria::class)
                                ->onlyMethods(['ejecutarAccion', 'ejecutarConsulta'])
                                ->getMock();
    }

    // Test para el método add_categoria - Caso exitoso
    public function testAddCategoriaExito()
    {
        $cat_nom = 'Categoria 1';
        $nivel = 2;

        // Mockear ejecutarAccion para que retorne true
        $this->categoria->method('ejecutarAccion')->willReturn(true);

        $resultado = $this->categoria->add_categoria($cat_nom, $nivel);
        $this->assertTrue($resultado);
    }

    // Test para el método add_categoria - Caso de error
    public function testAddCategoriaError()
    {
        $cat_nom = 'Categoria Invalida';
        $nivel = 999;  // Valor no válido de nivel

        // Mockear ejecutarAccion para que retorne false
        $this->categoria->method('ejecutarAccion')->willReturn(false);

        $resultado = $this->categoria->add_categoria($cat_nom, $nivel);
        $this->assertFalse($resultado);
    }

    // Test para el método get_categoria - Caso exitoso
    public function testGetCategoriaExito()
    {
        $categorias = [
            ['cat_id' => 1, 'cat_nom' => 'Categoria 1', 'nivel' => 2],
            ['cat_id' => 2, 'cat_nom' => 'Categoria 2', 'nivel' => 3],
        ];

        // Mockear ejecutarConsulta para retornar un conjunto de categorías
        $this->categoria->method('ejecutarConsulta')->willReturn($categorias);

        $resultado = $this->categoria->get_categoria();
        $this->assertEquals($categorias, $resultado);
    }

    // Test para el método get_categoria - Caso de error (categorías vacías)
    public function testGetCategoriaError()
    {
        // Mockear ejecutarConsulta para retornar un array vacío (sin resultados)
        $this->categoria->method('ejecutarConsulta')->willReturn([]);

        $resultado = $this->categoria->get_categoria();
        $this->assertEmpty($resultado);
    }

    // Test para el método get_datos_categoria - Caso exitoso
    public function testGetDatosCategoriaExito()
    {
        $cat_id = 1;
        $categoria = [
            'cat_id' => 1,
            'cat_nom' => 'Categoria 1',
            'nivel' => 2
        ];

        // Mockear ejecutarConsulta para que retorne los datos de una categoría específica
        $this->categoria->method('ejecutarConsulta')->willReturn([$categoria]);

        $resultado = $this->categoria->get_datos_categoria($cat_id);
        $this->assertEquals([$categoria], $resultado);
    }

    // Test para el método get_datos_categoria - Caso de error (categoría no encontrada)
    public function testGetDatosCategoriaError()
    {
        $cat_id = 999;  // ID que no existe

        // Mockear ejecutarConsulta para que no retorne resultados
        $this->categoria->method('ejecutarConsulta')->willReturn([]);

        $resultado = $this->categoria->get_datos_categoria($cat_id);
        $this->assertEmpty($resultado);
    }

    // Test para el método update_categoria - Caso exitoso
    public function testUpdateCategoriaExito()
    {
        $cat_id = 1;
        $cat_nom = 'Categoria Actualizada';
        $nivel = 3;

        // Mockear ejecutarAccion para que retorne true
        $this->categoria->method('ejecutarAccion')->willReturn(true);

        $resultado = $this->categoria->update_categoria($cat_id, $cat_nom, $nivel);
        $this->assertTrue($resultado);
    }

    // Test para el método update_categoria - Caso de error (ID inválido)
    public function testUpdateCategoriaError()
    {
        $cat_id = 999;  // ID no válido
        $cat_nom = 'Categoria Invalida';
        $nivel = 999;

        // Mockear ejecutarAccion para que retorne false
        $this->categoria->method('ejecutarAccion')->willReturn(false);

        $resultado = $this->categoria->update_categoria($cat_id, $cat_nom, $nivel);
        $this->assertFalse($resultado);
    }

    // Test para el método delete_categoria - Caso exitoso
    public function testDeleteCategoriaExito()
    {
        $cat_id = 1;

        // Mockear ejecutarAccion para que retorne true
        $this->categoria->method('ejecutarAccion')->willReturn(true);

        $resultado = $this->categoria->delete_categoria($cat_id);
        $this->assertTrue($resultado);
    }

    // Test para el método delete_categoria - Caso de error (ID no válido)
    public function testDeleteCategoriaError()
    {
        $cat_id = 999;  // ID no válido

        // Mockear ejecutarAccion para que retorne false
        $this->categoria->method('ejecutarAccion')->willReturn(false);

        $resultado = $this->categoria->delete_categoria($cat_id);
        $this->assertFalse($resultado);
    }

    // Test para el método get_cat_nom_by_ev_id - Caso exitoso
    public function testGetCatNomByEvIdExito()
    {
        $ev_id = 1;
        $cat_nom = ['cat_nom' => 'Categoria 1'];

        // Mockear ejecutarConsulta para que retorne el nombre de la categoría
        $this->categoria->method('ejecutarConsulta')->willReturn([$cat_nom]);

        $resultado = $this->categoria->get_cat_nom_by_ev_id($ev_id);
        $this->assertEquals(json_encode([$cat_nom]), $resultado);
    }

    // Test para el método get_cat_nom_by_ev_id - Caso de error (evento no encontrado)
    public function testGetCatNomByEvIdError()
    {
        $ev_id = 999;  // ID de evento no válido

        // Mockear ejecutarConsulta para retornar un array vacío (evento no encontrado)
        $this->categoria->method('ejecutarConsulta')->willReturn([]);

        $resultado = $this->categoria->get_cat_nom_by_ev_id($ev_id);
        $this->assertEquals(json_encode(['error' => 'Categoría no encontrada para el evento con ID: ' . $ev_id]), $resultado);
    }

    // Test para el método get_categoria_nivel - Caso exitoso
    public function testGetCategoriaNivelExito()
    {
        $categoriasConNiveles = [
            ['cat_id' => 1, 'cat_nom' => 'Categoria 1', 'nivel' => 2],
            ['cat_id' => 2, 'cat_nom' => 'Categoria 2', 'nivel' => 3],
        ];

        // Mockear ejecutarConsulta para retornar categorías con niveles
        $this->categoria->method('ejecutarConsulta')->willReturn($categoriasConNiveles);

        $resultado = $this->categoria->get_categoria_nivel();
        $this->assertEquals($categoriasConNiveles, $resultado);
    }

    // Test para el método get_categoria_nivel - Caso de error (sin categorías)
    public function testGetCategoriaNivelError()
    {
        // Mockear ejecutarConsulta para retornar un array vacío (sin categorías)
        $this->categoria->method('ejecutarConsulta')->willReturn([]);

        $resultado = $this->categoria->get_categoria_nivel();
        $this->assertEmpty($resultado);
    }

    // Test para el método get_categoria_relacion_motivo - Caso exitoso
    public function testGetCategoriaRelacionMotivoExito()
    {
        $mov_id = 1;
        $categoriasRelacionadas = [
            ['cat_nom' => 'Categoria 1', 'activo' => true, 'mov_id' => 1],
        ];

        // Mockear ejecutarConsulta para retornar categorías relacionadas con el motivo
        $this->categoria->method('ejecutarConsulta')->willReturn($categoriasRelacionadas);

        $resultado = $this->categoria->get_categoria_relacion_motivo($mov_id);
        $this->assertEquals($categoriasRelacionadas, $resultado);
    }
// Test para el método get_categoria_relacion_motivo - Caso de error (sin categorías relacionadas)
public function testGetCategoriaRelacionMotivoError()
{
    $mov_id = 999;  // ID de motivo no válido

    // Mockear ejecutarConsulta para que no retorne resultados
    $this->categoria->method('ejecutarConsulta')->willReturn([]);

    // No es necesario mockear get_categoria, llamará al método real
    $resultado = $this->categoria->get_categoria_relacion_motivo($mov_id);
    
    // Verificar que el resultado esté vacío (o lo que esperes que devuelva en este caso)
    $this->assertEmpty($resultado);
}
}
