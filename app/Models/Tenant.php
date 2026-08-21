<?php

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function getNameAttribute()
    {
        // Prefer explicit `name` column if present, otherwise fall back to `data.name`
        if (array_key_exists('name', $this->attributes) && ! is_null($this->attributes['name'])) {
            return $this->attributes['name'];
        }

        return data_get($this->data, 'name');
    }

    public function setNameAttribute($value)
    {
        // Write both to the `name` attribute (if the column exists) and into the JSON `data`
        $this->attributes['name'] = $value;

        $data = $this->data ?? [];
        data_set($data, 'name', $value);
        $this->attributes['data'] = json_encode($data);
    }
}
