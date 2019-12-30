<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-12-16
 * Time: 17:43
 */

namespace JoseChan\App\Api\Logic;


use JoseChan\App\DataSet\Models\App;
use JoseChan\Base\Api\Logic\Logic;
use JoseChan\Signature\SignAdaptor;

class SignLogic extends Logic
{

    /**
     * 验签
     * @param $app_id
     * @param $message
     * @param $sign
     * @param $sign_type
     * @return array
     */
    public function verify($app_id, $message, $sign, $sign_type)
    {
        $app_model = new App();

        /**@var App|\Illuminate\Database\Eloquent\Collection|static[]|static|null $app */
        $app = $app_model->find($app_id);

        if (!$app || !$app->exists) {
            return ["result" => 0];
        }

        if ($sign_type == SignAdaptor::RSA) {
            $key = $app->private_key;
        } else {
            $key = $app->getToken();
        }

        $result = SignAdaptor::getInstance()->verify($message, $sign, $key, $sign_type);

        return ["result" => $result ? 1 : 0];
    }

}