<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncomeSource extends Model
{
    protected $fillable =
    [
       'name',
    ];

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }
}
