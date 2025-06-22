public function getFechaHasta(){
// Verifico que tenga una baja asociada
    $criteria = new CDbCriteria();
    $criteria->select = 'fhasta';
    $criteria->limit = 1;
    $criteria->order = 'fhasta DESC';
    $condition = 'idld_estado=3 AND idld_alta=' . $this->idld_alta;
    $criteria->condition = $condition . ' AND idld_tipo_cambio_estado IN (2,3,4,5,6)';
    $baja = LdCambioEstado::model()->find($criteria);
    if (isset($baja)) {
        return $baja->fhasta;
    }
    //Obtengo última continuidad que me devolverá la última fecha
    $criteria->condition = $condition . ' AND idld_tipo_cambio_estado=1';
    $continuidad = LdCambioEstado::model()->find($criteria);
    if (isset($continuidad)) { 
        return $continuidad->fhasta; 
    } 
    //Si no tiene ni baja ni continuidad, entonces devuelvo la fecha del vencimiento del alta 
    return $this->fhasta;

}

--------------------------------------------------------
$fhastaLibre = $epLd->getFechaHasta();
$fechaDesdeLibre = strtotime($epLd->fdesde);
$fechaHastaLibre = ($fhastaLibre !== null && $fhastaLibre !== '') ? strtotime($fhastaLibre) : null;
$fechaDesdePeriodo = strtotime($periodo1->fdesde);
$fechaHastaPeriodo = strtotime($periodo1->fhasta);

//Compruebo que la libre esté dentro de las fechas que están determinadas en un período
$condicion = ((is_null($fechaHastaLibre) && $fechaDesdeLibre <= $fechaDesdePeriodo) 
    ($fechaDesdeLibre <=$fechaDesdePeriodo && $fechaHastaLibre>= $fechaHastaPeriodo)
    ($fechaDesdeLibre <= $fechaDesdePeriodo && $fechaHastaLibre>= $fechaDesdePeriodo && $fechaHastaLibre <= $fechaHastaPeriodo) 
    ($fechaDesdeLibre>= $fechaDesdePeriodo && $fechaDesdeLibre <= $fechaHastaPeriodo && $fechaHastaLibre>= $fechaDesdePeriodo && $fechaHastaLibre <= $fechaHastaPeriodo) 
    ($fechaDesdeLibre>= $fechaDesdePeriodo && $fechaDesdeLibre <= $fechaHastaPeriodo && $fechaHastaLibre>= $fechaHastaPeriodo)
);

-- FUNCTION: public.ld_nose(bigint, character varying, character varying)

DROP FUNCTION public.ld_nose(bigint, character varying, character varying);

CREATE OR REPLACE FUNCTION public.ld_nose(
	ld_idalta bigint DEFAULT 1,	
	"fechaDesdePeriodo" character varying DEFAULT (
	(
	2000 - 1) - 1),
	"fechaHastaPeriodo" character varying DEFAULT (
	(
	2000 - 1) - 1),
	OUT Vidld_alta bigint,	
	OUT fhasta date,
	OUT tipo integer)
    RETURNS record	
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 	
AS $BODY$
declare 
	b	integer;
	condicion bool;
BEGIN
Vidld_alta := 114;
b := null;
condicion := false;
fhasta := (select lc.fhasta from public.ld_cambio_estado as lc where lc.idld_estado=3 and lc.idld_alta=Vidld_alta and lc.idld_tipo_cambio_estado in (2,3,4,5,6) order by lc.idld_cambio_estado limit 1);
--fhasta := '2002-05-01'::date;
tipo := 0;
IF fhasta is null THEN
	fhasta := (select lc.fhasta from public.ld_cambio_estado as lc where lc.idld_estado=3 and lc.idld_alta=Vidld_alta and lc.idld_tipo_cambio_estado in (1) order by lc.idld_cambio_estado limit 1);
	tipo := 1;
END IF;
IF fhasta is null THEN
	fhasta := (select lc.fhasta from public.ld_alta as ld where ld.idld_alta=Vidld_alta);
	tipo := 2;
END IF;

If fhasta is not null then
	condicion := true;
	raise info 'todo bien'
ELSIF then
else then