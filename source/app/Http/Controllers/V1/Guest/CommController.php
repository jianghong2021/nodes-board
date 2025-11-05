<?php

namespace App\Http\Controllers\V1\Guest;

use App\Http\Controllers\Controller;
use App\Services\Plugin\HookManager;
use App\Utils\Dict;
use App\Utils\Helper;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CommController extends Controller
{
    public function config()
    {
        $data = [
            'tos_url' => admin_setting('tos_url'),
            'is_email_verify' => (int) admin_setting('email_verify', 0) ? 1 : 0,
            'is_invite_force' => (int) admin_setting('invite_force', 0) ? 1 : 0,
            'email_whitelist_suffix' => (int) admin_setting('email_whitelist_enable', 0)
                ? Helper::getEmailSuffix()
                : 0,
            'is_captcha' => (int) admin_setting('captcha_enable', 0) ? 1 : 0,
            'captcha_type' => admin_setting('captcha_type', 'recaptcha'),
            'recaptcha_site_key' => admin_setting('recaptcha_site_key'),
            'recaptcha_v3_site_key' => admin_setting('recaptcha_v3_site_key'),
            'recaptcha_v3_score_threshold' => admin_setting('recaptcha_v3_score_threshold', 0.5),
            'turnstile_site_key' => admin_setting('turnstile_site_key'),
            'app_description' => admin_setting('app_description'),
            'app_url' => admin_setting('app_url'),
            'logo' => admin_setting('logo'),
            // 保持向后兼容
            'is_recaptcha' => (int) admin_setting('captcha_enable', 0) ? 1 : 0,
        ];

        $data = HookManager::filter('guest_comm_config', $data);

        return $this->success($data);
    }

    public function appConfig(Request $request)
    {

        // $d = config();
        
        $data = [
            "message"=>'ok',
            "code"=>1,
            "baseURL" => config('app.url')."/api/v1/",
            "baseDYURL" => config('app.url')."/api/v1/client/subscribe?token=2704165367b5aaef3eec9d6f6058292c",
            "mainregisterURL" => config('app.url')."/#/register?code=",
            "paymentURL" => "xxxxx",
            "telegramurl" => "https://t.me/fastlink",
            "kefuurl" => "https://gooapis.com/fastlink/",
            "websiteURL" => config('app.url'),
            "crisptoken" => "5546c6ea-4b1e-41bc-80e4-4b6648cbca76",
            "banners" => [
                "https://doc.scriptbox.space/imgs/code-tpl.jpg",
                "https://doc.scriptbox.space/imgs/code-text.jpg"
            ],
        ];

        return response($data);
    }
}
