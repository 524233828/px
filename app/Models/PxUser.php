<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 15:10
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class PxUser
 * @package App\Models
 * @property integer $id ID
 * @property string $open_id openID
 * @property string $union_id unionId
 * @property integer $pid 父ID
 * @property string $nickname 昵称
 * @property string $headimg_url 头像地址
 * @property string $phone_number 手机号
 * @property integer $code 邀请码，暂时无用
 * @property string $created_at ID 创建时间
 * @property string $updated_at ID 更新时间
 * @property string $parent ID 更新时间
 *
 */
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
            "pid" => $this->pid,
        ];
    }

    public function getCodeAttribute()
    {
        return str_pad($this->id, 8, "0", STR_PAD_LEFT);
    }

    public function parent()
    {
        return $this->belongsTo(PxUser::class, "pid", "id");
    }
}