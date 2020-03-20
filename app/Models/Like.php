<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-20
 * Time: 09:57
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    protected $table = "px_like";

    protected $fillable = ["uid", "business_id","type"];

    public const TYPE_SHOP = 1;
    public const TYPE_CLASS = 2;
    public const TYPE_SPECIAL_CLASS = 3;

    public static $business_model = [
        self::TYPE_SHOP => Shop::class,
        self::TYPE_CLASS => Classes::class,
        self::TYPE_SPECIAL_CLASS => SpecialClass::class,
    ];

}
