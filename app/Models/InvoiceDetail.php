<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
	protected $table = 'invoices_details';
    protected $guarded = ['id'];
    public $timestamps = false;  

    public function charges(){
        return $this->hasMany('App\Models\Charge', 'id','charge_id');
    }  
}
