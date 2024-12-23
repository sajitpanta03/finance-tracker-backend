<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class Income extends Model
{
    protected $fillable =
    [
        'amount',
        'date_received',
        'description',
        'user_id',
        'income_sources_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function incomeSource(): BelongsTo
    {
        return $this->belongsTo(IncomeSource::class, 'income_sources_id', 'id');
    }

}
