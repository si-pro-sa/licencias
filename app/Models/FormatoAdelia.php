<?php
namespace App\Models;

class FormatoAdelia
{
    //Columna 1: 4 caracteres, siempre poner el valor "0965"
    private string $columna1 = '0965';
    //Columna 2: 11 caracteres, corresponde al legajo del Agente, poner el dni del agente y completar con ceros a la izquierda.
    private string $legajo = '';
    //Columna 3: 6 caracteres, corresponde al código del adicional, poner poner el código de la LD/Guardia, reemplazando los guiones por "0". Para el caso de Reemplazos y Coberturas, aún no se definió un código; por ahora poner 000001 para Reemplazo y 000002 para Cobertura.
    private string $codigo = '';
    //Columna 4: 13 caracteres, no se encontró documentación de lo que significa esta columna. Poner "0000000010000"
    private string $columna4 = '0000000010000';
    //Columna 5: 11 caracteres, días de prestación o monto de la prestación, completar con ceros a la izquierda. Para cobertura o reemplazo poner "1"
    private string $dias = '';
    //Columna 6: 7 caracteres, no se encontró documentación de lo que significa esta columna. Poner "0000001"
    private string $columna6 = '0000001';
    //Columna 7: 2 caracteres, Código de movimiento, AA para alta, MM para modificación, por ahora cargar todo como "AA"
    private string $movimiento = '';
    //Columna 8: 10 caracteres, fecha a partir de la cual se inicia la LD, Guardia, Cobertura o reemplazo.
    private string $fechaDesde = '';
    //Columna 9: 10 caracteres, fecha en la que finaliza la LD, Guardia, Cobertura o reemplazo.
    private string $fechaHasta = '';
    //Columna 10: 10 caracteres, fecha en que se registra el movimiento, enviar la fecha del día.
    private string $fecha = '';
    //Columna 11: 8 caracteres, corresponde al DNI del agente.
    private string $documento = '';

    public function __construct(int $documento, string $codigo, int $dias, string $fechaDesde, string $fechaHasta, string $fecha, string $movimiento = 'AA')
    {
        $this->setDocumento($documento);
        $this->setCodigo($codigo);
        $this->setDias($dias);
        $this->setFechaDesde($fechaDesde);
        $this->setFechaHasta($fechaHasta);
        $this->setFecha($fecha);
        $this->setMovimiento($movimiento);
    }

    public function setDocumento(int $documento): void
    {
        $this->documento = sprintf('%08d', $documento);
        $this->legajo = sprintf('%011d', $documento);
    }

    public function setCodigo(string $codigo): void
    {
        $this->codigo = sprintf('%06d', str_replace('-', '0', $codigo));
    }

    public function setDias(int $dias): void
    {
        $this->dias = sprintf('%011d', (string) $dias);
    }

    public function setMovimiento(string $movimiento): void
    {
        $this->movimiento = sprintf('%02d', $movimiento);
    }

    public function setFechaDesde(string $fechaDesde): void
    {
        $this->fechaDesde = date('d/m/Y', strtotime($fechaDesde));
    }

    public function setFechaHasta(string $fechaHasta): void
    {
        $this->fechaHasta = date('d/m/Y', strtotime($fechaHasta));
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = date('d/m/Y', strtotime($fecha));
    }

    // Un ejemplo de una línea del archivo de intercambio sería:
    // 0965000004954465000000000001000000006237.940000001AA01/02/202228/02/202201/02/202217614484
    public function getTextoFormateado(): string
    {
        return "{$this->columna1}{$this->legajo}{$this->codigo}{$this->columna4}{$this->dias}{$this->columna6}{$this->movimiento}{$this->fechaDesde}{$this->fechaHasta}{$this->fecha}{$this->documento}";
    }
}
