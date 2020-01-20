<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-20
 * Time: 14:02
 */

namespace App\Collections;


use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;

class VideoCollection extends Collection
{

    public function business()
    {
        $this->map(function (Video $item, $key){
            $item->setBusiness();
        });
    }
}