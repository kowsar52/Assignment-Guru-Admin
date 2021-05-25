<?php
namespace App\Services\Stripe;
use App\Models\PaymentGateways;

use GuzzleHttp\Client;

class Seller
{
    /**
     * Create express account via Stripe OAuth
     *
     * @param $code
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function create($code)
    {
        $payment = PaymentGateways::where('slug','stripe')->firstOrFail();
        $client = new Client(['base_uri' => 'https://connect.stripe.com/oauth/']);
        $request = $client->request("POST", "token", [
            'form_params' => [
                'client_secret' => $payment->key_secret,
                'code' => $code,
                'grant_type' => 'authorization_code'
            ]
        ]);
        return json_decode($request->getBody()->getContents());
    }
}