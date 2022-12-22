<?php

namespace App\Http\Controllers\App\CustomTable;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomTable\DataTableCustomTableRequest;
use App\Models\Core\Builder\Table\CustomTable;
use App\Models\Core\Builder\Table\CustomTableRow;

class DataTableCustomTableController extends Controller
{
    public function getTableValues(CustomTable $customTable)
    {
        return $customTable->rows()->with('fieldValues')->paginate();
    }

    public function storeTableValues(DataTableCustomTableRequest $request)
    {
        throw_if(!\request()->filled('table_id'),
            new GeneralException(__t('action_not_allowed'))
        );
        $table = CustomTable::query()->findOrFail($request->table_id);
        $row = $table->rows()->create();
        collect($request->fields)->each(function ($field) use ($row) {
            $row->fieldValues()->create($field);
        });
        return created_responses('table_data');
    }

    public function getTableRow(CustomTableRow $customTableRow)
    {
        return $customTableRow->load('fieldValues');
    }

    public function updateTableRow(CustomTableRow $customTableRow, DataTableCustomTableRequest $request)
    {
        collect($request->fields)->each(function ($field) use ($customTableRow) {
            $customTableRow->fieldValues()
                ->updateOrCreate([
                    'custom_field_id' => $field['custom_field_id']
                ],[
                    'value' => isset($field['value']) ? $field['value'] : null
                ]);
        });
        return updated_responses('table_data');
    }

    public function deleteTableRow(CustomTableRow $customTableRow)
    {
        $customTableRow->fieldValues()->delete();
        $customTableRow->delete();
        return deleted_responses('table_data');
    }
}
