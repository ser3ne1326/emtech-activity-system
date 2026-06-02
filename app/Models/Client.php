<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'business_type',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}