<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $table = 'invoices_payments';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
