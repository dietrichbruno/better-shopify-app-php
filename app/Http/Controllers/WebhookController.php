<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\HttpHeaders;
use Shopify\Clients\Rest;
use Shopify\Exception\InvalidWebhookException;
use Shopify\Webhooks\Registry;
use Shopify\Webhooks\Topics;

class WebhookController extends Controller
{
    public function listWebhooks(Request $request)
    {
        $session = $request->get('shopifySession');

        $client = new Rest($session->getShop(), $session->getAccessToken());
        $result = $client->get('webhooks.json');
        
        return response($result->getDecodedBody());
    }

    public function webhooks(Request $request)
    {
        try {
            $topic = $request->header(HttpHeaders::X_SHOPIFY_TOPIC, '');
    
            $response = Registry::process($request->header(), $request->getContent());
            if (!$response->isSuccess()) {
                Log::error("Failed to process '$topic' webhook: {$response->getErrorMessage()}");
                return response()->json(['message' => "Failed to process '$topic' webhook"], 500);
            }
        } catch (InvalidWebhookException $e) {
            Log::error("Got invalid webhook request for topic '$topic': {$e->getMessage()}");
            return response()->json(['message' => "Got invalid webhook request for topic '$topic'"], 401);
        } catch (\Exception $e) {
            Log::error("Got an exception when handling '$topic' webhook: {$e->getMessage()}");
            return response()->json(['message' => "Got an exception when handling '$topic' webhook"], 500);
        }
    }

    public function subscribe(Request $request)
    {
        $session = $request->get('shopifySession');
        $shop = $session->getShop();

        $response = Registry::register(
            'https://webhook.site/57f2e3a4-09a7-460c-9c6e-086e2d9583b9', 
            Topics::PRODUCTS_CREATE, $shop, $session->getAccessToken()
        );

        if ($response->isSuccess()) {
            Log::debug("Registered PRODUCTS_CREATE webhook for shop $shop");
        } else {
            Log::error(
                "Failed to register PRODUCTS_CREATE webhook for shop $shop with response body: " .
                    print_r($response->getBody(), true)
            );
        }
    }

    public function notifications(Request $request)
    {
        Log::debug('webhook notificartion', [$request->all()]);
    }
}
