<?php

namespace App\Models\Renshi;

use Illuminate\Database\Eloquent\Model;

class Renshi_jiaban_sub extends Model
{
	protected $fillable = [
        'applicant', 'department', 'category', 'start_date', 'end_date', 'duration', 'reason', 'remark',
    ];

}
