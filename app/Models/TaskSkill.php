<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskImage extends Model
{
    protected $fillable = [
        'task_id',
        'image',
    ];
}
