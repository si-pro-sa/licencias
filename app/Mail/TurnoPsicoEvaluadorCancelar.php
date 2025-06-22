<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PsicoEvaluador;

class TurnoPsicoEvaluadorCancelar extends Mailable
{
    use Queueable, SerializesModels;
    protected $psicoevaluador;
    protected $candidato;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PsicoEvaluador $psicoevaluador, $candidato)
    {
        $this->psicoevaluador = $psicoevaluador;
        $this->candidato = $candidato;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('testings@delgadopetrino.com.ar')
            ->view('emails.notificacionCancelacionTurno')->with(['psicoevaluador' => $this->psicoevaluador, 'candidato' => $this->candidato]);
    }
}
