<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__.'/../models/Unidad.php';
require_once __DIR__.'/../models/SeguridadPassword.php';


use PHPUnit\Framework\TestCase;

class TestUnidad extends TestCase{
  private $unidad;
  private $seguridad;

  protected function setUp(): void {
      // Crear un mock de la clase Usuario, especificando los métodos que se simularán
      $this->unidad = $this->getMockBuilder(Unidad::class)
                            ->onlyMethods(["ejecutarConsulta","ejecutarAccion"])
                            ->getMock();
  }

  public function testVerUnidad(){
    $datos = ['id_unidad' => 1];
    $this->unidad->method('ejecutarConsulta')->willReturn($datos);
    $resultado = $this->unidad->get_unidad();
    $this->assertEquals($datos, $resultado);
  }

  public function testGetDatosUnidad(){
    $datos = ['id_unidad' => 1];
    $this->unidad->method('ejecutarConsulta')->willReturn($datos);
    $resultado = $this->unidad->get_datos_unidad(1);
    $this->assertEquals($datos, $resultado);
  }

  public function testGetUnidadEstado(){
    $datos = ['id_unidad' => 1];
    $this->unidad->method('ejecutarConsulta')->willReturn($datos);
    $resultado = $this->unidad->get_unidad_est(1);
    $this->assertEquals($datos, $resultado);
  }

}
