<?php


namespace App\Http\Controllers\App\PaymentMethod;


use App\Http\Controllers\Controller;
use App\Services\App\Paypal\PaypalService;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;

class PaypalController extends Controller
{
    public function __construct(PaypalService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        $payer = $this->service->payer();

        list($item1, $item2) = $this->item();

        $itemList = new ItemList();
        $itemList->setItems(array($item1, $item2));

        $details = $this->service->details();

        $amount = $this->service->amount($details);

        $transaction = $this->service->transaction($amount, $itemList);

        $redirectUrls = $this->service->redirectUrls();

        $payment = $this->service->payment($payer, $redirectUrls, $transaction);

        $payment->create($this->service->apiContext);

        return redirect($payment->getApprovalLink());


    }

    public function execute()
    {

        $payment = $this->service->getThePayment();

        $execution = $this->service->createExtracted();

        $transaction = new Transaction();

        $details = $this->service->details();

        $amount = $this->service->amount($details);

        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);
        $result = $payment->execute($execution, $this->service->apiContext);
        return redirect(config('app.url') . '/payment-view')->with('message', 'Payment successfully');


    }

    public function cancel()
    {
        return redirect(config('app.url') . '/payment-view');
    }

    /**
     * @return Item[]
     */
    public function item(): array
    {
        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku("123123") // Similar to `item_number` in Classic API
            ->setPrice(7.5);

        $item2 = new Item();
        $item2->setName('Granola bars')
            ->setCurrency('USD')
            ->setQuantity(5)
            ->setSku("321321") // Similar to `item_number` in Classic API
            ->setPrice(2);
        return array($item1, $item2);
    }

    public function paypalStatus()
    {
        return $this->service->paypalStatus();
    }


}