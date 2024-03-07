<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Shopify\Clients\Rest;

class CheckoutController extends Controller
{
    public function checkouts(Request $request)
    {
        $session = $request->get('shopifySession');

        $client = new Rest($session->getShop(), $session->getAccessToken());
        $result = $client->get('checkouts.json');

        return response($result->getDecodedBody());
    }
}
