<?php

namespace App\Http\Controllers\Core\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Core\Frontend\TableFilter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TableFilterController extends Controller
{
    public function index(Request $request)
    {
        return TableFilter::where([
            ['table_id' , '=', $request['table_id']],
            ['user_id' , '=', auth()->id()]
        ])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'filter_name' => ['required','string',
                Rule::unique('table_filters')->where(function ($query) {
                    return $query->where('table_id', \request('table_id'))
                        ->where('filter_name', \request('filter_name'));
                })],
            'table_id' => ['required','string']
        ]);

        TableFilter::query()->create($request->all());
        return created_responses('filters');
    }
    public function destroy(TableFilter $tableFilter) {
        $tableFilter->delete();
        return deleted_responses('filters');
    }
}
