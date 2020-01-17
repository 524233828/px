<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-09
 * Time: 19:09
 */

namespace App\Api\Controllers;


use App\Models\Image;
use App\Models\PxUser;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;
use JoseChan\UserLogin\Libraries\Wechat\MiniProgram\Application;

/**
 * 用户相关
 * Class UserController
 * @package App\Api\Controllers
 */
class UserController extends Controller
{

    use Image;

    /**
     * 用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {
        /** @var PxUser $info */
        return $this->response(User::$info->getFrontFields());
    }

    /**
     * 用户邀请码
     * @return \Illuminate\Http\JsonResponse
     */
    public function code()
    {
        /** @var PxUser $user */
        $user = User::$info;

        //获取配置
        $config = config("user_login");

        $code = $user->getAttribute("code");

        $mini_program = new Application($config['mini_program']['app_id'], $config['mini_program']['app_secret']);

        $image = $mini_program->bindRedis(Redis::connection()->client())->getWxaCodeUnLimit("invited_{$code}");

        $file_name = "user/invited_code/{$user->id}.jpg";

        if (is_numeric($image) || Storage::disk("admin")->put($file_name, $image)) {
            return $this->response([], 6000, "生成小程序码失败");
        }

        return $this->response(["code" => $code, "code_image" => $this->imageHandle($file_name)]);

    }


}