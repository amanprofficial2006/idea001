<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    protected $table = 'organisations';

    protected $fillable = [
        'name',
        'email',
        'website',
        'contact1',
        'contact2',
        'address',
        'logo',
    ];
}
