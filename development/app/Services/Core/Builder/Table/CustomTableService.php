<?php


namespace App\Services\Core\Builder\Table;


use App\Models\Core\Builder\Table\CustomTable;
use App\Services\Core\BaseService;

class CustomTableService extends BaseService
{
    public function __construct(CustomTable $table)
    {
        $this->model = $table;
    }

}
