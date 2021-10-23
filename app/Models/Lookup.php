<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    protected $table = 'lookups';
    protected $guarded = ['id', 'type', 'key_value', 'value', 'created_at', 'updated_at'];

    public function parent(){
        return $this->hasOne('App\Models\Lookup', 'key_value', 'type');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

}
