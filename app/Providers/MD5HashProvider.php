<?php
/**
 * Created by PhpStorm.
 * User: jesus
 * Date: 26/11/2018
 * Time: 14:58
 */
namespace App\Providers;

use App\classes\MD5Hasher;

class MD5HashProvider extends \Illuminate\Hashing\HashServiceProvider
{
    /**
     * En algun momento del attempt del Authenticate llama a hash de
     * una manera que llama al check que sobrescribe la clase Md5hasher
     */
    public function boot()
    {
        \App::bind('hash', function () {
            return new MD5Hasher();
        });
    }
}
