<?php

namespace App\Http\Requests\CustomTable;

use Illuminate\Foundation\Http\FormRequest;

class DataTableCustomTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fields.*.value' => 'required_if:fields.*.required,1',
        ];
    }

}
