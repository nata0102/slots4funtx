<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkpPartBrand extends Model
{
    protected $table = 'parts_lkp_brands';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function brand(){
        return $this->hasOne('App\Models\MachineBrand', 'id','brand_id');
    }
}
