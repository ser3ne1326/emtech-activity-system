<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityAttachment extends Model
{
    protected $fillable = [
        'employee_activity_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    public function activity()
    {
        return $this->belongsTo(EmployeeActivity::class, 'employee_activity_id');
    }
}