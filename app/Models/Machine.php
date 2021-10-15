<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Address;


class Machine extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function status(){
        return $this->hasOne('App\Models\Lookup', 'id', 'lkp_status_id');
    }

    public function owner(){
        return $this->hasOne('App\Models\Lookup', 'id', 'lkp_owner_id');
    }

    public function address(){
        return $this->hasOne('App\Models\Address', 'id', 'address_id');
    }

   	public function brand(){
        return $this->hasOne('App\Models\MachineBrand', 'id', 'machine_brand_id');
    }

    //Query Scopes
    public function scopeGame($qry, $game){
    	if($game)
    		return $qry->where('game_title','LIKE',"%$game%");
    }
}
