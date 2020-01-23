<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-23
 * Time: 15:22
 */

namespace App\Api\Controllers;


use App\Models\Bill;
use App\Models\PxUser;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;
use JoseChan\UserLogin\Constants\User;

class BillController extends Controller
{

    public function fetch(Request $request)
    {

        /** @var PxUser $uesr */
        $uesr = User::$info;

        $page = $request->get("page", 1);
        $size = $request->get("size", 20);

        $pager = new Pager($page, $size);

        $count = Bill::query()->where("uid", "=", $uesr->id)->count();

        //流水信息
        $bill = Bill::query()->where("uid", "=", $uesr->id)
            ->offset($pager->getFirstIndex())
            ->limit($size)
            ->get();

        return $this->response(["list" => $bill, "meta" => $pager->getPager($count)]);


    }

}