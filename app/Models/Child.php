<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-10
 * Time: 16:59
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Child
 * @package App\Models
 * @property integer $id ID
 * @property integer $uid 用户ID
 * @property string $name 姓名
 * @property string $tel 联系方式
 * @property string $birth 生日
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property integer $gender 性别1-男2-女
 */
class Child extends Model
{

    protected $table = "px_child";

    protected $fillable = ["uid", "name", "tel", "birth", "gender"];
}