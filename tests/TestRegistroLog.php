<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__.'/../models/RegistroLog.php';

class TestRegistroLog extends TestCase {

    private $registroLog;

    protected function setUp(): void {
        // Crear un mock de la clase Conectar (de la que RegistroLog hereda) para simular la conexión a la BD
        $this->registroLog = $this->getMockBuilder(RegistroLog::class)
                              ->onlyMethods(['add_log_registro', 'get_registros_log'])
                              ->getMock();
    }

    public function testAddLogRegistroSuccess() {
        // Simular que la ejecución de la acción en la base de datos sea exitosa
        $this->registroLog->method('add_log_registro')->willReturn(true);

        // Llamar al método add_log_registro con datos de prueba
        $result = $this->registroLog->add_log_registro(1, 'add', 'Registro exitoso');

        // Verificar que el resultado sea exitoso y que el mensaje sea el esperado
        $this->assertTrue($result);
    }

    public function testAddLogRegistroError() {
        // Simular que la ejecución de la acción en la base de datos falle
        $this->registroLog->method('add_log_registro')->willReturn(false);

        // Llamar al método add_log_registro con datos de prueba
        $result = $this->registroLog->add_log_registro("test", 'add', 'Registro fallido');

        $this->assertFalse($result);

    }

    public function testGetRegistrosLogSuccess() {
        // Simular la consulta de datos de registros
        $this->registroLog->method('get_registros_log')->willReturn([ [
          'id' => 1,
          'nombre' => 'John Doe',
          'apellido' => 'Doe',
          'correo' => 'john@example.com',
          'usuario' => 'john',
          'fecha' => '2023-03-01',
          'operacion' => 'add',
          'detalle' => 'Registro exitoso',
        ] ]);

        // Llamar al método get_registros_log
        $result = $this->registroLog->get_registros_log();

        // Verificar que el resultado sea exitoso
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('John Doe', $result[0]['nombre']);
        $this->assertEquals('Doe', $result[0]['apellido']);
        $this->assertEquals('john@example.com', $result[0]['correo']);
        $this->assertEquals('john', $result[0]['usuario']);
        $this->assertEquals('2023-03-01', $result[0]['fecha']);
        $this->assertEquals('add', $result[0]['operacion']);
        $this->assertEquals('Registro exitoso', $result[0]['detalle']);
    }

    public function testGetRegistrosLogError() {
        // Simular que la consulta de datos de registros falle
        $this->registroLog->method('get_registros_log')->willReturn(false);

        // Llamar al método get_registros_log
        $result = $this->registroLog->get_registros_log();

        // Verificar que el resultado sea una advertencia
        $this->assertFalse($result);
    }
}
