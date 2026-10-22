<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'mentor_id',
        'gdrive_link',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isIntern()
    {
        return $this->role === 'intern';
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentees()
    {
        return $this->hasMany(User::class, 'mentor_id');
    }

    public function getFormattedGdriveLinkAttribute()
    {
        if (!$this->gdrive_link) {
            return null;
        }
        $link = trim($this->gdrive_link);
        if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
            return "https://" . $link;
        }
        return $link;
    }
}

