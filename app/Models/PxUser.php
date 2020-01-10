<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 15:10
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PxUser extends Model
{
    protected $table = "px_user";

    public function getFrontFields()
    {
        return [
            "id" => $this->id,
            "nickname" => $this->nickname,
            "headimg_url" => $this->headimg_url,
            "phone_number" => $this->phone_number,
        ];
    }
}