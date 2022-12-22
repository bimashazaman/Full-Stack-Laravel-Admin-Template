<?php

namespace App\Models\Core\Builder\Table;

use App\Models\Core\BaseModel;
use App\Models\Core\Builder\Form\CustomField;
use App\Models\Core\Builder\Form\CustomFieldValue;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class CustomTable extends BaseModel
{
    use CreatedByRelationship, BootTrait;

    protected $fillable = [
        'name', 'context', 'created_by',
    ];

    public function  fields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(CustomTableRow::class);
    }

    public function createdRules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'context' => 'required',
            'customFields.*.name' => 'required',
            'customFields.*.context' => 'required',
            'customFields.*.meta' => 'required_if:customFields.*.custom_field_type_name,select,radio',
        ];
    }
}
