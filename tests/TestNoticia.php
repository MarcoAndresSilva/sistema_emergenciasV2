<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__.'/../models/Noticia.php';

class TestNoticia extends TestCase {

    private $noticia;

    protected function setUp(): void {
        // Crear un mock de la clase Conectar (de la que Noticia hereda) para simular la conexión a la BD
        $this->noticia = $this->getMockBuilder(Noticia::class)
                              ->onlyMethods(['ejecutarAccion', 'ejecutarConsulta', 'obtenerUltimoRegistro'])
                              ->getMock();
    }

    public function testAddNoticiaSuccess() {
        // Simular que la ejecución de la acción en la base de datos fue exitosa
        $this->noticia->method('ejecutarAccion')->willReturn(true);

        // Llamar al método add_noticia con datos de prueba
        $result = $this->noticia->add_noticia('Asunto de prueba', 'Mensaje de prueba', 'http://url.com');

        // Verificar que el resultado sea exitoso
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Se agregó la noticia', $result['message']);
    }

    public function testAddNoticiaWarning() {
        // Simular que la ejecución de la acción en la base de datos falló
        $this->noticia->method('ejecutarAccion')->willReturn(false);

        // Llamar al método add_noticia con datos de prueba
        $result = $this->noticia->add_noticia('Asunto de prueba', 'Mensaje de prueba', 'http://url.com');

        // Verificar que el resultado sea una advertencia
        $this->assertEquals('warning', $result['status']);
        $this->assertEquals('Problemas al agregar dato', $result['message']);
    }

    public function testCrearYEnviarNoticiaSimpleSuccess() {
        // Simular la inserción de la noticia
        $this->noticia->method('ejecutarAccion')->willReturn(true);
        $this->noticia->method('obtenerUltimoRegistro')->willReturn(['noticia_id' => 1]);

        // Simular el envío exitoso del correo
        $result = $this->noticia->crear_y_enviar_noticia_simple([
            'asunto' => 'Asunto de prueba',
            'mensaje' => 'Mensaje de prueba',
            'url' => 'http://url.com',
            'usuario_id' => 1
        ]);

        // Verificar que todo el proceso fue exitoso
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('se envio correo al usuario', $result['message']);
    }

    public function testCrearYEnviarNoticiaSimpleError() {
        // Simular que la inserción de la noticia falló
        $this->noticia->method('ejecutarAccion')->willReturn(false);

        // Simular un resultado de error cuando no se puede crear la noticia
        $result = $this->noticia->crear_y_enviar_noticia_simple([
            'asunto' => 'Asunto de prueba',
            'mensaje' => 'Mensaje de prueba',
            'url' => 'http://url.com',
            'usuario_id' => 1
        ]);

        // Verificar que el resultado es de error
        $this->assertEquals('error', $result['status']);
    }

    public function testUsuariosAEnviarSegunRegla()
    {
        // Crear un mock de la clase Noticia, simulando solo el método get_regla_envio_por_asunto
        /** @var Noticia|MockObject $mockNoticia */
        $mockNoticia = $this->getMockBuilder(Noticia::class)
                            ->onlyMethods(['get_regla_envio_por_asunto']) // Simular solo este método
                            ->getMock();

        // Configurar el comportamiento del mock para get_regla_envio_por_asunto
        $mockNoticia->method('get_regla_envio_por_asunto')
                    ->willReturn([
                        'tipo_usuario' => '1',
                        'usuario' => '2,3',
                        'seccion' => '5',
                        'unidad' => '10'
                    ]);

        // Ejecutar el método a probar usando el mock
        $resultado = $mockNoticia->usuarios_a_enviar_segun_regla('asunto_prueba');

        // Aserciones: Verificar que el resultado no está vacío y tiene los valores esperados
        $this->assertNotEmpty($resultado);
        $this->assertIsArray($resultado);
        // Aserciones adicionales según la lógica de tu método
    }

    public function testEnviarNoticiaGrupalPorListaUsuarioSuccess() {
        // Simular la inserción en la tabla de noticias por usuarios
        $this->noticia->method('ejecutarAccion')->willReturn(true);

        // Preparar una lista de usuarios
        $lista_usuarios = [
            ['usu_id' => 1],
            ['usu_id' => 2]
        ];

        // Llamar al método
        $result = $this->noticia->enviar_noticia_grupal_por_lista_usuario(1, $lista_usuarios);

        // Verificar que el envío fue exitoso
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Se envió la noticia', $result['message']);
    }

    public function testEnviarNoticiaGrupalPorListaUsuarioError() {
        // Simular que la inserción falló
        $this->noticia->method('ejecutarAccion')->willReturn(false);

        // Preparar una lista de usuarios
        $lista_usuarios = [
            ['usu_id' => 1],
            ['usu_id' => 2]
        ];

        // Llamar al método
        $result = $this->noticia->enviar_noticia_grupal_por_lista_usuario(1, $lista_usuarios);

        // Verificar que el envío falló
        $this->assertEquals('warning', $result['status']);
        $this->assertEquals('Problemas al agregar dato', $result['message']);
    }
}
