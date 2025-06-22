<?php

use App\Models\Agente;
use App\Models\Candidato;
use App\Models\Domicilio;
use App\Models\EvaluacionPsicotecnica;
use App\Models\EvaluacionPsicotecnicaGrupo;
use App\Models\ExtendidoEvaluacionPsicotecnica;
use App\Models\RecomendacionCandidato;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgenteCandidatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::beginTransaction();
        try{
            factory(EvaluacionPsicotecnicaGrupo::class,5)->create();
            factory(Agente::class,10)->make()->each(function ($ag){
                $agente = Agente::find($ag->idagente);
                if (!empty($agente)){
                    if (!isset($agente->iddomicilio) || empty($agente->iddomicilio)){
                        $domicilio = factory(Domicilio::class)->make();
                        $domicilio->save();
                        $agente->iddomicilio = $domicilio->iddomicilio;
                        $agente->save();
                    }
                    $recomendacion = factory(RecomendacionCandidato::class)->make();
                    $recomendacion->candidato_id = $agente->idagente;
                    $recomendacion->candidato_type = get_class($agente);
                    $recomendacion->save();
                    print_r('\nRecomendacion creada');
                    factory(EvaluacionPsicotecnica::class,3)->make()->each(function ($ev) use($agente) {
                        $ev->evaluacion_psicotecnica_id = $agente->idagente;
                        $ev->evaluacion_psicotecnica_type = get_class($agente);
                        $ev->save();
                        print_r('\nEvaluacion Creada');
                        if ($ev->idtipo_recomendacion === 3){
                            $ext = factory(ExtendidoEvaluacionPsicotecnica::class)->make();
                            $ext->idevaluacion_psicotecnica = $ev->idevaluacion_psicotecnica;
                            $ext->save();
                            print_r('\n Extendido Evaluacion Creada');
                        }
                    });
                }
            });
            factory(Candidato::class,10)->make()->each(function ($ca){
                $domicilio = factory(Domicilio::class)->make();
                $domicilio->save();
                $ca->iddomicilio = $domicilio->iddomicilio;
                $ca->save();

                $recomendacion = factory(RecomendacionCandidato::class)->make();
                $recomendacion->candidato_id = $ca->idcandidato;
                $recomendacion->candidato_type = get_class($ca);
                $recomendacion->save();
                print_r('\nRecomendacion creada');
                factory(EvaluacionPsicotecnica::class,3)->make()->each(function ($ev) use($ca) {
                    $ev->evaluacion_psicotecnica_id = $ca->idcandidato;
                    $ev->evaluacion_psicotecnica_type = get_class($ca);
                    $ev->save();
                    print_r('\nEvaluacion Creada');
                    if ($ev->idtipo_recomendacion == 3){
                        $ext = factory(ExtendidoEvaluacionPsicotecnica::class)->make();
                        $ext->idevaluacion_psicotecnica = $ev->idevaluacion_psicotecnica;
                        $ext->save();
                        print_r('\n Extendido Evaluacion Creada');
                    }
                });
            });
            DB::commit();
        }catch (Exception $e){
            print_r($e->getMessage());
            DB::rollback();
        }
    }
}
