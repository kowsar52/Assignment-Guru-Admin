<?php
namespace App\Services\Stripe;


use App\Models\User;
use App\Models\PaymentGateways;
use App\Models\UserPaymentMethod;
use Stripe\Stripe;
use Stripe\Token;
use Carbon\Carbon;

class Customer
{
    public static function save(User $user, array $card)
    {
        $payment = PaymentGateways::where('slug','stripe')->firstOrFail();
        Stripe::setApiKey($payment->key_secret);

        $token = Token::create($card);
        $customer = \Stripe\Customer::create([
            'source' => $token->id,
            'email' => $user->email
        ]);

        $account = \Stripe\Account::create([
            'country' => 'US',
            'type' => 'express',
          ]);
          
        UserPaymentMethod::insert([
            'user_id' => $user->id,
            'payment_method_id' => 2,
            'stripe_customer_id' => $customer->id,
            'stripe_connect_id' => $account->id,
            'created_at' => Carbon::now(),
        ]);
    }
}