<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Correo.php';
final class TestCorreo extends TestCase
{
    public function testCorreo(): void
    {
        $correo = new Correo("test@test.com", "Prueba", "Esto es un mensaje de prueba");
        $resultado = $correo->enviar();
        $this->assertEquals("Correo enviado exitosamente a test@test.com", $resultado);
    }
    public function testSetDestinatario(): void
    {
        $correo = new Correo("test@test.com", "Prueba", "Esto es un mensaje de prueba");
        $correo->setDestinatario("test2@test.com");
        $resultado = $correo->enviar();
        $this->assertEquals("Correo enviado exitosamente a test2@test.com", $resultado);
    }
    public function testSetAsunto(): void
    {
        $correo = new Correo("test@test.com", "Prueba", "Esto es un mensaje de prueba");
        $correo->setAsunto("Prueba2");
        $resultado = $correo->enviar();
        $this->assertEquals("Correo enviado exitosamente a test@test.com", $resultado);
    }
    public function testSetMensaje(): void
    {
        $correo = new Correo("test@test.com", "Prueba", "Esto es un mensaje de prueba");
        $correo->setMensaje("Esto es un mensaje de prueba2");
        $resultado = $correo->enviar();
        $this->assertEquals("Correo enviado exitosamente a test@test.com", $resultado);
    }
    public function testSetGrupoDestinatario(): void
    {
        $usuarios = [
            [
                'usu_correo' => 'test@test.com',
            ],
            [
                'usu_correo' => 'test2@test.com',
            ],
            [
                'usu_correo' => 'test3@test.com',
            ],
        ];
        $correo = new Correo("", "Prueba", "Esto es un mensaje de prueba");
        $correo->setGrupoDestinatario($usuarios);
        $resultado = $correo->enviar();
        $this->assertEquals("Correo enviado exitosamente a test@test.com, test2@test.com, test3@test.com", $resultado);
    }
}
