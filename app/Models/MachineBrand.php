<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineBrand extends Model
{
  protected $table = 'machine_brands';

	protected $fillable = ['id', 'brand', 'model', 'weight', 'active'];

  //protected $guarded = ['id', 'created_at', 'updated_at'];
}
