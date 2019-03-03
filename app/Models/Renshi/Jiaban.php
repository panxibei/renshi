<?php

namespace App\Models\Renshi;

use Illuminate\Database\Eloquent\Model;

class Jiaban extends Model
{
    protected $table = 'jiabans'; 

	protected $fillable = [
        'gonghao', 'xingming', 'xinxi',
    ];

}
