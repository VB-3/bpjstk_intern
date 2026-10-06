<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'summary',
        'bpu_achievement',
    ];

    protected $casts = [
        'date' => 'date',
        'bpu_achievement' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
