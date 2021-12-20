<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;
    protected $table = "merchants";
    protected $fillable = [
        "user_id",
        "merchant_name",
        "created_by",
        "updated_by",
    ];

}
