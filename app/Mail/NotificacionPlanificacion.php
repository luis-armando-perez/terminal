<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionPlanificacion extends Mailable
{
    use Queueable, SerializesModels;

    public $planificaciones;
    public $usuario;

    public function __construct($planificaciones, $usuario)
    {
        $this->planificaciones = $planificaciones;
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Tu plan de viaje')
                    ->view('emails.planificacion')
                    ->with([
                        'planificaciones' => $this->planificaciones,
                        'usuario' => $this->usuario
                    ]);
    }
}

