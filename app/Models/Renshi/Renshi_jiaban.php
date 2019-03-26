<?php

namespace App\Models\Renshi;

use Illuminate\Database\Eloquent\Model;

class Renshi_jiaban extends Model
{
	protected $fillable = [
        'uuid', 'uid_of_agent', 'agent', 'department_of_agent', 'uid_of_auditor', 'auditor', 'department_of_auditor', 'application', 'status', 'reason', 'remark', 'auditing',
    ];

}
