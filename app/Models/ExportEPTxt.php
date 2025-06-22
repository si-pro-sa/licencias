<?php
namespace App\Models;

use Illuminate\Support\Facades\Storage;

/**
* Class TipoAgrupamiento
* @package App\Models
* @version March 30, 2022, 7:47 pm UTC
*
*/
abstract class ExportEPTxt
{
    protected $datos = [];

    public function __construct(
        protected int $idperiodo,
        protected string $nombreArchivo,
        protected array $datosSinFormatear
    ) {
    }

    public function export()
    {
        try {
            return Storage::download("{$this->idperiodo}_{$this->nombreArchivo}.txt");
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function agregarLineasATxt(): void
    {
        try {
            Storage::put("{$this->idperiodo}_{$this->nombreArchivo}.txt", implode("\n", $this->datos));
        } catch (\Exception $e) {
            dd($e);
        }
    }

    abstract public function transformarAFormatoAdelia();
}
