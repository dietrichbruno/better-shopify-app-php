<?php

namespace App\Http\Controllers;

use App\Exceptions\ShopifyProductCreatorException;
use App\Lib\ProductCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Rest;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        // Provided by the shopify.auth middleware, guaranteed to be active
        $session = $request->get('shopifySession'); 

        $success = $code = $error = null;
        try {
            ProductCreator::call($session, 5);
            $success = true;
            $code = 200;
            $error = null;
        } catch (\Exception $e) {
            $success = false;

            if ($e instanceof ShopifyProductCreatorException) {
                $code = $e->response->getStatusCode();
                $error = $e->response->getDecodedBody();
                if (array_key_exists("errors", $error)) {
                    $error = $error["errors"];
                }
            } else {
                $code = 500;
                $error = $e->getMessage();
            }

            Log::error("Failed to create products: $error");
        } finally {
            return response()->json(["success" => $success, "error" => $error], $code);
        }
    }

    public function products(Request $request)
    {
        $session = $request->get('shopifySession');

        $client = new Rest($session->getShop(), $session->getAccessToken());
        $result = $client->get('products.json');

        return response($result->getDecodedBody());
    }

    public function count(Request $request)
    {
        $session = $request->get('shopifySession');

        $client = new Rest($session->getShop(), $session->getAccessToken());
        $result = $client->get('products/count');

        return response($result->getDecodedBody());
    }
}
