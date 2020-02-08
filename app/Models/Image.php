<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-11
 * Time: 17:54
 */

namespace App\Models;


trait Image
{

    public function imageHandle($path)
    {
        $url = config("app.resource_url");

        return (!empty($path)) ? $url . DIRECTORY_SEPARATOR .$path : "";
    }

}