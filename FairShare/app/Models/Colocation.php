<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'invite_code'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'colocation_user')
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(MemberShip::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function isOwner($userId)
    {
        return $this->users()
            ->where('user_id', $userId)
            ->wherePivot('role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();
    }
}
