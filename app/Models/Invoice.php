<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function client(){
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }
}
