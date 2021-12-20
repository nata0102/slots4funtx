<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartDetail extends Model
{
    public $timestamps = false;
  
  	protected $table = 'parts_details';

  	protected $guarded = ['id'];

  	public function detail(){
      return $this->hasOne('App\Models\Lookup', 'id', 'lkp_detail_id');
  	}
}
