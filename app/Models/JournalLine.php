<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalLine extends Model
{
    use HasUuids, HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'journal_id',
        'journal_line_id',
        'account_id',
        'account_code',
        'account_type',
        'account_name',
        'description',
        'net_amount',
        'gross_amount',
        'tax_amount',
        'tax_type',
        'tax_name',
        'tracking_categories',
    ];

    protected $casts = [
        'tracking_categories' => 'array',
    ];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }
}

