<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineHistory extends Model
{
	protected $table = 'machine_history';
	
    protected $guarded = ['id', 'created_at'];
}
