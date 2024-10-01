<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Formato.php';

final class TestFormato extends TestCase
{
    public function testSetAsuntoNuevoEvento(): void
    {
        $formato = new Formato();
        $datos_evento = [
            'ev_direc' => 'Direccion del evento',
            'ev_desc' => 'Descripcion del evento',
            'cat_nom' => 'Categoria del evento',
            'id_evento' => 'ID del evento',
        ];

        // Llama al método que configura el asunto y mensaje.
        $formato->setCuerpoNuevoEvento($datos_evento);

        // Comprueba que el asunto se haya configurado correctamente.
        $asuntoEsperado = "🚨Nuevo evento en Categoria del evento - 📎ticket ID del evento";
        $this->assertEquals($asuntoEsperado, $formato->asunto);
    }

    public function testSetMensajeNuevoEvento(): void
    {
        $formato = new Formato();
        $datos_evento = [
            'ev_direc' => 'Direccion del evento',
            'ev_desc' => 'Descripcion del evento',
            'cat_nom' => 'Categoria del evento',
            'id_evento' => 'ID del evento',
        ];

        // Llama al método que configura el asunto y mensaje.
        $formato->setCuerpoNuevoEvento($datos_evento);

        // Comprueba que el mensaje se haya configurado correctamente.
        $mensajeEsperado = "<p>Estimado(a),</p>";
        $mensajeEsperado .= "<p>Se ha agregado un nuevo evento.</p>";
        $mensajeEsperado .= "<p><strong>Categoría:</strong> Categoria del evento<br>";
        $mensajeEsperado .= "<strong>Detalles:</strong> Descripcion del evento<br>";
        $mensajeEsperado .= "<strong>Dirección:</strong> Direccion del evento</p>";
        $mensajeEsperado .= "<p>Para derivar el evento, haga clic en el siguiente botón:</p>";
        $mensajeEsperado .= "<p><a href='https://emergencias.melipilla.cl/view/ControlEventos/index.php?id_evento=ID del evento' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 5px;'>Derivar Evento</a></p>";
        $mensajeEsperado .= "<p>Si el botón no funciona, copie y pegue el siguiente enlace en su navegador:</p>";
        $mensajeEsperado .= "<p><a href='https://emergencias.melipilla.cl/view/ControlEventos/index.php?id_evento=ID del evento'>https://emergencias.melipilla.cl/view/ControlEventos/index.php?id_evento=ID del evento</a></p>";
        $mensajeEsperado .= "<p>Saludos cordiales,<br>";
        $mensajeEsperado .= "El equipo de eventos.</p>";

        $this->assertEquals($mensajeEsperado, $formato->mensaje);
    }
}
