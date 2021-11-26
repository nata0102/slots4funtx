<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameCatalog extends Model
{
	protected $table = 'game_catalog';

	protected $guarded = ['id', 'created_at', 'updated_at'];

	public function brands(){
      return $this->hasMany('App\Models\GameBrand','game_catalog_id','id');
    }
}
