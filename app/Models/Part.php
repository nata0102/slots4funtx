<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lookup;

class Part extends Model
{

  protected $table = 'parts';

	protected $fillable = ['id', 'brand', 'model', 'serial', 'price', 'weight', 'image', 'description', 'lkp_type_id', 'lkp_status_id', 'lkp_protocol_id'];

  protected $guarded = ['id', 'brand', 'model', 'serial', 'price', 'weight', 'image', 'description','created_at', 'updated_at'];

  public function type(){
      return $this->hasOne('App\Models\Lookup', 'id', 'lkp_type_id');
  }

  public function status(){
      return $this->hasOne('App\Models\Lookup', 'id', 'lkp_status_id');
  }

  public function protocol(){
      return $this->hasOne('App\Models\Lookup', 'id', 'lkp_protocol_id');
  }

  public function machine(){
      return $this->hasOne('App\Models\Machine', 'id', 'machine_id');
  }

  public function brand(){
        return $this->hasOne('App\Models\MachineBrand', 'id', 'brand_id');
  }

  public static function scopeStatus1($query, $status) {
  	if ($status) {

      return $query->whereHas('status', function($q) use($status){
          $q->where('id', 'LIKE', "%$status%");
      });

  	}
  }

  public function scopeType1($query, $type) {
  	if ($type) {
      return $query->whereHas('type', function($q) use($type){
          $q->where('id', 'LIKE', "%$type%");
      });
  	}
  }

  public function scopeModel($query, $model) {
  	if ($model) {
  		return $query->where('model','like',"%$model%");
  	}
  }

  public function scopeBrand($query, $brand) {
  	if ($brand) {
  		return $query->where('id','like',"%$brand%");
  	}
  }

}
