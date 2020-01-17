<?php
/**
 * Created by PhpStorm.
 * User: lao
 * Date: 2020/1/17
 * Time: 10:27
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Collect
 * @package App\Models
 * @property integer $id ID
 * @property integer $uid 用户ID
 * @property integer $business_id 业务ID
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property integer $type 收藏分类，1-店铺 2-课程
 */
class Collect extends Model
{
    protected $table = "px_collect";

    protected $fillable = ["uid", "business_id", "type"];

}