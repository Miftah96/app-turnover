<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;
    protected $table = "outlets";
    protected $fillable = [
        "merchant_id",
        "outlet_name",
        "created_by",
        "updated_by",
    ];
}
