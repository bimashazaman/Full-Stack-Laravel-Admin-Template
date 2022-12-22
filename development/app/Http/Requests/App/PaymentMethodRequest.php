<?php


namespace App\Http\Requests\App;


use App\Models\App\PaymentMethods\PaymentMethod;

class PaymentMethodRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules(new PaymentMethod());
    }

}