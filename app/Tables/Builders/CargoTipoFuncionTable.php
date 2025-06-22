<?php
/**
 * Created by PhpStorm.
 * User: jesus
 * Date: 1/3/2019
 * Time: 21:38
 */
namespace App\Tables\Builders;

class CargoTipoFuncionTable extends Table
{
    const TemplatePath = __DIR__.'/../Templates/cargoTipoFuncion.json';

    public function query()
    {
        return '';
    }

    public function templatePath()
    {
        return self::TemplatePath;
    }
}
