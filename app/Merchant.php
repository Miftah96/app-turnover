<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = "merchants";
    protected $fillable = [
        "user_id",
        "merchant_name",
        "created_by",
        "updated_by",
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function outlets()
    {
        return $this->hasMany('App\Outlet', 'merchant_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction', 'merchant_id', 'id');
    }
}
