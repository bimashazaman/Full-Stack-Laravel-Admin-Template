<?php

namespace App\Models\Core\Frontend;

use App\Models\Core\BaseModel;

class TableFilter extends BaseModel
{
    protected $fillable = ['table_id', 'filter_name', 'filter_value', 'user_id'];

    public static function boot() : void
    {
        parent::boot();
        static::creating(function($model){
            return $model->fill([
                'user_id' => auth()->id()
            ]);
        });
    }
}
