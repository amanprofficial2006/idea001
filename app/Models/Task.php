<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'helper_id',
        'title',
        'category',
        'description',
        'budget_type',
        'amount',
        'urgency_level',
        'duration',
        'deadline',
        'location',
        'address',
        'lat',
        'lng',
        'additional_info',
        'contact_preference',
        'privacy',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'lat' => 'decimal:6',
        'lng' => 'decimal:6',
        'deadline' => 'datetime',
        'budget_type' => 'string',
        'urgency_level' => 'string',
        'contact_preference' => 'string',
        'privacy' => 'string',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedHelper()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function helper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'helper_id');
    }

    public function skills()
    {
        return $this->hasMany(TaskSkill::class);
    }

    public function images()
    {
        return $this->hasMany(TaskImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'name');
    }
}
