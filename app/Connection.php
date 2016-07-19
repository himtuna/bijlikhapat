<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// use App\Reading;

class Connection extends Model
{
    //

    protected $guarded = ['id','user_id','slug'];
    
    public function bills()
    {
    	return $this->hasMany('App\Bill')->with('readings');
    }
    
    public function billcurrent()
    {
    	return $this->hasOne('App\Bill')->where('status','Current')->with('readings');
    }

        
}
