<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TurnoPsicoEvaluador;

class TurnoPsicoEvaluadorReprogramar extends Mailable
{
    use Queueable, SerializesModels;
    protected $turno;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TurnoPsicoEvaluador $turno)
    {
        $this->turno = $turno;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('testings@delgadopetrino.com.ar')
            ->view('emails.notificacionReprogramacion')->with(['turno' => $this->turno]);
    }
}
