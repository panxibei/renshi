<?php

namespace App\Models\Renshi;

use Illuminate\Database\Eloquent\Model;

class Renshi_employee extends Model
{
	protected $fillable = [
        'uid', 'applicant', 'department', 'category', 'start_date', 'end_date', 'duration',
    ];

}
