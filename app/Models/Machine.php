<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Address;


class Machine extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function permission(){
      return $this->hasMany('App\Models\Permission','machine_id','id');
    }

    public function status(){
        return $this->hasOne('App\Models\Lookup', 'id', 'lkp_status_id');
    }

    public function owner(){
        return $this->hasOne('App\Models\Lookup', 'id', 'lkp_owner_id');
    }

    public function game(){
        return $this->hasOne('App\Models\GameCatalog', 'id', 'game_catalog_id');
    }

    public function address(){
        return $this->hasOne('App\Models\Address', 'id', 'address_id');
    }

   	public function brand(){
        return $this->hasOne('App\Models\MachineBrand', 'id', 'machine_brand_id');
    }

    public function parts(){
        return $this->hasMany('App\Models\Part', 'machine_id', 'id');
    }

    public function scopeStatussearch($query, $status) {
        switch ($status) {
            case 'all':
                break;
            case '':
                $query->whereNull('lkp_status_id');
            break;                    
            default:
                $query->where('lkp_status_id',$status);
            break;
        }
    }

    public function scopeMachine($query, $game) {
        if($game)
            $query->where('game_catalog_id',$game);
    }

    public function scopeBrand($query, $search) {
        if($search)
            $query->where('machine_brand_id',$search);
    }

    public function scopeOwner($query, $search) {
        if($search)
            $query->where('lkp_owner_id',$search);
    }

    public function scopeId($query, $search) {
        if($search)
            $query->where('id',$search);
    }

    public function scopeSerial($query, $search) {
        if($search)
            $query->where('serial','like',"%$search%");
    }
}
