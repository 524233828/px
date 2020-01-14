<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-12
 * Time: 18:11
 */

namespace App\Models;


class UploadFile
{
    use Image;

    public function getImageUrlAttribute($value)
    {
        return $this->imageHandle($value);
    }
}