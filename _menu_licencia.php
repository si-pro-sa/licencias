<?php
/**
 * Created by PhpStorm.
 * User: alvaro
 * Date: 21/11/19
 * Time: 23:10
 */
$menuLicencias = ['label' => 'Licencias', 'url' => '#', 'items' => [
    
    ['label' => 'Salud Ocupacional', 'url' => '#', 'items' => [
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Corto Tratamiento', 'url' => ['/Licencia&tipoLicencia=1&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],
        
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Largo Tratamiento', 'url' => ['/Licencia&tipoLicencia=2&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Enfermedades Criticas', 'url' => ['/Licencia&tipoLicencia=3&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia ART', 'url' => ['/Licencia&tipoLicencia=4&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Jubilacion por invalidez', 'url' => ['/Licencia&tipoLicencia=7&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Preparto', 'url' => ['/Licencia&tipoLicencia=8&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia por Familiar Enfermo', 'url' => ['/Licencia&tipoLicencia=11&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia 1A', 'url' => ['/Licencia&tipoLicencia=21&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],
        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=2'], 'visible' => Yii::app()->user->checkAccess('saludOcupacional')],
        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia 1B', 'url' => ['/Licencia&tipoLicencia=22&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

     

        ['label' => 'Licencia PostParto', 'url' => ['/Licencia&tipoLicencia=10&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia PostParto', 'url' => ['/Licencia&tipoLicencia=10&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia PostParto', 'url' => ['/Licencia&tipoLicencia=10&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia PostParto', 'url' => ['/Licencia&tipoLicencia=10&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia PostParto', 'url' => ['/Licencia&tipoLicencia=10&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia PostParto', 'url' => ['/Licencia&tipoLicencia=10&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia PostParto', 'url' => ['/Licencia&tipoLicencia=10&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        
    ]
    ],


    ['label' => 'Licencia Anual Reglamentaria', 'url' => '#', 'items' => [
        
        ['label' => 'Licencia DLA', 'url' => ['/Licencia&tipoLicencia=17&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia DLA', 'url' => ['/Licencia&tipoLicencia=17&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia DLA', 'url' => ['/Licencia&tipoLicencia=17&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia DLA', 'url' => ['/Licencia&tipoLicencia=17&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia DLA', 'url' => ['/Licencia&tipoLicencia=17&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia DLA', 'url' => ['/Licencia&tipoLicencia=17&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia DLA', 'url' => ['/Licencia&tipoLicencia=17&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Anual Ordinario', 'url' => ['/Licencia&tipoLicencia=16&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Anual Ordinario', 'url' => ['/Licencia&tipoLicencia=16&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Anual Ordinario', 'url' => ['/Licencia&tipoLicencia=16&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Anual Ordinario', 'url' => ['/Licencia&tipoLicencia=16&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Anual Ordinario', 'url' => ['/Licencia&tipoLicencia=16&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Anual Ordinario', 'url' => ['/Licencia&tipoLicencia=16&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Anual Ordinario', 'url' => ['/Licencia&tipoLicencia=16&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Proporcional', 'url' => ['/Licencia&tipoLicencia=25&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Proporcional', 'url' => ['/Licencia&tipoLicencia=25&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Proporcional', 'url' => ['/Licencia&tipoLicencia=25&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Proporcional', 'url' => ['/Licencia&tipoLicencia=25&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Proporcional', 'url' => ['/Licencia&tipoLicencia=25&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Proporcional', 'url' => ['/Licencia&tipoLicencia=25&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Proporcional', 'url' => ['/Licencia&tipoLicencia=25&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Postergación de Lao', 'url' => ['/Licencia&tipoLicencia=26&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Postergación de Lao', 'url' => ['/Licencia&tipoLicencia=26&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Postergación de Lao', 'url' => ['/Licencia&tipoLicencia=26&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Postergación de Lao', 'url' => ['/Licencia&tipoLicencia=26&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Postergación de Lao', 'url' => ['/Licencia&tipoLicencia=26&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Postergación de Lao', 'url' => ['/Licencia&tipoLicencia=26&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Postergación de Lao', 'url' => ['/Licencia&tipoLicencia=26&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Anticipo de Lao', 'url' => ['/Licencia&tipoLicencia=27&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Anticipo de Lao', 'url' => ['/Licencia&tipoLicencia=27&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Anticipo de Lao', 'url' => ['/Licencia&tipoLicencia=27&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Anticipo de Lao', 'url' => ['/Licencia&tipoLicencia=27&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Anticipo de Lao', 'url' => ['/Licencia&tipoLicencia=27&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Anticipo de Lao', 'url' => ['/Licencia&tipoLicencia=27&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Anticipo de Lao', 'url' => ['/Licencia&tipoLicencia=27&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],


    ]
    ],

    ['label' => 'Licencias Particulares Sin Goce de Sueldo', 'url' => '#', 'items' => [
        ['label' => 'Licencia Sin Goce de Sueldo', 'url' => ['/Licencia&tipoLicencia=15&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Sin Goce de Sueldo', 'url' => ['/Licencia&tipoLicencia=15&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Sin Goce de Sueldo', 'url' => ['/Licencia&tipoLicencia=15&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Sin Goce de Sueldo', 'url' => ['/Licencia&tipoLicencia=15&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Sin Goce de Sueldo', 'url' => ['/Licencia&tipoLicencia=15&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Sin Goce de Sueldo', 'url' => ['/Licencia&tipoLicencia=15&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],
        ['label' => 'Licencia Deportiva Rentada', 'url' => ['/Licencia&tipoLicencia=6&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Deportiva Rentada', 'url' => ['/Licencia&tipoLicencia=6&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Deportiva Rentada', 'url' => ['/Licencia&tipoLicencia=6&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Deportiva Rentada', 'url' => ['/Licencia&tipoLicencia=6&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Deportiva Rentada', 'url' => ['/Licencia&tipoLicencia=6&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Deportiva Rentada', 'url' => ['/Licencia&tipoLicencia=6&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Deportiva Rentada', 'url' => ['/Licencia&tipoLicencia=6&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],
        ['label' => 'Licencia Cargo Público', 'url' => ['/Licencia&tipoLicencia=30&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Cargo Público', 'url' => ['/Licencia&tipoLicencia=30&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Cargo Público', 'url' => ['/Licencia&tipoLicencia=30&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Cargo Público', 'url' => ['/Licencia&tipoLicencia=30&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Cargo Público', 'url' => ['/Licencia&tipoLicencia=30&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Cargo Público', 'url' => ['/Licencia&tipoLicencia=30&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Cargo Público', 'url' => ['/Licencia&tipoLicencia=30&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],
        ['label' => 'Licencia Otra Causal PE', 'url' => ['/Licencia&tipoLicencia=31&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Otra Causal PE', 'url' => ['/Licencia&tipoLicencia=31&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Otra Causal PE', 'url' => ['/Licencia&tipoLicencia=31&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Otra Causal PE', 'url' => ['/Licencia&tipoLicencia=31&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Otra Causal PE', 'url' => ['/Licencia&tipoLicencia=31&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Otra Causal PE', 'url' => ['/Licencia&tipoLicencia=31&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Otra Causal PE', 'url' => ['/Licencia&tipoLicencia=31&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],
       
    ]
    ],

    ['label' => 'Licencias Particulares con Goce de Sueldo', 'url' => '#', 'items' => [
        ['label' => 'Licencia Matrimonio', 'url' => ['/Licencia&tipoLicencia=9&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
    ['label' => 'Licencia Matrimonio', 'url' => ['/Licencia&tipoLicencia=9&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
    ['label' => 'Licencia Matrimonio', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
    ['label' => 'Licencia Matrimonio', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
    ['label' => 'Licencia Matrimonio', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
    ['label' => 'Licencia Matrimonio', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
    ['label' => 'Licencia Matrimonio', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

    ['label' => 'Licencia Nacimiento', 'url' => ['/Licencia&tipoLicencia=12&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
    ['label' => 'Licencia Nacimiento', 'url' => ['/Licencia&tipoLicencia=12&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
    ['label' => 'Licencia Nacimiento', 'url' => ['/Licencia&tipoLicencia=12&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
    ['label' => 'Licencia Nacimiento', 'url' => ['/Licencia&tipoLicencia=12&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
    ['label' => 'Licencia Nacimiento', 'url' => ['/Licencia&tipoLicencia=12&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
    ['label' => 'Licencia Nacimiento', 'url' => ['/Licencia&tipoLicencia=12&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
    ['label' => 'Licencia Nacimiento', 'url' => ['/Licencia&tipoLicencia=12&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

    ['label' => 'Licencia Deportiva No Rentada', 'url' => ['/Licencia&tipoLicencia=32&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Deportiva No Rentada', 'url' => ['/Licencia&tipoLicencia=32&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Deportiva No Rentada', 'url' => ['/Licencia&tipoLicencia=32&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Deportiva No Rentada', 'url' => ['/Licencia&tipoLicencia=32&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Deportiva No Rentada', 'url' => ['/Licencia&tipoLicencia=32&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Deportiva No Rentada', 'url' => ['/Licencia&tipoLicencia=32&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Deportiva No Rentada', 'url' => ['/Licencia&tipoLicencia=32&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Adopcion', 'url' => ['/Licencia&tipoLicencia=9&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Adopcion', 'url' => ['/Licencia&tipoLicencia=9&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Adopcion', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Adopcion', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Adopcion', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Adopcion', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Adopcion', 'url' => ['/Licencia&tipoLicencia=9&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia Fallecimiento de un Familiar', 'url' => ['/Licencia&tipoLicencia=14&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Fallecimiento de un Familiar', 'url' => ['/Licencia&tipoLicencia=14&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Fallecimiento de un Familiar', 'url' => ['/Licencia&tipoLicencia=14&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia Fallecimiento de un Familiar', 'url' => ['/Licencia&tipoLicencia=14&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia Fallecimiento de un Familiar', 'url' => ['/Licencia&tipoLicencia=14&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia Fallecimiento de un Familiar', 'url' => ['/Licencia&tipoLicencia=14&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia Fallecimiento de un Familiar', 'url' => ['/Licencia&tipoLicencia=14&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia por Examen', 'url' => ['/Licencia&tipoLicencia=28&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia por Examen', 'url' => ['/Licencia&tipoLicencia=28&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia por Examen', 'url' => ['/Licencia&tipoLicencia=28&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia por Examen', 'url' => ['/Licencia&tipoLicencia=28&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia por Examen', 'url' => ['/Licencia&tipoLicencia=28&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia por Examen', 'url' => ['/Licencia&tipoLicencia=28&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia por Examen', 'url' => ['/Licencia&tipoLicencia=28&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],

        ['label' => 'Licencia por Obligacion Militar', 'url' => ['/Licencia&tipoLicencia=29&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia por Obligacion Militar', 'url' => ['/Licencia&tipoLicencia=29&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia por Obligacion Militar', 'url' => ['/Licencia&tipoLicencia=29&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargadereemplazosyguardias')],
        ['label' => 'Licencia por Obligacion Militar', 'url' => ['/Licencia&tipoLicencia=29&role=0'], 'visible' => Yii::app()->user->checkAccess('jefedepersonahcargadereemplazosyguardias')],
        ['label' => 'Licencia por Obligacion Militar', 'url' => ['/Licencia&tipoLicencia=29&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalaocargaderi')],
        ['label' => 'Licencia por Obligacion Militar', 'url' => ['/Licencia&tipoLicencia=29&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalh')],
        ['label' => 'Licencia por Obligacion Militar', 'url' => ['/Licencia&tipoLicencia=29&role=0'], 'visible' => Yii::app()->user->checkAccess('jefepersonalao')],
    ]
    ],
    ['label' => 'Licencias por Capacitación', 'url' => '#', 'items' => [
        ['label' => 'Licencia Capacitacion', 'url' => ['/Licencia&tipoLicencia=18&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Capacitacion', 'url' => ['/Licencia&tipoLicencia=18&role=1'], 'visible' => Yii::app()->user->checkAccess('directorEfector')],
        ['label' => 'Licencia Capacitacion', 'url' => ['/Licencia&tipoLicencia=18&role=0'], 'visible' => Yii::app()->user->checkAccess('refrrhh')],

        ['label' => 'Licencia Capacitacion que excede los 7 dias', 'url' => ['/Licencia&tipoLicencia=19&role=5'], 'visible' => Yii::app()->user->checkAccess('gerencia')],        
        ['label' => 'Licencia Capacitacion que excede los 7 dias', 'url' => ['/Licencia&tipoLicencia=19&role=1'], 'visible' => Yii::app()->user->checkAccess('direccionFormacion')],
        ['label' => 'Licencia Capacitacion que excede los 7 dias', 'url' => ['/Licencia&tipoLicencia=19&role=0'], 'visible' => Yii::app()->user->checkAccess('refrrhh')],
    ]
    ],
    
    ],
];



