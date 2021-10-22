<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartHistory extends Model
{
  public $timestamps = false;

  protected $table = 'part_history';

  protected $fillable = ['id', 'part_id', 'active','lkp_status_id','machine_id'];

  public function status(){
    return $this->hasOne('App\Models\Lookup', 'id', 'lkp_status_id');
  }

  public function machine(){
    return $this->hasOne('App\Models\Machine', 'id', 'machine_id');
  }

  public function part(){
    return $this->hasOne('App\Models\Part', 'id', 'part_id');
  }
}
