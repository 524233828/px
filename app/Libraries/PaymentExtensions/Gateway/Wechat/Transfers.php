<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-17
 * Time: 09:46
 */

namespace App\Libraries\PaymentExtensions\Gateway\Wechat;


use Runner\NezhaCashier\Gateways\Wechat\AbstractWechatGateway;
use Runner\NezhaCashier\Requests\Charge;

class Transfers extends AbstractWechatGateway
{

    private const TRANSFERS_URL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
    /**
     * @param Charge $form
     *
     * @return array
     */
    protected function prepareCharge(Charge $form): array
    {
        $openId = $form->has('extras.open_id')
            ? $form->get('extras.open_id')
            : $this->getOpenId($form->get('extras.code'));

        return [
            'openid' => $openId,
        ];
    }

    protected function doCharge(array $response, Charge $form): array
    {
        $parameters = [
            'appId'     => $this->config->get('app_id'),
            'timeStamp' => time(),
            'nonceStr'  => uniqid(),
            'package'   => "prepay_id={$response['prepay_id']}",
            'signType'  => 'MD5',
        ];

        $parameters['paySign'] = $this->sign($parameters);

        return [
            'charge_url' => '',
            'parameters' => $parameters,
        ];
    }

    protected function getTradeType(): string
    {
        return 'JSAPI';
    }

    public function pay(array $form): array
    {
        $payload = [
            'mch_appid'  => $this->config->get('app_id'),
            'mchid' => $this->config->get('mch_id'),
            'nonce_str' => uniqid(),
            'partner_trade_no'     => $form['order_sn'],
            'openid'    => $form['openid'],
            'check_name'   => 'NO_CHECK',
            'amount'   => $form['amount'],
            'desc'   => $form['desc'],
            'spbill_create_ip'   => $form['ip'],
        ];

        $response = $this->request(
            self::TRANSFERS_URL,
            $payload,
            $this->config->get('cert'),
            $this->config->get('ssl_key')
        );

        return $response;
    }
}