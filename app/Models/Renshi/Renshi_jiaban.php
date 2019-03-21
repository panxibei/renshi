<?php

namespace App\Models\Renshi;

use Illuminate\Database\Eloquent\Model;

class Renshi_jiaban extends Model
{
	protected $fillable = [
        'uuid', 'agent', 'department_of_agent','application', 'status', 'reason', 'remark', 'auditing',
    ];

}
