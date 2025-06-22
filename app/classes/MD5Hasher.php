<?php
/**
 * Modificado por Alvaro Fraga.
 * Creado por Jesus Pacheco
 * Date: 26/11/2018
 * Time: 14:59
 *
 */
namespace App\classes;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class MD5Hasher extends \Illuminate\Hashing\BcryptHasher
{
    /**
     * Este check sobrescribe el check de BcryptHasher, no utiliza password_verify de Abstract porque no tiene el hash
     * los parametros para su lectura en password_get_info por lo que password_verify no puede leer el tipo de opciones
     * este check se lo puede llamar con Hash::check, el make no esta sobrescrito por lo que make sigue con el mismo algoritmo
     * Junto a este archivo debe estar el provider que por medio de boot enlaza esta clase al hash de facades.
     * Supongo que la inspiracion de esta modificacion surgio del foro, pero este a diferencia actualiza el algoritmo
     * @links https://laracasts.com/discuss/channels/laravel/laravel-5-extending-hasher
     * @param string $value
     * @param string $hashedValue
     * @param array $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = array())
    {
        return md5($value) === $hashedValue ? true : false;
    }
}
