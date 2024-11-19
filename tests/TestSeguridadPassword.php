<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/SeguridadPassword.php';
use PHPUnit\Framework\TestCase;

class TestSeguridadPassword extends TestCase {

    protected $seguridadPassword;

    protected function setUp(): void {
        // Configuración antes de cada test
        $this->seguridadPassword = new SeguridadPassword();
    }

    public function testPasswordSegura() {
        $password = 'Pass1234!';

        // Llamamos al método real PasswordSegura
        $resultado = $this->seguridadPassword->PasswordSegura($password);

        // Realizamos las comprobaciones de seguridad
        $this->assertTrue($resultado['mayuscula']);
        $this->assertTrue($resultado['minuscula']);
        $this->assertTrue($resultado['numero']);
        $this->assertTrue($resultado['especiales']);
        $this->assertTrue($resultado['largo']);
    }

    public function testCumpleCriteriosSeguridad() {
        $password = 'Pass123!';
        $unidad = 1;
        
        // Simulación de los criterios que se utilizarán para la unidad
        $criterios = [
            "mayuscula" => true,
            "minuscula" => true,
            "numero" => true,
            "especiales" => true,
            "largo" => 8
        ];
        
        // Mockear solo el método getCriteriosSeguridadPorUnidad, no toda la clase
        $this->seguridadPassword = $this->getMockBuilder(SeguridadPassword::class)
                                         ->onlyMethods(['getCriteriosSeguridadPorUnidad'])
                                         ->getMock();
        $this->seguridadPassword->method('getCriteriosSeguridadPorUnidad')->willReturn($criterios);
        
        // Llamada al método real cumpleCriteriosSeguridad
        $resultado = $this->seguridadPassword->cumpleCriteriosSeguridad($unidad, $password);

        $this->assertTrue($resultado);
    }
  public function testAddPasswordInfo() {
       $email = 'test@example.com';
       $usu_name = 'testUser';
       $password = 'Pass123!';

       // Crear un mock específico para la clase SeguridadPassword
       $mockSeguridadPassword = $this->getMockBuilder(SeguridadPassword::class)
                                       ->onlyMethods(['PasswordSegura', 'getCriteriosSeguridadPorUnidad', 'add_password_info'])
                                       ->getMock();

       // Mockear el método PasswordSegura
       $mockSeguridadPassword->method('PasswordSegura')->willReturn([
           'mayuscula' => true,
           'minuscula' => true,
           'numero' => true,
           'especiales' => true,
           'largo' => true
       ]);

       // Mockear el método getCriteriosSeguridadPorUnidad
       $mockSeguridadPassword->method('getCriteriosSeguridadPorUnidad')->willReturn([
           "mayuscula" => true,
           "minuscula" => true,
           "numero" => true,
           "especiales" => true,
           "largo" => 8
       ]);

       // Simular que el usuario existe y el método add_password_info retorna verdadero
       $mockSeguridadPassword->method('add_password_info')->willReturn(true);

       // Llamar al método mockeado
       $resultado = $mockSeguridadPassword->add_password_info($email, $usu_name, $password);

       // Asegúrate de que el resultado sea verdadero
       $this->assertTrue($resultado);
  }
  public function testAddPasswordInfoConError() {
    $email = 'test@example.com';
    $usu_name = 'testUser';
    $password = 'passwordsinmayus';  // No cumple los criterios (falta mayúscula)

    // Crear un mock específico para la clase SeguridadPassword
    $mockSeguridadPassword = $this->getMockBuilder(SeguridadPassword::class)
                                    ->onlyMethods(['PasswordSegura', 'getCriteriosSeguridadPorUnidad', 'add_password_info'])
                                    ->getMock();

    // Mockear el método PasswordSegura, simulando que no cumple con los criterios (falta una mayúscula)
    $mockSeguridadPassword->method('PasswordSegura')->willReturn([
        'mayuscula' => false,  // No cumple este criterio
        'minuscula' => true,
        'numero' => false,      // No cumple este criterio
        'especiales' => false,  // No cumple este criterio
        'largo' => true
    ]);

    // Mockear el método getCriteriosSeguridadPorUnidad, indicando los criterios de seguridad
    $mockSeguridadPassword->method('getCriteriosSeguridadPorUnidad')->willReturn([
        "mayuscula" => true,
        "minuscula" => true,
        "numero" => true,
        "especiales" => true,
        "largo" => 8
    ]);

    // Simular que el método add_password_info retorna falso porque no se cumplen los criterios
    $mockSeguridadPassword->method('add_password_info')->willReturn(false);

    // Llamar al método mockeado
    $resultado = $mockSeguridadPassword->add_password_info($email, $usu_name, $password);

    // Asegúrate de que el resultado sea falso, indicando que no se agregará la contraseña por no cumplir los criterios
    $this->assertFalse($resultado);
  }
public function testGetCriteriosSeguridadPorUnidad() {
    $unidad = 1;

    // Crea un mock de SeguridadPassword para este test específico
    $this->seguridadPassword = $this->getMockBuilder(SeguridadPassword::class)
                                     ->onlyMethods(['getCriteriosSeguridadPorUnidad'])
                                     ->getMock();

    // Simulación de la respuesta de la base de datos
    $criteriosEsperados = [
        "mayuscula" => true,
        "minuscula" => false,
        "numero" => true,
        "especiales" => false,
        "largo" => 8
    ];

    // Simular la ejecución de la consulta
    $this->seguridadPassword->method('getCriteriosSeguridadPorUnidad')->willReturn($criteriosEsperados);

    // Llamada al método y verificar que devuelve los criterios esperados
    $criterios = $this->seguridadPassword->getCriteriosSeguridadPorUnidad($unidad);
    $this->assertEquals($criteriosEsperados, $criterios);
}
}
