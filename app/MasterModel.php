<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MasterModel extends Model
{
    public static function boot() {
        parent::boot();

        // create a event to happen on updating
        static::updating(function($table)  {
            if (self::exists(Auth::user())){
                $table->updated_by = Auth::user()->idusuario;
            }
        });

// create a event to happen on deleting
        static::deleting(function($table)  {
            $table->deleted_by = Auth::user()->idusuario;
            $table->save();
        });

// create a event to happen on saving
        static::saving(function($table)  {
            if (self::exists(Auth::user())){
                $table->created_by = Auth::user()->idusuario;
                $table->updated_by = Auth::user()->idusuario;
            }
        });
    }

    public static function exists($field){
        return (isset($field) && !empty($field));
    }
}
