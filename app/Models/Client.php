<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  protected $guarded = ['id', 'created_at', 'updated_at'];

  public function addresses(){
    return $this->hasMany('App\Models\Address');
  }

  public function scopeName($query, $name) {
  	if ($name) {
  		return $query->where('name','like',"%$name%");
  	}
  }

  public function scopePhone($query, $phone) {
  	if ($phone) {
  		return $query->where('phone','like',"%$phone%");
  	}
  }

  public function scopeEmail($query, $email) {
  	if ($email) {
  		return $query->where('email','like',"%$email%");
  	}
  }
}
