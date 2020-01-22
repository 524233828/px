<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 19:42
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "px_comment";
    protected $fillable = ["uid", "class_id", "comment", "star", "shop_id"];

    public function user(){
        return $this->belongsTo(PxUser::class, "uid", "id")
            ->select(["id","nickname","headimg_url"]);
    }
}