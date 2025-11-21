<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    use HasUuids, HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'api_key',
        'name',
        'legal_name',
        'pays_tax',
        'version',
        'organisation_type',
        'base_currency',
        'country_code',
        'is_demo_company',
        'organisation_status',
        'registration_number',
        'employer_identification_number',
        'tax_number',
        'financial_year_end_day',
        'financial_year_end_month',
        'sales_tax_basis',
        'sales_tax_period',
        'default_sales_tax',
        'default_purchases_tax',
        'period_lock_date',
        'end_of_year_lock_date',
        'created_date_utc',
        'timezone',
        'organisation_entity_type',
        'short_code',
        'class',
        'edition',
        'line_of_business',
        'external_links',
        'payment_terms',
        'last_sync',
    ];

    protected $casts = [
        'is_demo_company' => 'boolean',
        'external_links' => 'array',
        'payment_terms' => 'array',
        'last_sync' => 'datetime',
    ];

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(XeroLog::class);
    }
}

