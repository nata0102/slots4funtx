<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function machine(){
        return $this->hasOne('App\Models\Machine', 'id', 'machine_id');
    }
}
