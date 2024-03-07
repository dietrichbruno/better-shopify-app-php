<?php

namespace App\Http\Controllers;

use App\Lib\AuthRedirection;
use App\Lib\EnsureBilling;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\OAuth;
use Shopify\Context;
use Shopify\Utils;
use Shopify\Webhooks\Registry;
use Shopify\Webhooks\Topics;

class AuthController extends Controller
{
    public function fallback(Request $request)
    {
        Log::debug('fallback route');
        if (Context::$IS_EMBEDDED_APP &&  $request->query("embedded", false) === "1") {
            if (env('APP_ENV') === 'production') {
                return file_get_contents(public_path('index.html'));
            } else {
                return file_get_contents(base_path('frontend/index.html'));
            }
        } else {
            return redirect(Utils::getEmbeddedAppUrl($request->query("host", null)) . "/" . $request->path());
        }
    }

    public function auth(Request $request)
    {
        Log::debug('auth route');
        $shop = Utils::sanitizeShopDomain($request->query('shop'));

        // Delete any previously created OAuth sessions that were not completed (don't have an access token)
        Session::where('shop', $shop)->where('access_token', null)->delete();

        return AuthRedirection::redirect($request);
    }

    public function authCallback(Request $request)
    {
        Log::debug('auth callback route');
        $session = OAuth::callback(
            $request->cookie(),
            $request->query(),
            ['App\Lib\CookieHandler', 'saveShopifyCookie'],
        );

        $host = $request->query('host');
        $shop = Utils::sanitizeShopDomain($request->query('shop'));

        $response = Registry::register('/api/webhooks', Topics::APP_UNINSTALLED, $shop, $session->getAccessToken());
        if ($response->isSuccess()) {
            Log::debug("Registered APP_UNINSTALLED webhook for shop $shop");
        } else {
            Log::error(
                "Failed to register APP_UNINSTALLED webhook for shop $shop with response body: " .
                    print_r($response->getBody(), true)
            );
        }

        $redirectUrl = Utils::getEmbeddedAppUrl($host);
        if (Config::get('shopify.billing.required')) {
            list($hasPayment, $confirmationUrl) = EnsureBilling::check($session, Config::get('shopify.billing'));

            if (!$hasPayment) {
                $redirectUrl = $confirmationUrl;
            }
        }

        return redirect($redirectUrl);
    }
}
