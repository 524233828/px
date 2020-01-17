<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-17
 * Time: 09:56
 */

namespace App\Libraries\PaymentExtensions\Provider;


use App\Libraries\PaymentExtensions\Gateway\Wechat\Transfers;
use Illuminate\Support\ServiceProvider;
use Runner\NezhaCashier\Cashier;

/**
 * 注册交易扩展
 * Class PayExtServiceProvider
 * @package App\Libraries\PaymentExtensions\Provider
 */
class PayExtServiceProvider extends ServiceProvider
{

    /**
     * boot
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //加载支付组件扩展
        Cashier::extend("wechat_transfer", Transfers::class);
    }

}