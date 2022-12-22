<?php


namespace App\Services\App\Paypal;


use App\Models\Core\Setting\Setting;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\Config;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PaypalService extends BaseService
{
    public $apiContext;
    public $setting;

    public function __construct()
    {
        $this->settings = resolve(SettingRepository::class)
            ->formatSettings(
                Setting::query()->where('context', 'paypal')
                    ->get(), true
            );

        if ($this->settings) {
            Config::set('services.paypal.client_id', $this->settings['client_id']);
            Config::set('services.paypal.secret', $this->settings['secret_key']);
        }

        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
        );

    }

    /**
     * @return Payer
     */
    public function payer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        return $payer;
    }

    /**
     * @return Details
     */
    public function details(): Details
    {
        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);
        return $details;
    }

    /**
     * @param $details
     * @return Amount
     */

    public function amount($details): Amount
    {
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);
        return $amount;
    }

    /**
     * @param Amount $amount
     * @param ItemList $itemList
     * @return Transaction
     */
    public function transaction(Amount $amount, ItemList $itemList): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());
        return $transaction;
    }

    /**
     * @return RedirectUrls
     */
    public function redirectUrls(): RedirectUrls
    {
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(config('app.url') . '/execute-payment')
            ->setCancelUrl(config('app.url') . '/cancel-payment');
        return $redirectUrls;
    }

    /**
     * @param Payer $payer
     * @param RedirectUrls $redirectUrls
     * @param Transaction $transaction
     * @return Payment
     */
    public function payment(Payer $payer, RedirectUrls $redirectUrls, Transaction $transaction): Payment
    {
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        return $payment;
    }

    /**
     * @return Payment
     */
    public function getThePayment(): Payment
    {
        $paymentId = \request('paymentId');
        $payment = Payment::get($paymentId, $this->apiContext);
        return $payment;
    }

    /**
     * @return PaymentExecution
     */
    public function createExtracted(): PaymentExecution
    {
        $execution = new PaymentExecution();
        $execution->setPayerId(\request('PayerID'));
        return $execution;
    }

    public function paypalStatus()
    {
        return ['isSetPaypal' => $this->settings ? true : false];
    }


}