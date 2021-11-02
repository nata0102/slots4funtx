<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineBrand extends Model
{
  protected $table = 'machine_brands';

	protected $fillable = ['id', 'brand', 'model', 'weight', 'active'];

  //protected $guarded = ['id', 'created_at', 'updated_at'];

	public function type(){
    return $this->hasOne('App\Models\Lookup', 'id', 'lkp_type_id');
  }

  public function scopeModel($query, $model) {
  	if ($model) {
  		return $query->where('model','like',"%$model%");
  	}
  }

  public function scopeBrand($query, $brand) {
  	if ($brand) {
  		return $query->where('brand','like',"%$brand%");
  	}
  }

  public function scopeType($query, $type) {
  	if ($type) {
  		return $query->where('lkp_type_id','like',"%$type%");
  	}
  }
}
