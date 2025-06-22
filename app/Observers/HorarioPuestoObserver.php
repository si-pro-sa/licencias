<?php

namespace App\Observers;

use App\Models\HorarioPuesto;
use App\Models\RangoTiempo;

class HorarioPuestoObserver
{
    /**
     * Handle the horario "creating" event.
     *
     * @param  \App\Models\HorarioPuesto  $horario
     * @return void
     */
    public function creating(HorarioPuesto $horario)
    {
        $rango = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
        $horario->cantidad_horas = (isset($horario->hora_desde, $horario->hora_hasta) ? $rango->getDiffHoras() : 0);
    }

    /**
     * Handle the horario "created" event.
     *
     * @param  \App\Models\HorarioPuesto  $horario
     * @return void
     */
    public function created(HorarioPuesto $horario)
    {
        $rango = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
        $horario->cantidad_horas = (isset($horario->hora_desde, $horario->hora_hasta) ? $rango->getDiffHoras() : 0);
    }

    /*
     * Handle the horario "updating" event.
     *
     * @param  \App\Models\HorarioPuesto  $horario
     * @return void
     */
    public function updating(HorarioPuesto $horario)
    {
        $rango = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
        $horario->cantidad_horas = (isset($horario->hora_desde, $horario->hora_hasta) ? $rango->getDiffHoras() : 0);
    }

    /**
     * Handle the horario "updated" event.
     *
     * @param  \App\Models\HorarioPuesto  $horario
     * @return void
     */
    public function updated(HorarioPuesto $horario)
    {
        $rango = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
        $horario->cantidad_horas = (isset($horario->hora_desde, $horario->hora_hasta) ? $rango->getDiffHoras() : 0);
    }

    /**
     * Handle the horario "deleted" event.
     *
     * @param  \App\Models\HorarioPuesto  $horario
     * @return void
     */
    public function deleted(HorarioPuesto $horario)
    {
    }

    /**
     * Handle the horario "restored" event.
     *
     * @param  \App\Models\HorarioPuesto  $horario
     * @return void
     */
    public function restored(HorarioPuesto $horario)
    {
        //
    }

    /**
     * Handle the horario "force deleted" event.
     *
     * @param  \App\Models\HorarioPuesto  $horario
     * @return void
     */
    public function forceDeleted(HorarioPuesto $horario)
    {
        //
    }
}
