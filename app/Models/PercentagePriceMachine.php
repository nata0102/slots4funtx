<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PercentagePriceMachine extends Model
{
	protected $table = 'percentage_price_machine';
	
	protected $guarded = ['id', 'created_at', 'updated_at'];
}
