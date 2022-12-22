<?php

namespace App\Http\Controllers\Core\Builder\Table;

use App\Exceptions\GeneralException;
use App\Filters\Core\CustomFieldFilter;
use App\Hooks\Table\BeforeCustomTableSaved;
use App\Hooks\Table\CustomTableCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Builder\Table\CustomTableRequest;
use App\Models\Core\Builder\Table\CustomTable;
use App\Services\Core\Builder\Table\CustomTableService;

class CustomTableController extends Controller
{
    public $hook;

    public function __construct(CustomTableService $service, CustomFieldFilter $filter, CustomTableCreated $hook)
    {
        $this->service = $service;
        $this->filter = $filter;
        $this->hook = $hook;
    }

    public function index()
    {
        return
            $this->service
                ->with([
                    'fields',
                    'fields.customFieldType:id,name',
                    'createdBy:id,first_name,last_name'
                ])
                ->orderBy('id', 'DESC')
                ->filters($this->filter)
                ->paginate(request('per_page', 15));
    }

    public function store(CustomTableRequest $request)
    {
        BeforeCustomTableSaved::new(true)
            ->handle();

        $table = $this
            ->service
            ->save(
                $request->only('name', 'context')
            );

        if (count($request->customFields)) {
            $table->fields()->createMany($request->customFields);
        };

        $this->hook
            ->setModel($table)
            ->handle();

        return created_responses('custom_table');
    }

    public function show(CustomTable $customTable)
    {
        return $customTable->load('fields');
    }

    public function update(CustomTable $customTable, CustomTableRequest $request)
    {
        BeforeCustomTableSaved::new(true)
            ->handle();

        throw_if($customTable->rows()->exists(),
            new GeneralException(__t('can_not_update_non_empty_datatable'))
        );

        $customTable
            ->update(
                $request->only('name', 'context')
            );

        $customTable->fields()->delete();
        $customTable->fields()->createMany($request->customFields);

        $this->hook
            ->setModel($customTable)
            ->handle();

        return updated_responses('custom_table');
    }

    public function destroy(CustomTable $customTable)
    {
        $customTable->rows->each(fn($row) => $row->fieldValues()->delete());
        $customTable->rows()->delete();
        $customTable->fields()->delete();
        $customTable->delete();

        return deleted_responses('custom_table');
    }

    public function viewTable(CustomTable $customTable)
    {
        $customTable->load([
            'fields',
            'fields.customFieldType',
        ]);
        return view('custom-table.custom_table', compact('customTable'));
    }
}