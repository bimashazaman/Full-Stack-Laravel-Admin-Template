<?php

namespace App\Models\Core\Builder\Table;

use App\Models\Core\Builder\Form\CustomFieldValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CustomTableRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_table_id',
    ];
    public function customTable(): BelongsTo
    {
        return $this->belongsTo(CustomTable::class);
    }

    public function fieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'contextable');
    }
}
