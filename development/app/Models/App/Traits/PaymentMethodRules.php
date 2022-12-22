<?php


namespace App\Models\App\Traits;


trait PaymentMethodRules
{
    public function createdRules(): array
    {
        $rules['name'] = 'required';
        $rules['type'] = 'required';
        $rules['is_default'] = 'required';
        if (\request()->get('type') === 'paypal') {
            $rules['client_id'] = 'required';
            $rules['secret_key'] = 'required';
        } elseif (\request()->get('type') === 'stripe') {
            $rules['public_key'] = 'required';
            $rules['secret_key'] = 'required';
        }
        return $rules;
    }

    public function updatedRules(): array
    {
        return $this->createdRules();
    }
}