<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'project_number',
        'title',
        'service_type',
        'description',
        'start_date',
        'due_date',
        'priority',
        'status',
        'created_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function activities()
    {
        return $this->hasMany(EmployeeActivity::class);
    }
}