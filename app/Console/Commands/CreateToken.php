<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-05-17
 * Time: 16:06
 */

namespace App\Console\Commands;

use Firebase\JWT\JWT;
use Illuminate\Console\Command;
use JoseChan\UserLogin\Constant\JWTKey;

class CreateToken extends Command
{
    protected $signature = 'user:token {--uid=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成token脚本';

    public function handle()
    {
        $uid = isset($this->option("uid")[0]) ? $this->option("uid")[0] : 0;

        $config = JWTKey::getConfigs();
        $token = [
            'iss' => $config['iss'],
            'aud' => (string)$uid,
            'iat' => time(),
            'exp' => time() + $config['expired'], // 有效期
            'data' => []
        ];

        $this->output->write(JWT::encode($token, $config['key'], $config['alg']) . "\n");
    }
}
