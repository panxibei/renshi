<?php

namespace App\Models\Renshi;

use Illuminate\Database\Eloquent\Model;

class Renshi_jiaban extends Model
{
	protected $fillable = [
        'uuid', 'agent', 'department_of_agent','applicant', 'department_of_applicant', 'category', 'start_date', 'end_date', 'duration', 'status', 'reason', 'remark',
    ];

}
