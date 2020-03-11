<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-11
 * Time: 09:17
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CardOrderChild extends Model
{

    protected $table = "px_card_order_child";

    protected $fillable = ["card_order_id", "child_name", "child_tel", "child_birth", "child_gender"];

    public $timestamps = false;

    public function cardOrder()
    {
        return $this->belongsTo(CardOrder::class, "card_order_id", "id");
    }

    public static function buildCardOrderChild($data_list)
    {
        $models = [];

        if(!empty($data_list)){
            foreach ($data_list as $item){
                $models[] = new self($item);
            }
        }

        return $models;
    }

    public function setAge()
    {
        $now = new Carbon("now");
        $child_birth = new Carbon($this->child_birth);
        $this->setAttribute("age", $now->year - $child_birth->year);

        return $this;
    }

}
