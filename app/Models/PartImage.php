<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartImage extends Model
{

  public $timestamps = false;
  
  protected $table = 'images_brands';

  protected $guarded = ['id', 'part_brand_id', 'name_image', 'created_at', 'updated_at'];

  public function machineBrand(){
    return $this->belongsTo('App\MachineBrand');
  }

  public function scopePart($query, $search) {
    if($search)
        $query->where('part_id',$search);
  }

  public function scopeBrand($query, $search) {
    if($search)
        $query->where('brand_id',$search);
  }

  public function part(){
        return $this->hasOne('App\Models\Lookup', 'id', 'part_id');
    }

    public function brand(){
        return $this->hasOne('App\Models\MachineBrand', 'id', 'brand_id');
    }
}
