<?php

namespace App\Observers;

use App\Models\Puesto;
use Illuminate\Support\Facades\Auth;

class PuestoObserver
{
    /**
     * Handle the puesto "creating" event.
     *
     * @param  \App\Models\Puesto  $puesto
     * @return void
     */
    public function creating(Puesto $puesto)
    {
        $puesto->usuario = Auth::user()->nombreusuario ?? 'estela';
        $puesto->operacion = 'A';
        $puesto->foperacion = date('Y-m-d H:i:s');
    }

    /**
     * Handle the puesto "created" event.
     *
     * @param  \App\Models\Puesto  $puesto
     * @return void
     */
    public function created(Puesto $puesto)
    {
    }

    /**
     * Handle the puesto "updated" event.
     *
     * @param  \App\Models\Puesto  $puesto
     * @return void
     */
    public function updated(Puesto $puesto)
    {
        $puesto->usuario = Auth::user()->nombreusuario ?? 'estela';
        $puesto->operacion = 'M';
        $puesto->foperacion = date('Y-m-d H:i:s');
    }

    /**
     * Handle the puesto "deleted" event.
     *
     * @param  \App\Models\Puesto  $puesto
     * @return void
     */
    public function deleted(Puesto $puesto)
    {
        $puesto->usuario = Auth::user()->nombreusuario;
        $puesto->operacion = 'D';
        $puesto->foperacion = date('Y-m-d H:i:s');
    }

    /**
     * Handle the puesto "restored" event.
     *
     * @param  \App\Models\Puesto  $puesto
     * @return void
     */
    public function restored(Puesto $puesto)
    {
        //
    }

    /**
     * Handle the puesto "force deleted" event.
     *
     * @param  \App\Models\Puesto  $puesto
     * @return void
     */
    public function forceDeleted(Puesto $puesto)
    {
        //
    }
}
