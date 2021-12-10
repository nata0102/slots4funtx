<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineBrand extends Model
{
  protected $table = 'machine_brands';

	protected $fillable = ['id', 'brand', 'model', 'weight', 'active'];

  public function images(){
    return $this->hasMany('App\PartImage','part_brand_id');
  }

	public function type(){
    return $this->hasOne('App\Models\Lookup', 'id', 'lkp_type_id');
  }

  public function part(){
    return $this->hasOne('App\Models\Lookup', 'id', 'lkp_part_id');
  }

  public function scopeModel($query, $model) {
    if ($model) {
      return $query->where('model','like',"%$model%");
    }
  }

  public function scopeBrand($query, $brand) {
    if ($brand) {
      return $query->where('brand','like',"%$brand%");//->orWhere('model', 'like', "%$brand%");
    }
  }

  public function scopeType($query, $type) {
  	if ($type) {
  		return $query->where('lkp_type_id','like',"%$type%");
  	}
  }

  public function scopePart($query, $part) {
    if ($part) {
      return $query->where('lkp_part_id','=',$part);
    }
  }
}
