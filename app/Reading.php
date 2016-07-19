<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    //

    protected $guarded = ['id','bill_id','slug'];
    
    public function bill()
    {
    	return $this->belongsTo('App\Bill');
    }
    
}
