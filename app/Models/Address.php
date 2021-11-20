<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function client(){
    	return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }

    public function cliente(){
    	return $this->belongsTo('App\Models\Client');
    }

    public function city(){
      return $this->hasOne('App\Models\Lookup','id','lkp_city_id');
    }

    public function county(){
      return $this->hasOne('App\Models\Lookup','id','lkp_county_id');
    }
}
