<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    //
    public function items()
    {
        return $this->hasMany('App\Items', 'rest_id');
    }
}
