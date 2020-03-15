<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-15
 * Time: 17:05
 */

namespace App\Api\Controllers;


use App\Models\IndexEntry;
use JoseChan\Base\Api\Controllers\Controller;

class IndexEntryController extends Controller
{

    public function fetch()
    {
        $index_entry = IndexEntry::query()->orderBy("sort", "desc")->get();

        return $this->response(["list" => $index_entry]);
    }

}
