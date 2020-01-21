<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-21
 * Time: 16:03
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ChinaArea
 * @package App\Models
 * @property integer $id ID
 * @property integer $parent_id 父类ID
 * @property string $code 地区码
 * @property string $name 名字
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ChinaArea extends Model
{
    protected $table = "china_area";

    public function getChildren()
    {

        return ChinaArea::query()->where("parent_id", "=", $this->id)->get();
    }

}