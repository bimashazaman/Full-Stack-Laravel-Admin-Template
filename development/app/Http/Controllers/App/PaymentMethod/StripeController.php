<?php


namespace App\Http\Controllers\App\PaymentMethod;

use App\Models\Core\Setting\Setting;
use App\Repositories\Core\Setting\SettingRepository;
use Illuminate\Http\Request;
use Stripe;


use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    private $settings;

    public function __construct()
    {
        $this->settings = resolve(SettingRepository::class)
            ->formatSettings(
                Setting::query()->where('context', 'stripe')
                    ->get(), true
            );
    }

    public function stripe()
    {
        $public_key = $this->settings ? $this->settings['public_key'] : '';
        return view('sample-pages.stripe-payment', with(['public_key' => $public_key]));
    }


    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey($this->settings['secret_key']);
        Stripe\Charge::create([
            "amount" => 100 * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Test payment"
        ]);

        return redirect()->back()->with('message', 'Payment Successful!');
    }

    public function stripeStatus()
    {
        return ['isSetStripe' => $this->settings ? true : false];
    }
}