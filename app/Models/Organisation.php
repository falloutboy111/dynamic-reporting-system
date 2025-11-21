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
        'name',
        'database_host',
        'database_name',
        'database_username',
        'database_password',
        'database_port',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'database_port' => 'integer',
    ];

    protected $hidden = [
        'database_username',
        'database_password',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Mutators for encryption
    public function setDatabaseUsernameAttribute($value): void
    {
        $this->attributes['database_username'] = $value ? encrypt($value) : null;
    }

    public function getDatabaseUsernameAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }

    public function setDatabasePasswordAttribute($value): void
    {
        $this->attributes['database_password'] = $value ? encrypt($value) : null;
    }

    public function getDatabasePasswordAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }
}

