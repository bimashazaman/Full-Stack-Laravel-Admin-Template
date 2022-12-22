<?php

namespace App\Models\App\Crud;

use App\Models\App\AppModel;
use App\Models\App\Traits\CrudValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Crud extends AppModel
{
    use CrudValidationRules, HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'gender', 'age', 'status'];
}
