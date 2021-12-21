<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $table = "outlets";
    protected $fillable = [
        "merchant_id",
        "outlet_name",
        "created_by",
        "updated_by",
    ];

    public function transaction()
    {
        return $this->hasMany('App\Transaction');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }
}
