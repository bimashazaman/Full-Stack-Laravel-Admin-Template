<?php

namespace App\Http\Requests\Core\Builder\Table;

use App\Http\Requests\BaseRequest;
use App\Models\Core\Builder\Table\CustomTable;

class CustomTableRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws \App\Exceptions\GeneralException
     */
    public function rules()
    {
        return $this->initRules(new CustomTable());
    }
}
