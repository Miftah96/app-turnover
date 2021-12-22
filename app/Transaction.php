<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transactions";
    protected $fillable = [
        "merchant_id",
        "outlet_id",
        "bill_total",
        "created_by",
        "updated_by",
    ];

    public function merchant()
    {
        return $this->belongsTo('App\Merchant')->with('user');
    }

    public function outlet()
    {
        return $this->belongsTo('App\Outlet');
    }
}
