<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    //
    use SoftDeletes;
    protected $appends = ['volume'];

    public function parent()
    {
        return $this->belongsTo('App\Models\Transactions', 'id_parent');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Transactions', 'id_parent')->with('children');
    }

    public function getVolumeAttribute () 
    {
        $db = $this->children->where('status', '1')->where('type', 'db')->pluck('amount')->sum();
        $cr = $this->children->where('status', '1')->where('type', 'cr')->pluck('amount')->sum();
        return $cr - $db;
    }
}
