<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = "px_wallet";

    protected $fillable = ["uid", "amount", "freeze_amount"];

}