<?php

namespace App\Mail;

use App\Models\PsicoEvaluador;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TurnoPsicoEvaluador extends Mailable
{
    use Queueable, SerializesModels;

    protected $psicoevaluador;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PsicoEvaluador $psicoevaluador)
    {
        $this->psicoevaluador = $psicoevaluador;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('testings@delgadopetrino.com.ar')
            ->view('emails.notificacionpsicoevaluador')->with(['psicoevaluador' => $this->psicoevaluador]);
    }
}
