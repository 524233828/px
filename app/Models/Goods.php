<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Goods
 * @package App\Models
 * @property int $id ID
 * @property string $name ID
 * @property string $img_url ID
 * @property float $price ID
 * @property string $created_at ID
 * @property string $updated_at ID
 * @property string $intro ID
 * @property string $desc ID
 */
class Goods extends Model
{
    protected $table = "px_goods";

}