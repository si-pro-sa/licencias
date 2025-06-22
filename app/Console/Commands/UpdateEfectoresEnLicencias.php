<?php

namespace App\Console\Commands;

use App\Models\Licencia;
use App\Models\Dependencia;
use Illuminate\Console\Command;

class UpdateEfectoresEnLicencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:licenciasEfectores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Table Licencias with Efectores to fulfill stakeholders reqs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $Licencias = Licencia::join('agente', 'licencias.idagente', '=', 'agente.idagente')
            ->join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente')
                    ->where('puesto.fhasta', '=', null);
            })
            ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
            ->select(['dependencia.iddependencia as iddependencia', 'licencias.idlicencia as idlicencia'])
            ->get();
        $Licencias->each(function ($item, $key) {
            $item['efector'] = Dependencia::where('dependencia', '=', Dependencia::find($item['iddependencia'])->getPadre())
                ->pluck('iddependencia')->first();
        });

        foreach ($Licencias as $licencia) {
            $licenciaModificar = Licencia::find($licencia['idlicencia']);

            $licenciaModificar->update([
                'idefector' => $licencia['efector']
            ]);
        }

    }
}
