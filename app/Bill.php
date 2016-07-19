<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    
    protected $guarded = ['id','connection_id','slug'];

    public function readings()
    {
    	return $this->hasMany('App\Reading')->orderBy('created_at','desc');

    }

    
    
    public function connection()
    {
    	return $this->belongsTo('App\Connection');
    }
    

}
