<?php

namespace App\Models\Renshi;

use Illuminate\Database\Eloquent\Model;

class Renshi_jiaban extends Model
{
	protected $fillable = [
        'uuid', 'agent', 'department_of_agent', 'auditor', 'department_of_auditor', 'application', 'status', 'reason', 'remark', 'auditing',
    ];

}
