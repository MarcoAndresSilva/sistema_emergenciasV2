<?php

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__.'/../models/Usuario.php';

use PHPUnit\Framework\TestCase;

class TestUsuario extends TestCase {
    private $usuario;

    protected function setUp(): void {
        // Crear un mock de la clase Usuario, especificando los métodos que se simularán
        $this->usuario = $this->getMockBuilder(Usuario::class)
                              ->onlyMethods([
                                'ejecutarConsulta',
                                'ejecutarAccion',
                                'crearSesionUsuario',
                                'GetIpCliente',
                                'get_login_usuario',
                              ])
                              ->getMock();
    }

    // Test para verificar cuando los campos están vacíos
    public function testLoginCamposNulos() {
        // Se espera que redirija a la página de campos vacíos
        $resultado = $this->usuario->login('res', null);
        $this->assertEquals('camposvacios', $resultado);
    }
    public function testLoginCamposStringVacios() {
        // Se espera que redirija a la página de campos vacíos
        $resultado = $this->usuario->login('', '');
        $this->assertEquals('camposvacios', $resultado);
    }

    // Test para verificar cuando el usuario o la contraseña no son correctos
    public function testLoginDatosIncorrectos() {
        $this->usuario->method('ejecutarConsulta')->willReturn([]); // Simula que no se encuentra el usuario

        // Espera una redirección a la página de error de credenciales incorrectas
        $resultado = $this->usuario->login('usuario_incorrecto', 'clave_incorrecta');
        $this->assertEquals('datoincorecto', $resultado);
    }

    // Test para verificar cuando el login es exitoso
    public function testLoginExitoso() {
        // comprobar que si el usuario existe iniciar sesion
        $userData = [
            'usu_id' => 1,
            'usu_nom' => 'Juan',
            'usu_ape' => 'Pérez',
            'usu_tipo' => 'admin',
            'usu_name' => 'juan.perez',
            'usu_correo' => 'juan.perez@example.com',
            'usu_telefono' => '123456789',
            'usu_unidad' => 'Ventas'
        ];

        $this->usuario->method('ejecutarConsulta')->willReturn($userData);

        $resultado = $this->usuario->login('juan.perez', '123456789');
        $this->assertEquals('home', $resultado);
    }
    public function testLoginExitoso2() {
        // comprobar que si el usuario existe iniciar sesion
        $userData = [
            'usu_id' => 1,
            'usu_nom' => 'maria',
            'usu_ape' => 'jose',
            'usu_tipo' => 'admin',
            'usu_name' => 'maria.jose',
            'usu_correo' => 'maria.jose@example.com',
            'usu_telefono' => '123456789',
            'usu_unidad' => 'dga'
        ];

        $this->usuario->method('ejecutarConsulta')->willReturn($userData);

        $resultado = $this->usuario->login('maria.jose', '123456789');
        $this->assertEquals('home', $resultado);
    }
    public function testLoginValidoSession() {
       // comprobar que si inicia sesion se estan guardando datos en la sesion del usuario
        session_destroy();
        session_start();
        $userData = [
            'usu_id' => 1,
            'usu_nom' => 'maria',
            'usu_ape' => 'jose',
            'usu_tipo' => 'admin',
            'usu_name' => 'maria.jose',
            'usu_correo' => 'maria.jose@example.com',
            'usu_telefono' => '123456789',
            'usu_unidad' => 'dga'
        ];

        $this->usuario->method('ejecutarConsulta')->willReturn($userData);

        $resultado = $this->usuario->login('maria.jose', '123456789');
        $this->assertEquals('home', $resultado);
        $this->assertEquals($_SESSION["usu_id"] , $userData["usu_id"]);
        $this->assertEquals($_SESSION["usu_nom"] , $userData["usu_nom"]);
        $this->assertEquals($_SESSION["usu_ape"] , $userData["usu_ape"]);
        $this->assertEquals($_SESSION["usu_tipo"] , $userData["usu_tipo"]);
        $this->assertEquals($_SESSION["usu_correo"] , $userData["usu_correo"]);
        $this->assertEquals($_SESSION["usu_telefono"] , $userData["usu_telefono"]);
        $this->assertEquals($_SESSION["usu_unidad"] , $userData["usu_unidad"]);
        session_destroy();
  }
    public function testLoginInvalidSession() {
       // comprobar que si no inicia sesion no se estan guardando datos en la sesion
        session_destroy();
        session_start();
        $userData = [
            'usu_id' => 1,
            'usu_nom' => 'maria',
            'usu_ape' => 'jose',
            'usu_tipo' => 'admin',
            'usu_name' => 'maria.jose',
            'usu_correo' => 'maria.jose@example.com',
            'usu_telefono' => '123456789',
            'usu_unidad' => 'dga'
        ];

        $this->usuario->method('ejecutarConsulta')->willReturn([]);

        $resultado = $this->usuario->login('maria.jose', '123456789');
        $this->assertEquals('datoincorecto', $resultado);
        $this->assertEquals(null, $_SESSION["usu_id"]);
        $this->assertEquals( null, $_SESSION["usu_nom"]);
        $this->assertEquals( null, $_SESSION["usu_ape"]);
        $this->assertEquals( null, $_SESSION["usu_tipo"]);
        $this->assertEquals( null, $_SESSION["usu_correo"]);
        $this->assertEquals( null, $_SESSION["usu_telefono"]);
        $this->assertEquals( null, $_SESSION["usu_unidad"]);
        session_destroy();
    }
  public function testGetTipoUsuario() {
    $userData = [
      "usu_id" => 1,
      "usu_nom" => "maria",
      "usu_ape" => "jose",
      "usu_tipo" => "admin",
      "usu_name" => "maria.jose",
      "usu_correo" => "maria.jose@example.com",
      "usu_telefono" => "123456789",
      "usu_unidad" => "dga",
    ];
    $this->usuario->method('ejecutarConsulta')->willReturn($userData);
    $resultado = $this->usuario->get_tipo(1);

    $this->assertEquals($resultado, $userData);

  }
  public function testGetTipoUsuarioError() {
    $userData = [];
    $this->usuario->method('ejecutarConsulta')->willReturn($userData);
    $resultado = $this->usuario->get_tipo(1);

    $this->assertFalse($resultado);
  }
  public function testGetDatosUsuario() {
    $userData = ["usuario" => "Maria Jose"];
    $this->usuario->method('ejecutarConsulta')->willReturn($userData);
    $resultado = $this->usuario->get_datos_contacto(1);

    $this->assertEquals($resultado, $userData);
  }
  public function testGetDatosUsuarioError() {
    $userData = [];
    $this->usuario->method('ejecutarConsulta')->willReturn($userData);
    $resultado = $this->usuario->get_datos_contacto(1);

    $this->assertEquals(0,$resultado);
  }
  public function testAddUsuario() {
    $userData = [
      "usu_id" => 1,
      "usu_nom" => "maria",
      "usu_ape" => "jose",
      "usu_tipo" => "admin",
      "usu_name" => "maria.jose",
      "usu_correo" => "maria.jose@example.com",
      "usu_telefono" => "123456789",
      "usu_unidad" => "dga",
    ];
    $this->usuario->method('ejecutarConsulta')->willReturn([]);
    $this->usuario->method('ejecutarAccion')->willReturn(true);
    $resultado = $this->usuario->add_usuario(
      "maria",
      "jose",
      "maria.jose@example.com",
      "maria.jose",
      "P4ssw-rd",
      "20-12-2021",
      1,
      1,
      "123456789",
      1,
    );
    $esperado = ["status" => "success", "message" => "Usuario agregado correctamente"];
    $this->assertEquals($resultado, $esperado  );
  }
  public function testAddUsuarioExisteUsername(){

    $this->usuario->method('ejecutarConsulta')->willReturn(true);
    $resultado = $this->usuario->add_usuario(
      "maria",
      "jose",
      "maria.jose@example.com",
      "maria.jose",
      "password",
      "20-12-2021",
      "1",
      "1",
      "123456789",
      "dga",
    );

    $esperado = ['status' => 'warning', 'message' => 'El usuario ya existe con ese nombre de usuario'];
    $this->assertEquals($resultado, $esperado  );
  }

  public function testEnableUsuario(){
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->enable_usuario(1);
    $esperado = array('status' => 'success', 'message' => 'Usuario activado con éxito');
    $this->assertEquals($resultado, $esperado);
  }
  public function testDisableUsuario(){
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->disable_usuario(2);
    $esperado = array('status' => 'success', 'message' => 'Usuario desactivado con éxito');
    $this->assertEquals($resultado, $esperado);
  }
public function testAddUsuarioPasswordInsegura(){
    $userData = [
      "usu_id" => 1,
      "usu_nom" => "maria",
      "usu_ape" => "jose",
      "usu_tipo" => "admin",
      "usu_name" => "maria.jose",
      "usu_correo" => "maria.jose@example.com",
      "usu_telefono" => "123456789",
      "usu_unidad" => "dga",
    ];
    $this->usuario->method('ejecutarConsulta')->willReturn([]);
    $this->usuario->method('ejecutarAccion')->willReturn(true);
    $resultado = $this->usuario->add_usuario(
      "maria",
      "jose",
      "maria.jose@example.com",
      "maria.jose",
      "holamundo",
      "20-12-2021",
      1,
      1,
      "123456789",
      1,
    );
    $esperado =  ["status" => "warning", "message" => "La contraseña no cumple con todos los requisitos de seguridad para esta unidad."];

    $this->assertEquals($resultado, $esperado  );
  }
  public function testUpdateUsuario(){
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->update_usuario(4,"test","TestApellido","test@correo.cl","123456789","testmemo",1,1);
    $esperado = array('status'=>"success","message"=>"Usuario actualizado con éxito");
    $this->assertEquals($resultado, $esperado);
  }

  public function testUpdateUsuarioCampoVacio(){
    $resultado = $this->usuario->update_usuario("","","","","","","","");
    $esperado = array('status' => 'warning', 'message' => 'Todos los campos son obligatorios');
    $this->assertEquals($resultado, $esperado);
  }

  public function testUpdatePassword(){

    $this->usuario->method("ejecutarConsulta")->willReturn(["usu_unidad"=>1]);
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->update_password(123,"P4ssw-rd",1);
    $esperado = ["status"=>"success", "message"=>"Contraseña actualizada con éxito"];
    $this->assertEquals($resultado,$esperado);
  }

  public function testUpdatePaswordNoCoincideOldPassword(){
    $this->usuario->method("ejecutarConsulta")->willReturn([]);
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->update_password(123,"P4ssw-rd",1);
    $esperado = ["status"=>"warning", "message"=>"La contraseña antigua no coincide"];
    $this->assertEquals($resultado,$esperado);
  }

   public function testUpdatePasswordOldYNewNoPuedenSerIguales(){

    $this->usuario->method("ejecutarConsulta")->willReturn([
      "usu_unidad"=>1,
      "usu_pass"=>md5("P4ssw-rd")
    ]);

    $resultado = $this->usuario->update_password("P4ssw-rd","P4ssw-rd",1);
    $esperado = ["status"=>"info", "message"=>"La nueva contraseña debe ser distinta a la antigua"];
    $this->assertEquals($resultado,$esperado);
  }

  public function testUpdatePasswordForce(){
    $this->usuario->method("ejecutarConsulta")->willReturn(["UNIDAD"=>1]);
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->update_password_force("P4ssw-rd",1);
    $esperado =  ['status' => 'success', 'message' => 'Contraseña actualizada con éxito'];
    $this->assertEquals($resultado, $esperado);
  }
  public function testUpdatePasswordForceConPasswordInsegura(){
    $this->usuario->method("ejecutarConsulta")->willReturn(["UNIDAD"=>1]);
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->update_password_force("holamundo",1);
    $esperado =  ['status' => 'warning', 'message' => 'La contraseña no cumple con todos los requisitos de seguridad para esta unidad.'];
    $this->assertEquals($resultado, $esperado);
  }
  public function testUpdatePhoneUsuario(){
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->update_phone(123456789,1);
    $esperado =  ['status' => 'success', 'message' => 'Número de teléfono actualizado con éxito'];

    $this->assertEquals($resultado, $esperado);
  }
  public function testUpdatePhoneUsuarioFalse(){
    $this->usuario->method("ejecutarAccion")->willReturn(false);
    $resultado = $this->usuario->update_phone(123456789,1);
    $esperado =  ['status' => 'info', 'message' => 'No se realizó ningún cambio'];
    $this->assertEquals($resultado, $esperado);
  }
  public function testUpdatePhoneUsuarioNumeroCorto(){
    $resultado = $this->usuario->update_phone(1234568,1);
    $esperado =  ['status' => 'warning', 'message' => 'La longitud del número de teléfono no es correcta'];
    $this->assertEquals($resultado, $esperado);
  }
  public function testGetInfoUsuarioExitoso() {
    $userData = [
        "Nombre Completo" => "Juan Pérez",
        "Tipo" => "admin",
        "Telefono" => "123456789",
        "Correo" => "juan.perez@example.com",
        "Unidad" => "Ventas",
        "Usuario" => "juan.perez"
    ];

    $this->usuario->method('ejecutarConsulta')->willReturn($userData);

    $resultado = $this->usuario->get_info_usuario(1);
    $this->assertEquals('success', $resultado['status']);
  }
  public function testGetInfoUsuarioError() {

    $this->usuario->method('ejecutarConsulta')->willReturn([]);

    $resultado = $this->usuario->get_info_usuario(9999);
    $this->assertEquals('error', $resultado['status']);
    $this->assertEquals('No se puede obtener los datos', $resultado['message']);
    $this->assertEquals($userData, $resultado["result"]);
  }

  public function testGetFullUsuarios(){
    $usersData = [[
        "Nombre Completo" => "Juan Pérez",
        "Tipo" => "admin",
        "Telefono" => "123456789",
        "Correo" => "juan.perez@example.com",
        "Unidad" => "Ventas",
        "Usuario" => "juan.perez"
    ],[
        "Nombre Completo" => "Juan Pérez",
        "Tipo" => "admin",
        "Telefono" => "123456789",
        "Correo" => "juan.perez@example.com",
        "Unidad" => "Ventas",
        "Usuario" => "juan.perez"
    ]];

    $this->usuario->method("ejecutarConsulta")->willReturn($usersData);
    $resultado=$this->usuario->get_full_usuarios();

    $this->assertEquals('success', $resultado['status']);
    $this->assertEquals('Se obtienen los datos', $resultado['message']);
    $this->assertEquals($usersData, $resultado['result']);
  }

  public function testGetFullUsuariosSinDatos(){
    $usersData = [];

    $this->usuario->method("ejecutarConsulta")->willReturn($usersData);
    $resultado=$this->usuario->get_full_usuarios();

    $this->assertEquals('error', $resultado['status']);
    $this->assertEquals('No se puede obtener los datos', $resultado['message']);
  }

  public function testUpdateUsuarioTipoErrorTipoVacio(){
    $resultado = $this->usuario->update_usuario_tipo(1,"");
    $this->assertEquals("warning",$resultado["status"]);
    $this->assertEquals('El tipo de usuario es obligatorio',$resultado["message"]);

  }
  public function testUpdateUsuarioTipoSinCambios(){
    $this->usuario->method("ejecutarAccion")->willReturn(false);
    $resultado = $this->usuario->update_usuario_tipo(1,1);
    $this->assertEquals("info",$resultado["status"]);
    $this->assertEquals('No se realizó ningún cambio',$resultado["message"]);
  }
  public function testUpdateUsuarioTipoCorrecto(){
    $this->usuario->method("ejecutarAccion")->willReturn(true);
    $resultado = $this->usuario->update_usuario_tipo(1,1);
    $this->assertEquals("success",$resultado["status"]);
    $this->assertEquals('Tipo de usuario actualizado con éxito',$resultado["message"]);
  }
  public function testDatosUsuariosPorTipo(){
    $usersData = [[
        "Nombre Completo" => "Juan Pérez",
        "Tipo" => "admin",
        "Telefono" => "123456789",
        "Correo" => "juan.perez@example.com",
        "Unidad" => "Ventas",
        "Usuario" => "juan.perez"
    ],[
      "Nombre Completo" => "maria jose",
      "Tipo" => "admin",
      "Correo" => "maria.jose@example.com",
      "Telefono" => "123456789",
      "Unidad" => "dga",
      "Usuario"=>"maria.jose"
    ]];


    $this->usuario->method("ejecutarConsulta")->willReturn($usersData);
    $resultado = $this->usuario->get_full_usuarios_tipo(1);
    $this->assertEquals($usersData,$resultado);
  }

}
