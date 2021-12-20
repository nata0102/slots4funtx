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
}
