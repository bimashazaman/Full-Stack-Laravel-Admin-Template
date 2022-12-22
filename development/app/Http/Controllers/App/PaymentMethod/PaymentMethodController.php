<?php

namespace App\Http\Controllers\App\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\PaymentMethodRequest;
use App\Models\App\PaymentMethods\PaymentMethod;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\App\PaymentMethod\PaymentMethodService;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function __construct(PaymentMethodService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->latest()
            ->with(['status'])
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(PaymentMethodRequest $request)
    {
        $paymentMethod = DB::transaction(function () use ($request) {

            $status = resolve(StatusRepository::class)->payment_methodActive();

            $attributes = array_merge($request->only('name', 'type', 'is_default', 'rounded_to'), [
                'status_id' => $status,
                'alias' => $request->name,
                'created_by' => auth()->id(),
            ]);

            $paymentMethod = $this->service
                ->setAttributes($attributes)
                ->save();

            $this->service
                ->setModel($paymentMethod)
                ->setAttributes($request->only('public_key', 'client_id', 'secret_key', 'mode'))
                ->saveSettings($request->type);

            return $paymentMethod;
        });

        return created_responses('payment_method', ['payment_method' => $paymentMethod]);

    }


    public function show(PaymentMethod $paymentMethod)
    {
        return $this->service
            ->getDataWithFormattedSetting($paymentMethod);
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        DB::transaction(function () use ($request, $paymentMethod) {
            $this->service
                ->setModel($paymentMethod)
                ->save($request->only('name', 'type', 'is_default', 'status_id'));

            $this->service
                ->setModel($paymentMethod)
                ->setAttributes($request->only('public_key', 'client_id', 'secret_key', 'mode'))
                ->updateSettings();
        });

        return updated_responses('payment_method', ['payment_method' => $paymentMethod]);

    }


    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->settings()->delete();
        $paymentMethod->delete();

        return deleted_responses('payment_method');
    }

    public function paymentMethodStatus()
    {
        return $this->service
            ->getPaymentMethodStatus();
    }
}
