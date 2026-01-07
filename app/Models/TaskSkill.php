<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSkill extends Model
{
    protected $fillable = [
        'task_id',
        'skill_name',
    ];
}
