<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedTask extends Model
{
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'is_active',
        'status',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deadline' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function isExpired()   { return $this->deadline && $this->deadline->isPast(); }
    public function isPending()   { return $this->status === 'pending'; }
    public function isApproved()  { return $this->status === 'approved'; }
    public function isRejected()  { return $this->status === 'rejected'; }
}
