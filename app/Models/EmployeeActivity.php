<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeActivity extends Model
{
    protected $fillable = [
        'project_id',
        'project_task_id',
        'user_id',
        'activity_type',
        'description',
        'activity_date',
        'time_started',
        'time_ended',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(ActivityAttachment::class);
    }
}