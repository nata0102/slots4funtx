<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameBrand extends Model
{
	protected $table = 'game_brands';

	protected $guarded = ['id'];

    public $timestamps = false;

    public function brand(){
        return $this->hasOne('App\Models\MachineBrand', 'id', 'machine_brand_id');
    }

}
